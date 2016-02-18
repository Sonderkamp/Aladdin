<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 19:02
 */
class User
{
    public $email, $isAdmin, $name, $surname, $token, $adresses;

    public function validate($username, $password)
    {
        if ($this->validateUsername($username)) {
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            $res = $this->getUser($username);

            if ($res === false) {
                return false;
            } else if (password_verify($password, $res["Password"])) {
                $this->email = strtolower($username);
                $this->isAdmin = $res["IsAdmin"];
                $this->name = $res["Name"];
                $this->surname = $res["Surname"];
                $this->adresses = $this->getAdresses($username);
                $_SESSION["user"] = $this;
                return true;
            }
        }
        return false;
    }

    public function getAdresses($username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        $res = Database::query_safe("SELECT * FROM `users` WHERE `Email` = ?", array($username));
        return $res;
    }

    public function validateUsername($username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));

        // Validate e-mail
        if (!filter_var($username, FILTER_VALIDATE_EMAIL) === false) {


            $res = Database::query_safe("SELECT * FROM `users` WHERE `Email` = ? AND `ValidationHash` IS NULL", array($username));
            if ($res == null)
                return false;
            return true;
        }
        return false;

    }

    private function getUser($username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        $res = Database::query_safe("SELECT * FROM `users` WHERE `Email` = ?", array($username));
        if ($res == null) {
            return false;
        }
        $res = $res[0];
        return $res;
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
            if (!Database::query_safe("UPDATE `users` SET `Password` = ?  WHERE `Email` = ?", array($hashed, $username))) {
                echo "Query error: \"UPDATE `users` SET `Password` = '$hashed'  WHERE `Email` = '$username'\"";
                exit();
            }
            return true;
        }
        return false;

    }

    private function validPass($password)
    {
        if (strlen($password) < 8
            || !preg_match('/[0-9]/', $password)
            || !preg_match('/[A-Z]/', $password)
            || !preg_match('/[a-z]/', $password)
            || !preg_match('/[\'^£$!%&*()}{@#~?><>,|=_+¬-]/', $password)
        )
            return false;
        return true;
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
            || Empty($array["province"])
            || Empty($array["city"])
        ) {
            return "Niet alles is ingevuld.";
        }

        $array["username"] = strtolower(filter_var($array["username"], FILTER_SANITIZE_EMAIL));

        if (!$this->validPass($array["password"])) {
            return "het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter,
            een nummer en een speciaal teken bevatten.";
        }
        if (!preg_match("/^[A-Za-z ]+$/", $array["name"]) || !preg_match("/^[A-Za-z ]+$/", $array["surname"])) {
            return "Naam mag alleen aphabetische characters bevatten.";
        }

        if ($this->getUser($array["username"]) !== false) {
            return "Dit emailadress heeft al een account.";
        }

        // NO checks for:
//        $array["address"]
//        $array["postalcode"]
//        $array["country"]
//        $array["province"]
//        $array["city"]

        // SQL
        $hashed = password_hash($array["password"], PASSWORD_DEFAULT);
        $this->token = bin2hex(openssl_random_pseudo_bytes(16));

        if (!Database::query_safe("INSERT INTO `users` (`Email`, `Password`, `Name`, `Surname`, `RecoveryHash`, `RecoveryDate`, `ValidationHash`) VALUES (?, ?, ?,?, NULL, NULL, ?)"
            , array($array["username"], $hashed, $array["name"], $array["surname"], $this->token))
        ) {
            echo "Query error:\"INSERT INTO `users` (`Email`, `Password`, `Name`, `Surname`, `RecoveryHash`, `RecoveryDate`, `ValidationHash`)
            VALUES (" . $array["username"] . ", " . $hashed . ", " . $array["name"] . ", " . $array["surname"] . ", NULL, NULL, '$this->token')\"";
            exit();
        }

        if (!Database::query_safe("INSERT INTO `address` (`Id`, `Zipcode`, `Address`, `City`, `Province`, `Country`, `Users_Email`) VALUES (NULL, ?, ?, ?,?, ?, ?)",
            array($array["postalcode"], $array["address"], $array["city"], $array["province"], $array["country"], $array["username"]))
        ) {
            echo "Query error: ADDRESS ADD";
            exit();
        }


        return true;
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
                if (!Database::query_safe("UPDATE `users` SET `RecoveryHash` = ?, `RecoveryDate` = ? WHERE `Email` = ?", array($this->token, date('Y-m-d H:i:s'), $username))) {
                    echo "Query error: \"UPDATE `users` SET `RecoveryHash` = '$this->token', `RecoveryDate` = '" . date('Y-m-d H:i:s') . "' WHERE `Email` = '$username'\"";
                    exit();
                }
                return true;
            }

        }
        return false;
    }

    public function resetHash($username)
    {
        if ($this->validateUsername($username)) {
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            if (!Database::query_safe("UPDATE `users` SET `RecoveryHash` = NULL, `RecoveryDate` = NULL WHERE `Email` = ?", array($username))) {
                echo "Query error: \"UPDATE `users` SET `RecoveryHash` = NULL, `RecoveryDate` = NULL WHERE `Email` = '$username'";
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
        $res = Database::query_safe("SELECT * FROM `users` WHERE `RecoveryHash` = ?", array($token));

        if ($res == null)
            return false;
        if ($this->hoursPassed($res[0]["RecoveryDate"]) >= 24)
            return false;

        return $res[0]["Email"];
    }

    public function validateActivateToken($token)
    {
        $res = Database::query_safe("SELECT * FROM `users` WHERE `ValidationHash` = ?", array($token));
        $res= $res[0];
        if ($res == null)
            return false;

        // Clear
        if (!Database::query_safe("UPDATE `users` SET `ValidationHash` = NULL WHERE `Email` = ?", array($res["Email"]))) {
            echo "Query error: UPDATE `users` SET `ValidationHash` = NULL WHERE `Email` = " . $res["Email"];
            exit();
        }

        return $res["Email"];
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
            http://" . $_SERVER["SERVER_NAME"] . "/account/action=recover/token=" . $this->token . "\n
            Deze link is 24 uur geldig \n

            Met vriendelijke groet,\n
            Webshop";

            return true;
        } else {
            return false;
        }
    }

    public function setActivateMail($mail, $username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        $val = $this->getUser($username);
        if ($val === false)
            return false;

        // Get
        $mail->to = $username;
        $mail->toName = $val["Name"] . " " . $val["Surname"];;
        $mail->subject = "Activeer Account Webshop";
        $mail->message =
            "Beste " . $val["Name"] . ",\n
            Deze mail is verstuurd omdat u een nieuw account aan heeft gemaakt.\n
            Om uw account te activeren, ga naar deze link:\n
            http://" . $_SERVER["SERVER_NAME"] . "/account/action=activate/token=" . $this->token . "\n

            Met vriendelijke groet,\n
            Webshop";
        return true;

    }

}