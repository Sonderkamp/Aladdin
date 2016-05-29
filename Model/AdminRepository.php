<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 29-5-2016
 * Time: 13:37
 */
class AdminRepository
{
    private $adminQueryBuilder;

    public function __construct()
    {
        $this->adminQueryBuilder = new AdminQueryBuilder();
    }

    // Create
    public function addAdmin()
    {
        if (empty($_POST["username"]) && empty($_POST["password"]) && empty($_POST["verifyPassword"])) {
            return "Vul a.u.b. alle velden in!";
        }

        $username = htmlspecialchars(strtolower(trim($_POST["username"])));
        $password = htmlspecialchars(trim($_POST["password"]));
        $verify = htmlspecialchars(trim($_POST["verifyPassword"]));

        if ($password !== $verify) {
            return "De wachtwoorden komen niet overeen!";
        }

        if(!$this->validPass($password)) {
            return "Het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en
            een nummer bevatten!";
        }

        $admins = $this->adminQueryBuilder->getAdmin();
        foreach ($admins as $item) {
            if (strtolower($item["Username"]) == strtolower($username)) {
                return 'De gebruikersnaam "' . $username . '" word al gebruikt!';
            }
        }

        if (!preg_match("/^[A-Za-z\\- ]+$/", $username) || !preg_match("/^[A-Za-z\\- ]+$/", $username)) {
            return "De gebruikersnaam mag alleen alphabetische characters, spaties en streepjes(-) bevatten!";
        }
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

// Read
    public function getCurrentAdmin()
    {

        if (empty($_SESSION["admin"])) {
            return false;
        }
        return $_SESSION["admin"];
    }

    public function getAdmins()
    {
        return $this->createAdminArray($this->adminQueryBuilder->getAdmin());
    }

    private function createAdminArray($result)
    {

        $returnArray = array();

        if (!Empty($result)) {

            foreach ($result as $item) {

                $admin = new Admin(
                    $item["Username"],
                    $item["CreationDate"]
                );

                array_push($returnArray, $admin);
            }
        }

        return $returnArray;
    }

// Other
    public function login()
    {
        if (!Empty($_POST["username"]) && !Empty($_POST["password"])) {

            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);

            if ($this->validate($username, $password)) {
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

    public function validate($username, $password)
    {

        $res = $this->adminQueryBuilder->getAdmin($username);

        if (count($res) != 1)
            return false;

        if (password_verify($password, $res[0]["Password"])) {
            $this->setAdmin($res[0]["Username"], $res[0]["CreationDate"]);
            return true;
        }

        return false;
    }

    private function setAdmin($username, $creationDate)
    {
        $_SESSION["admin"] = new Admin($username, $creationDate);
    }
}