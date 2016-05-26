<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-3-2016
 * Time: 00:05
 */
class Admin
{

    public $username;

    private function setAdmin($username)
    {
        $this->username = $username;
        $_SESSION["admin"] = $this;
    }

    public function validate($username, $pass)
    {

        $res = Database::query_safe("Select * from moderator where Username = ?", array($username));

        if (count($res) != 1)
            return false;

        if (password_verify($pass, $res[0]["Password"])) {
            $this->setAdmin($username);
            return true;
        }
        return false;


    }

    public function login()
    {
        if (!Empty($_POST["username"]) && !Empty($_POST["password"])) {

            if ($this->validate($_POST["username"], $_POST["password"])) {
                return true;
            }
            return "gebruikersnaam/wachtwoord combinatie is niet geldig";

        }
        return "Niet alle gegevens zijn ingevuld";
    }

    public function logout()
    {
        $_SESSION["admin"] = null;
    }

    public function getCurrentAdmin()
    {

        if (empty($_SESSION["admin"])) {
            return false;
        }
        return $_SESSION["admin"];
    }
}