<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 13-5-2016
 * Time: 21:20
 */
class UserQueryBuilder
{


    public function setPassword($hashed, $username)
    {
        if (Database::query_safe("UPDATE `user` SET `Password` = ?  WHERE `Email` = ?", array($hashed, $username)) === false) {
            exit();
        }
    }

    public function IPlog($check = null)
    {
        if ($check) {
            $dayAgo = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime(date('Y-m-d H:i:s')))));
            $res = Database::query_safe("SELECT count(*) AS Counter FROM `recoveryLog` WHERE IP = ? AND `Date` BETWEEN ? AND ?", array($_SERVER['REMOTE_ADDR'], $dayAgo, date('Y-m-d H:i:s')));
            $res = $res[0];
            if ($res["Counter"] > 4)
                return false;
            return true;
        }
        Database::query_safe("INSERT INTO `recoveryLog` (`IP`, `Date`) VALUES (?, ?)", array($_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s')));


    }

    public function getMailByToken($hash, $type)
    {
        switch ($type) {
            case "recovery":
                return Database::query_safe("SELECT * FROM `user` WHERE `RecoveryHash` = ?", array($hash));
            case "validation":
                return Database::query_safe("SELECT * FROM `user` WHERE `ValidationHash` = ?", array($hash));
            default:
                echo "TYPE DOES NOT EXIST - GETMAILBYTOKEN->UserQueryBuilder()";
                exit();
        }


    }

    public function checkExistence($username)
    {
        return Database::query_safe("SELECT * FROM `user` WHERE `Email` = ? AND `ValidationHash` IS NULL", array($username));
    }

    public function addUser($array)
    {
        if (Database::query_safe("INSERT INTO `user` (`Email`, `Password`, `Name`,
            `Surname`, `RecoveryHash`, `RecoveryDate`,
            `ValidationHash`, `Address`, `Postalcode`,
            `Country`, `City`, `Dob`,
            `Gender`, `Handicap`, `DisplayName`, `Initials`) VALUES (?, ?, ?,?, NULL, NULL, ?, ?,?,?, ?,?,?,?,?,?)"
                , $array) === false
        ) {
            apologize("Er was een error bij het toevoegen van uw gegevens aan onze database. Probeer dit alstublieft opnieuw. Is dit de tweede keer dat u dit ziet, contacteer de webmaster op: Mariusdv@outlook.com");
            exit();
        }
    }

    public function clearToken($username, $type)
    {
        switch ($type) {
            case "validation":
                Database::query_safe("UPDATE `user` SET `ValidationHash` = NULL WHERE `Email` = ?", array($username));
                break;
            case "recovery":
                Database::query_safe("UPDATE `user` SET `RecoveryHash` = NULL, `RecoveryDate` = NULL WHERE `Email` = ?", array($username));
                break;

            default:
                echo "TYPE DOES NOT EXIST - clearToken->UserQueryBuilder()";
                exit();
        }
    }

    public function getTokenByName($username, $type)
    {
        switch ($type) {
            case "recovery":
                return Database::query_safe("SELECT * FROM `user` WHERE `Email` = ?", array($username))[0];
            case "validation":
                return Database::query_safe("SELECT * FROM `user` WHERE `Email` = ?", array($username))[0];
            default:
                echo "TYPE DOES NOT EXIST - getTokenByName->UserQueryBuilder()";
                exit();
        }


    }

    public function setToken($username, $hash, $type)
    {

        switch ($type) {
            case "recovery":
                Database::query_safe("UPDATE `user` SET `RecoveryHash` = ?, `RecoveryDate` = ? WHERE `Email` = ?", array($hash, date('Y-m-d H:i:s'), $username));
                return true;
            default:
                echo "TYPE DOES NOT EXIST - setToken->UserQueryBuilder()";
                exit();
        }
        return false;

    }


    public function getDisplaynames(User $matches = null)
    {
        if ($matches !== null) {
            $res = Database::query_safe("select `Email`, `DisplayName` from user where Email = ANY (SELECT DISTINCT IF(`user_Receiver` = ? ,`user_Sender`,`user_Receiver`) FROM `message` WHERE `user_Sender` = ? OR `user_Receiver` = ?)", array($matches->email, $matches->email, $matches->email));
        } else {
            $res = Database::query("SELECT `DisplayName` FROM `user`  WHERE `ValidationHash` IS NULL;");
        }


        $ret = [];
        foreach ($res as $val) {
            $ret[] = $val["DisplayName"];
        }
        return $ret;
    }

    public function setblock($block, $username, $reason = null)
    {
        $blockBool = 0;
        if ($block) {
            $blockBool = 1;

        }
        Database::query_safe("INSERT INTO blockedusers (`IsBlocked`, `Reason`, `moderator_Username`, `user_Email`) VALUES (?, ?, ?, ?)", array($blockBool, $reason, $_SESSION["admin"]->username, $username));
    }

    public function getUser($emailOrDisplayName)
    {
        return $this->createUser(Database::query_safe("SELECT * FROM user WHERE Email = ? OR DisplayName = ?", array($emailOrDisplayName, $emailOrDisplayName)));
    }

    public function isBlocked($username)
    {
        if (Database::query_safe("SELECT count(*) as count  from `blockedusers` where `user_Email` = ?", array($username))[0]["count"] == 0)
            return false;

        $status = Database::query_safe("SELECT *  from `blockedusers` where `user_Email` = ? order by DateBlocked DESC", array($username))[0];
        if ($status["IsBlocked"] == 1)
            return $status["Reason"];

        return false;
    }


    public function getAllBlocks($user)
    {
        $result = Database::query_safe("SELECT Block_Id ,DateBlocked as bdate,IsBlocked as isblocked
              from blockedusers
              where user_Email = ?
              order by Block_Id desc", array($user));
        return $result;
    }

    private function createUser($result)
    {

        if ($result == null || $result == false || count($result) == 0) {
            return false;
        }

        $newUser = new User();

        $newUser->email = $result[0]["Email"];
        $newUser->isAdmin = $result[0]["Admin"];
        $newUser->name = $result[0]["Name"];
        $newUser->surname = $result[0]["Surname"];
        $newUser->address = $result[0]["Address"];
        $newUser->handicap = $result[0]["Handicap"];
        $newUser->postalcode = $result[0]["Postalcode"];
        $newUser->country = $result[0]["Country"];
        $newUser->city = $result[0]["City"];
        $newUser->dob = $result[0]["Dob"];
        $newUser->gender = $result[0]["Gender"];
        $newUser->hash = $result[0]["ValidationHash"];
        $newUser->displayName = $result[0]["DisplayName"];
        $newUser->initials = $result[0]["Initials"];
        $newUser->RecoveryHash = $result[0]["RecoveryHash"];
        $newUser->RecoveryDate = $result[0]["RecoveryDate"];

        return $newUser;
    }
}