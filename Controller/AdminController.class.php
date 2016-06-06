<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 20:17
 */
class AdminController extends Controller
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

        (new AdminController())->guaranteeAdmin("/admin");

        if (!empty($_GET["csv"])) {
            (new Graph())->$_GET["csv"]();

        } else {
            $donations = (new DonationRepository())->getDonations();
            $this->render("adminHome.tpl", ["title" => "Statistiek", "donations" => $donations]);
            exit();
        }


    }

    public function logout()
    {
        $adminRepo = new AdminRepository();
        $adminRepo->logout();
        $this->login();
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $adminRepo = new AdminRepository();
            $res = $adminRepo->login();
            if ($res !== true) {
                $this->loginError($res);
                exit();
            }

            if (!empty($_SESSION["Redirect"])) {
                $this->redirect($_SESSION["Redirect"]);
                $_SESSION["Redirect"] = null;
                exit();
            }
            $this->redirect("/Admin");
            exit();

        } else {
            $this->render("Admin/login.tpl", ["title" => "Log in", "username" => ""]);
        }
    }

    private function loginError($mess)
    {
        $this->render("Admin/login.tpl", ["title" => "Log in", "error" => $mess, "username" => htmlspecialchars($_POST["username"])]);
        exit();
    }
}