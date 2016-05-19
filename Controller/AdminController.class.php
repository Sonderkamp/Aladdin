<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 20:17
 */
class AdminController
{

    public function guaranteeAdmin($link)
    {
        if (!Empty($_SESSION["admin"])) {

            return true;
        } else {
            $_SESSION["Redirect"] = $link;
            $this->login();
            exit(1);
        }
    }

    public function run()
    {

        guaranteeAdmin("/admin");

        if (!empty($_GET["csv"])) {

            $val = new Graph();
            switch ($_GET["csv"]) {
                case "usersMonth":
                    $val->getUsersMonth();
                    exit();
                case "cats":
                    $val->getCats();
                    exit();
                case "monthly":
                    $val->getCatsMonth($_GET["month"]);
                    exit();
                case "age":
                    $val->getAge();
                    exit();
            }


        } else {
            render("adminHome.tpl", ["title" => "Statistiek"]);
            exit();
        }


    }

    public function logout()
    {
        $adminModel = new Admin();
        $adminModel->logout();
        $this->login();
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!Empty($_POST["username"]) && !Empty($_POST["password"])) {
                // htmlspecialchar
                $adminModel = new Admin();
                if ($adminModel->validate(htmlspecialchars($_POST["username"]), htmlspecialchars($_POST["password"]))) {
                    if (!empty($_SESSION["Redirect"])) {
                        redirect($_SESSION["Redirect"]);
                        $_SESSION["Redirect"] = null;
                        exit(0);
                    }
                    redirect("/Admin");
                    exit();
                }
                $this->loginError("gebruikersnaam/wachtwoord combinatie is niet geldig");

            }
            $this->loginError("Niet alle gegevens zijn ingevuld");
        } else {
            render("Admin/login.tpl", ["title" => "Log in", "username" => ""]);
        }
    }

    private function loginError($mess)
    {
        render("Admin/login.tpl", ["title" => "Log in", "error" => $mess, "username" => htmlspecialchars($_POST["username"])]);
        exit();
    }
}