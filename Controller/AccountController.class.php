<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 18:49
 */
class AccountController extends Controller
{
    public function run()
    {
        $this->pagepicker();
    }

    public function guaranteeLogin($s)
    {
        if (!Empty($_SESSION["user"])) {

            return true;
        } else {
            $_SESSION["Redirect"] = $s;
            $this->pagepicker();
            exit(1);
        }

    }

    // check if username is taken.
    public function check()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $userRepo = new UserRepository();
            $userRepo->checkUsernameJSON($_POST["username"]);
            exit();
        } else {
            $this->pagepicker();
        }
    }

    // go to login, activate or manage account.
    private function pagepicker()
    {
        if (!Empty($_SESSION["user"]) && (empty($_GET["token"]) || (strtolower($_GET["action"])) != "activate")) {
            $this->manage();
        } else if (empty($_GET["token"]) || (strtolower($_GET["action"])) != "activate") {
            $this->login();
        } else
            $this->activate();
    }

    private function activate()
    {
        if (empty($_GET["token"])) {
            $this->redirect("/Account");
            exit(1);
        }
        $this->checkActivateToken($_GET["token"]);
        $this->redirect("/Account");
        exit();
    }

    // password recovery
    public function recover()
    {
        $userRepo = new UserRepository();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_POST["token"])) {

                // save new password
                $username = $this->checkRecoveryToken($_POST["token"]);
                $userRepo = new UserRepository();
                $res = $userRepo->recover($username);
                if ($res !== true) {
                    $this->newPasswordError($username, $res);
                }

                // reset hash & date
                $userRepo->resetHash($_POST["username"]);
                $this->redirect("/");

            } else if (!Empty($_POST["username"])) {

                // save recovery request
                $res = $userRepo->newRecover($_POST["username"], $websiteMessage);
                if ($res !== true) {
                    $this->recoverError($res);
                }
                $this->render("messageScreen.tpl", ["title" => "Email verzonden.",
                    "message" => $websiteMessage
                ]);

                exit(1);
            }
        } else if (!Empty($_GET["token"])) {

            // new password form
            $username = $this->checkRecoveryToken($_GET["token"]);
            $this->render("newPassword.tpl", ["title" => "nieuw wachtwoord", "username" => $username, "token" => $_GET["token"]]);
            exit(1);

        }
        // new recovery form
        $this->render("recover.tpl", ["title" => "Log in", "username" => ""]);

    }

    private function newPasswordError($username, $error)
    {
        $this->render("newPassword.tpl", [
            "error" => $error,
            "username" => $username,
            "token" => $_POST["token"]]);
        exit(1);
    }

    private function checkRecoveryToken($token)
    {
        $userRepo = new UserRepository();
        $this->canRecover($userRepo);
        // validate email-link
        $username = $userRepo->validateToken($token);
        if ($username === false) {
            $userRepo->logRecovery();
            $this->apologize("niet geldige token.");
        }
        return $username;
    }

    private function checkActivateToken($token)
    {
        $userRepo = new UserRepository();
        $this->canRecover($userRepo);
        // validate email-link
        $username = $userRepo->validateActivateToken($token);
        if ($username === false) {
            $userRepo->logRecovery();
        }
        return $username;
    }

    private function canRecover($userRepo)
    {
        if (!$userRepo->CanRecover()) {
            $this->apologize("Er is afgelopen 24 uur te veel malfide activiteit van dit IP adress gekomen.
            Wacht 24 uur voordat u opnieuw een activatielink of recoverylink probeert.");
        }
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $userRepo = new UserRepository();
            $res = $userRepo->login();

            if ($res !== true) {
                $this->loginError($res);
            }
            $this->redirectToPage();
            exit();

        } else {
            $this->render("login.tpl", ["title" => "Log in", "username" => ""]);
        }
    }

    private function loginError($mess)
    {
        $this->render("login.tpl", ["title" => "Log in", "error" => $mess, "username" => htmlspecialchars($_POST["username"])]);
        exit();
    }

    private function redirectToPage()
    {
        if (!empty($_SESSION["Redirect"]) && (strpos($_SESSION["Redirect"], 'admin') === false)) {
            $this->redirect($_SESSION["Redirect"]);
            $_SESSION["Redirect"] = null;
            exit();
        }
        $_SESSION["Redirect"] = null;
        $this->redirect("/");
    }

    private function recoverError($mess)
    {
        $this->render("recover.tpl", [
            "title" => "Log in",
            "error" => $mess,
            "username" => htmlspecialchars($_POST["username"])]);
        exit();
    }

    public function register()
    {
        $types = ["business", "elder", "adult", "child", "disabled"];
        // check variables
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!in_array($_GET["type"], $types)) {
                $this->render("register.tpl", [
                    "title" => "register",
                    "error" => "Formulier klopt niet."]);
                exit(1);
            }

            // compare passwords
            if ($_POST["password1"] !== $_POST["password2"] && !empty($_POST["password1"])) {
                $this->render("register.tpl", [
                    "title" => "register",
                    "error" => "wachtwoorden komen niet overeen."]);
                exit(1);
            }

            // set info array
            $arr = $_POST;
            $arr["username"] = strtolower(filter_var($_POST["username"], FILTER_SANITIZE_EMAIL));
            $arr["password"] = $_POST["password1"];
            if (isset($_POST["handicap"])) {
                $arr["handicap"] = 1;
                if (!isset($arr["handicap_info"])) {
                    $this->render("register.tpl", [
                        "title" => "register",
                        "error" => "Formulier klopt niet."]);
                    exit(1);
                }
            } else
                $arr["handicap"] = 0;

            $userRepo = new UserRepository();

            $res = $userRepo->tryRegister($arr);
            if ($res === true) {
                $mailer = new Email();

                if ($userRepo->setActivateMail($mailer, $arr["username"])) {

                    $mailer->sendMail();
                    $this->render("messageScreen.tpl", [
                        "title" => "Email verzonden.",
                        "message" =>
                            "Er is een email verstuurd naar " . $arr["username"] . " met een activatielink.
                            Deze link verschijnt binnen drie minuten.
                            als u niks binnenkrijgt, kijk alstublieft in uw spam folder."]);
                    exit(1);

                } else {
                    $this->render("register.tpl", ["title" => "register", "error" => "Mail send error"]);
                    exit(1);
                }

            }
            $this->render("register.tpl", ["title" => "register", "error" => $res]);
            exit(1);


        } else {


            // register form
            if (empty($_GET["type"]) || !in_array($_GET["type"], $types)) {
                $this->render("register.tpl", ["title" => "register"]);
                exit();
            }

            $this->render("register.tpl", ["title" => "register", "type" => $_GET["type"]]);
            exit();

        }
    }


    private function manage()
    {

        (new ProfileController())->run();
        exit();
    }


    public function logout()
    {
        $_SESSION["user"] = null;
        // redirect to main page
        $this->redirect("/");
    }
}