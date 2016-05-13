<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 26-04-16
 * Time: 16:39
 */
class UserRepository
{

    public function getUser($emailOrDisplayName)
    {
        $result = Database::query_safe("SELECT * FROM user WHERE Email = ? OR DisplayName = ?", array($emailOrDisplayName, $emailOrDisplayName));
        return $this->createUser($result);
    }

    public function blockUser($username)
    {
        Database::query_safe("INSERT INTO blockedusers (`IsBlocked`, `Reason`, `moderator_Username`, `user_Email`) VALUES (1, 'xxxxx', 'Admin', ?)", array($username));


    }

    public function unblockUser($username)
    {
        Database::query_safe("INSERT INTO blockedusers (`IsBlocked`, `Reason`, `moderator_Username`, `user_Email`) VALUES (0, 'xxxxx', 'Admin', ?)", array($username));

    }


    public function createUser($result)
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
        $newUser->displayName = $result[0]["DisplayName"];
        $newUser->initials = $result[0]["Initials"];

        return $newUser;
    }

    public function validate($username, $password)
    {
        if ($this->validateUsername($username)) {
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            $res = $this->getUser($username);

            if ($res === false) {
                return false;
            } else if ($res->checkPassword($password)) {
                $_SESSION["user"] = $res;
                return true;
            }
        }
        return false;
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

    public function getBlockStatus($username)
    {
        // query om alle blocks van een user te zien
        $result = Database::query_safe("SELECT Block_Id,DateBlocked,user_Email,IsBlocked as IsBlocked
from blockedusers
 where user_Email = ?
              order by DateBlocked asc", array($username));
        $result = $result[0];
        return $result;
    }


    public function getLastBlockStatus($username)
    {
        // query om de laatste block van een user te zien
        $result = Database::query_safe("SELECT Block_Id,DateBlocked,Reason, user_Email,IsBlocked as IsBlocked
from blockedusers
where DateBlocked =
        (select
max(blockedusers.DateBlocked) AS max_date
              FROM blockedusers
              where user_Email = ?)
              order by DateBlocked asc", array($username));
        $result = $result[0];
        return $result;
    }

    public function getAllBlocks($user)
    {
        $result = Database::query_safe("SELECT Block_Id ,DateBlocked as bdate,IsBlocked as isblocked
              from blockedusers
              where user_Email = ?
              order by Block_Id desc", array($user));
        return $result;
    }


    public function resetHash($username)
    {
        if ($this->validateUsername($username)) {
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            if (Database::query_safe("UPDATE `user` SET `RecoveryHash` = NULL, `RecoveryDate` = NULL WHERE `Email` = ?", array($username)) === false) {
                echo "Query error: \"UPDATE `user` SET `RecoveryHash` = NULL, `RecoveryDate` = NULL WHERE `Email` = '$username'";
                exit();
            }
        }
    }

    public function CanRecover()
    {
        $dayAgo = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime(date('Y-m-d H:i:s')))));
        $res = Database::query_safe("SELECT count(*) AS Counter FROM `recoveryLog` WHERE IP = ? AND `Date` BETWEEN ? AND ?", array($_SERVER['REMOTE_ADDR'], $dayAgo, date('Y-m-d H:i:s')));
        $res = $res[0];
        if ($res["Counter"] > 4)
            return false;
        return true;
    }

    public function logRecovery()
    {
        Database::query_safe("INSERT INTO `recoveryLog` (`IP`, `Date`) VALUES (?, ?)", array($_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s')));
    }

    public function validateToken($token)
    {
        $res = Database::query_safe("SELECT * FROM `user` WHERE `RecoveryHash` = ?", array($token));

        if ($res == null)
            return false;
        if ($this->hoursPassed($res[0]["RecoveryDate"]) >= 24)
            return false;

        return $res[0]["Email"];
    }

    public function setActivateMail($mail, $username, $token)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        $val = $this->getUser($username);
        if ($val === false)
            return false;

        // Get
        $mail->to = $username;
        $mail->toName = $val["Name"] . " " . $val["Surname"];
        $mail->subject = "Activeer Account Webshop";
        $mail->message =
            "Beste " . $val["Name"] . ",\n
            Deze mail is verstuurd omdat u een nieuw account aan heeft gemaakt.\n
            Om uw account te activeren, ga naar deze link:\n
            http://" . $_SERVER["SERVER_NAME"] . "/account/action=activate/token=" . $token . "\n

            Met vriendelijke groet,\n
            Webshop";
        return true;

    }

    public function validateActivateToken($token)
    {
        $res = Database::query_safe("SELECT * FROM `user` WHERE `ValidationHash` = ?", array($token));
        if ($res == null || $res === false)
            return false;
        $res = $res[0];

        // Clear
        if (Database::query_safe("UPDATE `user` SET `ValidationHash` = NULL WHERE `Email` = ?", array($res["Email"])) === false) {
            exit();
        }

        return $res["Email"];
    }

    public function updateUser($arr)
    {
        if (Empty($arr["email"])
            || Empty($arr["name"])
            || Empty($arr["surname"])
            || Empty($arr["address"])
            || Empty($arr["postalcode"])
            || Empty($arr["country"])
            || Empty($arr["city"])
            || Empty($arr["dob"])
            || Empty($arr["initials"])
            || Empty($arr["gender"])
        ) {
            return "Niet alles is ingevuld.";
        }

        $arr["username"] = strtolower(trim($arr["email"]));
        $arr["name"] = strtolower(trim($arr["name"]));
        $arr["surname"] = ucfirst(trim($arr["surname"]));
        $arr["address"] = strtolower(trim($arr["address"]));
        $arr["postalcode"] = strtoupper(trim($arr["postalcode"]));
        $arr["country"] = strtolower(trim($arr["country"]));
        $arr["city"] = strtolower(trim($arr["city"]));
        $arr["dob"] = trim($arr["dob"]);
        $arr["initial"] = strtoupper(trim($arr["initials"]));
        $arr["gender"] = strtolower(trim($arr["gender"]));

        if ($this->validateUser($arr) === false) {
            return "Validatie mislukt. check uw gegevens. Voor interactieve validatie, zet uw javascipt aan.";
        }
        $d = DateTime::createFromFormat('d-m-Y', $arr["dob"]);
        $us = $this->getUser($arr["email"]);
        if (!(strtolower($us->initials) == strtolower($arr["initial"]) && strtolower($us->surname) == strtolower($arr["surname"]))) {

            $newname = array("initial" => $arr["initial"], "surname" => $arr["surname"]);
            $newdisplay = $this->createDislay($newname);
        } else {
            $newdisplay = $us->displayName;
        }
        if ($arr["handicap"] != 1) {
            $arr["handicap"] = 0;
        }
        Database::query_safe("UPDATE user SET `Name`=?, `Surname`=?, `Address`=?,`Postalcode`=?,`Country`=?,`City`=?,`Dob`=?,`Initials`=?,`Gender`=?,`Handicap`=?,`DisplayName`=?  WHERE Email=?", Array($arr["name"], $arr["surname"], $arr["address"], $arr["postalcode"], $arr["country"], $arr["city"], $d->format('Y-m-d'), $arr["initial"], $arr["gender"], $arr["handicap"], $newdisplay, $arr["username"]));

        if ($arr["email"] === $_SESSION["user"]->email) {
            // Update
            $_SESSION["user"] = $this->getUser($arr["email"]);
        }
    }

    public function validateUser($array)
    {
//        var_dump($array["dob"]);
        $array["username"] = strtolower(trim($array["username"]));
        $array["name"] = strtolower(trim($array["name"]));
        $array["surname"] = trim($array["surname"]);
        $array["address"] = strtolower(trim($array["address"]));
        $array["postalcode"] = strtoupper(trim($array["postalcode"]));
        $array["country"] = strtolower(trim($array["country"]));
        $array["city"] = strtolower(trim($array["city"]));
        $array["dob"] = trim($array["dob"]);
        $array["initial"] = strtoupper(trim($array["initial"]));
        $array["gender"] = strtolower(trim($array["gender"]));

        // USERNAME
        // valid email
        // NAME
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $array["name"]) == false)
            return false;


        // SURNAME
        //[a-zA-Z][a-zA-Z ]+$
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $array["surname"]) == false)
            return false;

        // ADDRESS
        if (preg_match("/^[a-zA-Z][A-Za-z0-9\\- ]+$/", $array["address"]) == false)
            return false;

        // POSTALCODE
        //data-validation-regexp="^[0-9]{4}[\s]{0,1}[a-zA-z]{2}"
        if (preg_match("/^[0-9]{4}[\s]{0,1}[a-zA-z]{2}/", $array["postalcode"]) == false)
            return false;

        // COUNTRY
        //[a-zA-Z][a-zA-Z ]+$
        if (preg_match("/^[a-zA-Z][a-zA-Z ]+$/", $array["country"]) == false)
            return false;

        // CITY
        //[a-zA-Z][a-zA-Z ]+$
        if (preg_match("/^[a-zA-Z][a-zA-Z ]+$/", $array["city"]) == false)
            return false;

        // INITIALS
        // data-validation-regexp="^([a-zA-Z\.]+)$"
        if (preg_match("/^([a-zA-Z\.]+)$/", $array["initial"]) == false)
            return false;

        // DOB

        $d = DateTime::createFromFormat('d-m-Y', $array["dob"]);
        if (($d && $d->format('d-m-Y') == $array["dob"]) === false)
            return false;

        // GENDER
        if ($array["gender"] != "male" && $array["gender"] != "female" && $array["gender"] != "other")
            return false;

        return true;

    }

    private function hoursPassed($time)
    {
        if ($time == null) {
            return 0;
        }

        $date1 = new DateTime();
        $date2 = new DateTime($time);

        $diff = $date2->diff($date1);
        $diff = ($diff->y * 12 * 30 * 24) + ($diff->m * 30 * 24) + ($diff->d * 24) + ($diff->h);
        return $diff;

    }

    public function setRecoveryMail($mail, $username)
    {
        if ($this->validateUsername($username)) {
            // getName
            $val = $this->getUser($username);
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            // Get
            $mail->to = $username;
            $mail->toName = $val["Name"] . " " . $val["Surname"];;
            $mail->subject = "Wachtwoord vergeten Webshop";
            $mail->message =
                "Beste " . $val["Name"] . ",\n
            Deze mail is verstuurd omdat u uw wachtwoord vergeten bent.\n
            Om een nieuw wachtwoord in te stellen, ga naar deze link:\n
            http://" . $_SERVER["SERVER_NAME"] . "/account/action=recover/token=" . $val->token . "\n
            Deze link is 24 uur geldig \n

            Met vriendelijke groet,\n
            Webshop";

            return true;
        } else {
            return false;
        }
    }


    public function newHash($username)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes(16));
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        if ($this->validateUsername($username)) {

            $res = $this->getUser($username);
            if ($res === false)
                return false;

            if ($res["RecoveryHash"] == null || $this->hoursPassed($res["RecoveryDate"]) >= 24) {
                if (Database::query_safe("UPDATE `user` SET `RecoveryHash` = ?, `RecoveryDate` = ? WHERE `Email` = ?", array($res->token, date('Y-m-d H:i:s'), $username)) === false) {
                    echo "Query error: \"UPDATE `user` SET `RecoveryHash` = '$this->token', `RecoveryDate` = '" . date('Y-m-d H:i:s') . "' WHERE `Email` = '$username'\"";
                    exit();
                }
                return true;
            }

        }
        return false;
    }


    public function tryRegister($array)
    {
        if (Empty($array["username"])
            || Empty($array["password"])
            || Empty($array["name"])
            || Empty($array["surname"])
            || Empty($array["address"])
            || Empty($array["postalcode"])
            || Empty($array["country"])
            || Empty($array["city"])
            || Empty($array["dob"])
            || Empty($array["initial"])
            || Empty($array["gender"])
            || !isset($array["handicap"])
        ) {
            return "Niet alles is ingevuld.";
        }

        $array["username"] = strtolower(trim($array["username"]));
        $array["name"] = strtolower(trim($array["name"]));
        $array["surname"] = trim($array["surname"]);
        $array["address"] = strtolower(trim($array["address"]));
        $array["postalcode"] = strtoupper(trim($array["postalcode"]));
        $array["country"] = strtolower(trim($array["country"]));
        $array["city"] = strtolower(trim($array["city"]));
        $array["dob"] = trim($array["dob"]);
        $array["initial"] = strtoupper(trim($array["initial"]));
        $array["gender"] = strtolower(trim($array["gender"]));
        $array["initial"] = trim($array["initial"], '.');
        $array["username"] = strtolower(filter_var($array["username"], FILTER_SANITIZE_EMAIL));

        if (!$this->validPass($array["password"])) {
            return "het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en
            een nummer bevatten.";
        }
        if (!preg_match("/^[A-Za-z\\- ]+$/", $array["name"]) || !preg_match("/^[A-Za-z\\- ]+$/", $array["surname"])) {
            return "Naam mag alleen alphabetische characters, spaties en streepjes(-) bevatten.";
        }

        if ($this->getUser($array["username"]) !== false) {
            return "Dit emailadress heeft al een account.";
        }


        $displayname = $this->createDislay($array);
        $array["postalcode"] = preg_replace('/\s+/', '', $array["postalcode"]);


        if ($this->validateUser($array) === false) {
            return "Validatie mislukt. check uw gegevens. Voor interactieve validatie, zet uw javascipt aan.";
        }

        $d = DateTime::createFromFormat('d-m-Y', $array["dob"]);


        // SQL
        $hashed = password_hash($array["password"], PASSWORD_DEFAULT);
        $this->token = bin2hex(openssl_random_pseudo_bytes(16));


        if (Database::query_safe("INSERT INTO `user` (`Email`, `Password`, `Name`,
            `Surname`, `RecoveryHash`, `RecoveryDate`,
            `ValidationHash`, `Address`, `Postalcode`,
            `Country`, `City`, `Dob`,
            `Gender`, `Handicap`, `DisplayName`, `Initials`) VALUES (?, ?, ?,?, NULL, NULL, ?, ?,?,?, ?,?,?,?,?,?)"
                , array(strtolower($array["username"]), $hashed, strtolower($array["name"]),
                    $array["surname"], $this->token, $array["address"],
                    $array["postalcode"], $array["country"], $array["city"],
                    $d->format('Y-m-d'), $array["gender"], $array["handicap"], $displayname, $array["initial"])) === false
        ) {
            apologize("Er was een error bij het toevoegen van uw gegevens aan onze database. Probeer dit alstublieft opnieuw. Is dit de tweede keer dat u dit ziet, contacteer de webmaster op: Mariusdv@outlook.com");
            exit();
        }
        return true;
    }

    public function createDislay($arr)
    {
        $arr["initial"] = strtoupper(trim($arr["initial"], '.'));
        $name = $arr["initial"] . ". " . ucfirst($arr["surname"]);

        // first try
        $res = Database::query_safe("SELECT count(*) AS Counter FROM `user` WHERE DisplayName LIKE ? ", array($name));
        $res = $res[0];
        if ($res["Counter"] == 0)
            return $name;
        return $name . $res["Counter"];
    }

    private function validPass($password)
    {
        if (strlen($password) < 8
            || !preg_match('/[0-9]/', $password)
            || !preg_match('/[A-Z]/', $password)
            || !preg_match('/[a-z]/', $password)
        )
            return false;
        return true;
    }


    public function getAllMatchedDislaynames($user)
    {
        $res = Database::query_safe("select `Email`, `DisplayName` from user where Email = ANY (SELECT DISTINCT IF(`user_Receiver` = ? ,`user_Sender`,`user_Receiver`) FROM `message` WHERE `user_Sender` = ? OR `user_Receiver` = ?)", array($user->email, $user->email, $user->email));

        //$res = Database::query("select `Email`, `DisplayName` from user");

        $ret = [];
        foreach ($res as $val) {
            $ret[] = $val["DisplayName"];
        }
        return $ret;
    }

    public function getUsername($display)
    {
        $res = Database::query_safe("SELECT `Email` FROM `user` WHERE `DisplayName` = ? AND `ValidationHash` IS NULL", array($display));
        if ($res == null)
            return false;

        $res = $res[0];
        return $res["Email"];
    }

    public function validateUsername($username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));

        // Validate e-mail
        if (!filter_var($username, FILTER_VALIDATE_EMAIL) === false) {


            $res = Database::query_safe("SELECT * FROM `user` WHERE `Email` = ? AND `ValidationHash` IS NULL", array($username));
            if ($res == null)
                return false;
            return true;
        }
        return false;

    }


    public function getAllDislaynames()
    {
        $res = Database::query("SELECT `DisplayName` FROM `user`  WHERE `ValidationHash` IS NULL;");

        $ret = [];
        foreach ($res as $val) {
            $ret[] = $val["DisplayName"];
        }
        return $ret;
    }

    public function newPassword($username, $password)
    {
        if ($this->validateUsername($username)) {

            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            // validate password
            // Wachtwoord moet minimaal 8 tekens lang, een nummer, een hoofdletter, een kleine letter en een speciaal teken bevatten.
            if (!$this->validPass($password))
                return false;

            // save password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            if (Database::query_safe("UPDATE `user` SET `Password` = ?  WHERE `Email` = ?", array($hashed, $username)) === false) {
                echo "Query error: \"UPDATE `user` SET `Password` = '$hashed'  WHERE `Email` = '$username'\"";
                exit();
            }
            return true;
        }
        return false;

    }

}