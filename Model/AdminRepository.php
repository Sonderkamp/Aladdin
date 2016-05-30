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
        if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["verifyPassword"])) {
            return "Vul a.u.b. alle velden in!";
        }

        $username = htmlspecialchars(strtolower(trim($_POST["username"])));
        $password = htmlspecialchars(trim($_POST["password"]));
        $verify = htmlspecialchars(trim($_POST["verifyPassword"]));

        if ($password !== $verify) {
            return "De wachtwoorden komen niet overeen!";
        }

        if (!$this->validPass($password)) {
            return "Het wachtwoord moet minimaal 8 tekens en maximaal 60 tekens lang zijn, een hoofdletter, een kleine letter en
            een nummer bevatten!";
        }

        if (!$this->validUsername($username, $message)) {
            return $message;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $this->adminQueryBuilder->addAdmin($username, $hashed);

        return true;
    }


    private function validPass($password)
    {
        if (strlen($password) < 8 || strlen($password) > 60
            || !preg_match('/[0-9]/', $password)
            || !preg_match('/[A-Z]/', $password)
            || !preg_match('/[a-z]/', $password)
        )
            return false;
        return true;
    }

    private function validUsername($username, &$message)
    {
        $admins = $this->adminQueryBuilder->getAdmin();
        foreach ($admins as $item) {
            if (strtolower($item["Username"]) == strtolower($username)) {
                $message = 'De gebruikersnaam "' . $username . '" word al gebruikt!';
                return false;
            }
        }

        if (strlen($username) > 45) {
            $message = "De gebruikernaam mag maximaal 45 tekens lang zijn!";
            return false;
        }

        if (!preg_match("/^[A-Za-z\\- ]+$/", $username) || !preg_match("/^[A-Za-z\\- ]+$/", $username)) {
            $message = "De gebruikersnaam mag alleen alphabetische characters, spaties en streepjes(-) bevatten!";
            return false;
        }
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

    // Update
    public function changePassword()
    {
        if (empty($_POST["oldUsername"])) {
            return "Oeps... Er ging iets fout...";
        } else {
            $oldUsername = htmlspecialchars(trim($_POST["oldUsername"]));
        }

        $admin = $this->adminQueryBuilder->getAdmin($oldUsername);
        if (empty($admin)) {
            return "Oeps... Er ging iets fout...";
        }

        $date1 = strftime(" %H:%M %#d %B %Y", strtotime($admin[0]["CreationDate"]));
        $date2 = strftime(" %H:%M %#d %B %Y", strtotime($this->getCurrentAdmin()->creationDate));
        if ($date1 <= $date2) {
            return "Deze admin kan niet door u gewijzigd worden";
        }

        if (empty($_POST["password"]) && empty(!$_POST["verifyPassword"])) {
            return "Vul een wachtwoord in!";
        }

        $password = htmlspecialchars(trim($_POST["password"]));
        $verify = htmlspecialchars(trim($_POST["verifyPassword"]));

        if ($password !== $verify) {
            return "De wachtwoorden komen niet overeen!";
        }

        if (!$this->validPass($password)) {
            return "Het wachtwoord moet minimaal 8 tekens en maximaal 60 tekens lang zijn, een hoofdletter, een kleine letter en
            een nummer bevatten!";
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $this->adminQueryBuilder->changePassword($hashed, $oldUsername);

        return true;
    }

    public function blockAdmin()
    {
        if(empty($_GET["admin"]))
            return false;
        
        $username = htmlspecialchars($_GET["admin"]);
        
        $this->adminQueryBuilder->blockAdmin($username);
        return true;
    }

    public function unblockAdmin()
    {
        if(empty($_GET["admin"]))
            return false;

        $username = htmlspecialchars($_GET["admin"]);

        $this->adminQueryBuilder->unblockAdmin($username);
        return true;
    }

    private function createAdminArray($result)
    {

        $returnArray = array();

        if (!Empty($result)) {

            foreach ($result as $item) {

                $admin = new Admin(
                    $item["Username"],
                    $item["CreationDate"],
                    $item["IsActive"]
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

            if ($this->validate($username, $password, $message)) {
                return true;
            }
            return $message;

        }
        return "Niet alle gegevens zijn ingevuld";
    }

    public function logout()
    {
        $_SESSION["admin"] = null;
    }

    public function validate($username, $password, &$message)
    {

        $res = $this->adminQueryBuilder->getAdmin($username);

        if (count($res) != 1) {
            $message = "gebruikersnaam/wachtwoord combinatie is niet geldig";
            return false;
        }

        if ($res[0]["IsActive"] == 0) {
            $message = "Dit account is op non-actief gesteld";
            return false;
        }

        if (password_verify($password, $res[0]["Password"])) {
            $this->setAdmin($res[0]["Username"], $res[0]["CreationDate"], $res[0]["IsActive"]);
            return true;
        }

        $message = "gebruikersnaam/wachtwoord combinatie is niet geldig";
        return false;
    }

    private function setAdmin($username, $creationDate, $isActive)
    {
        $_SESSION["admin"] = new Admin($username, $creationDate, $isActive);
    }
}