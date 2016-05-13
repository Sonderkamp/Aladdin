<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 18:49
 */
class AccountController
{
    public function run()
    {

        if (Empty($_GET["action"])) {
            $this->pagepicker();
        } else {
            $_SESSION["Redirect"] = null;
            switch (strtolower($_GET["action"])) {
                case "register":
                    $this->register();
                    break;
                case "logout":
                    $this->logout();
                    break;
                case "recover":
                    $this->recover();
                    break;
                case "check":
                    $this->check();
                    break;
                case "activate":
                    $this->activate();
                    break;
                default:
                    $this->pagepicker();
                    break;
            }
        }
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

    private function check()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!Empty($_POST["username"])) {
                // htmlspecialchar
                $userRepo = new UserRepository();
                if ($userRepo->getUser($_POST["username"]) !== false) {
                    header('Content-Type: application/json');
                    echo json_encode(array('result' => true));
                    exit();
                }

                header('Content-Type: application/json');
                echo json_encode(array('result' => false));
                exit();
            }
            header('Content-Type: application/json');
            echo json_encode(array('result' => false));
            exit();
        } else {
            $this->pagepicker();
        }
    }

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
            redirect("/Account");
            exit(1);
        }
        $this->checkActivateToken($_GET["token"]);
        redirect("/Account");
    }

    private function recover()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_POST["token"])) {
                // newpassword-form is filled in
                $username = $this->checkRecoveryToken($_POST["token"]);
                $userRepo = new UserRepository();
                if ((Empty($_POST["username"]) || !$userRepo->validateUsername($_POST["username"]))) {
                    apologize("Invalid form.");
                }
                if ($username != $_POST["username"]) {
                    apologize("Invalid form.");
                }
                // check passwords
                if (Empty($_POST["password1"]) || Empty($_POST["password2"])) {
                    render("newPassword.tpl", ["error" => "Niet alles ingevuld", "title" => "nieuw wachtwoord", "username" => $username, "token" => $_POST["token"]]);
                    exit(1);
                }
                if ($_POST["password1"] != $_POST["password2"]) {
                    render("newPassword.tpl", ["error" => "Wachtwoorden komen niet overeen.", "title" => "nieuw wachtwoord", "username" => $username, "token" => $_POST["token"]]);
                    exit(1);
                }

                // save password
                if (!$userRepo->newPassword($_POST["username"], $_POST["password1"])) {
                    render("newPassword.tpl", ["error" => "Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.", "title" => "nieuw wachtwoord", "username" => $username, "token" => $_POST["token"]]);
                    exit(1);
                }
                // reset hash & date
                $userRepo->resetHash($_POST["username"]);
                redirect("/");


                exit(1);
            } else if (!Empty($_POST["username"])) {
                // new recovery creation
                $userRepo = new UserRepository();
                if (!$userRepo->validateUsername($_POST["username"])) {
                    $this->recoverError("Invalid username");
                }
                if ($userRepo->newHash($_POST["username"])) {
                    $mailer = new Email();
                    if ($userRepo->setRecoveryMail($mailer, $_POST["username"])) {
                        $mailer->sendMail();
                        render("messageScreen.tpl", ["title" => "Email verzonden.", "message" => "Er is een email verstuurd naar " . $_POST["username"] . " met een link om uw wachtwoord te resetten.
                    Deze link verschijnt binnen drie minuten.
                    als u niks binnenkrijgt, kijk alstublieft in uw spam folder."]);
                        exit(1);

                    } else {
                        $this->recoverError("Email send error.");
                    }

                }
                $this->recoverError("deze gebruiker heeft afgelopen 24 uur al een recovery aangevraagd.");
            }
        } else if (!Empty($_GET["token"])) {

            $username = $this->checkRecoveryToken($_GET["token"]);
            render("newPassword.tpl", ["title" => "nieuw wachtwoord", "username" => $username, "token" => $_GET["token"]]);
            exit(1);

        }
        // new recovery
        render("recover.tpl", ["title" => "Log in", "username" => ""]);

    }

    private function checkRecoveryToken($token)
    {
        $userRepo = new UserRepository();
        $this->canRecover($userRepo);
        // validate email-link
        $username = $userRepo->validateToken($token);
        if ($username === false) {
            $userRepo->logRecovery();
            apologize("niet geldige token.");
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
            apologize("niet geldige token.");

        }
        return $username;
    }

    private function canRecover($userRepo)
    {
        if (!$userRepo->CanRecover()) {
            apologize("Er is afgelopen 24 uur te veel malfide activiteit van dit IP adress gekomen. Wacht 24 uur voordat u opnieuw een activatielink of recoverylink probeert.");
        }
    }

    private function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!Empty($_POST["username"]) && !Empty($_POST["password"])) {
                // htmlspecialchar
                $userRepo = new UserRepository();
                if ($userRepo->validate(htmlspecialchars($_POST["username"]), htmlspecialchars($_POST["password"]))) {

                    if ($userRepo->isBlocked($_POST["username"]) !== false) {
                        $_SESSION["user"] = null;
                        $this->loginError("gebruiker is geblokkeerd. Reden: " . htmlspecialcharsWithNL($userRepo->isBlocked($_POST["username"])));
                        exit();
                    }
                    if (!empty($_SESSION["Redirect"])) {
                        redirect($_SESSION["Redirect"]);
                        $_SESSION["Redirect"] = null;
                        exit(0);
                    }
                    redirect("/");
                    exit();
                }
                $this->loginError("gebruikersnaam/wachtwoord combinatie is niet geldig");

            }
            $this->loginError("Niet alle gegevens zijn ingevuld");
        } else {
            render("login.tpl", ["title" => "Log in", "username" => ""]);
        }
    }

    private function loginError($mess)
    {
        render("login.tpl", ["title" => "Log in", "error" => $mess, "username" => htmlspecialchars($_POST["username"])]);
        exit();
    }

    private function recoverError($mess)
    {
        render("recover.tpl", ["title" => "Log in", "error" => $mess, "username" => htmlspecialchars($_POST["username"])]);
        exit();
    }

    private function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (Empty($_POST["username"])
                || Empty($_POST["password1"])
                || Empty($_POST["password2"])
                || Empty($_POST["name"])
                || Empty($_POST["surname"])
                || Empty($_POST["address"])
                || Empty($_POST["postalcode"])
                || Empty($_POST["country"])
                || Empty($_POST["city"])
                || Empty($_POST["initial"])
                || Empty($_POST["dob"])
                || Empty($_POST["gender"])
            ) {
                render("register.tpl", ["title" => "register", "error" => "Vul AUB alles in"]);
                exit(1);
            }

            // Validate stuff
            if ($_POST["password1"] != $_POST["password2"]) {
                render("register.tpl", ["title" => "register", "error" => "wachtwoorden komen niet overeen."]);
                exit(1);
            }

            $arr = [];
            $arr["username"] = strtolower(filter_var($_POST["username"], FILTER_SANITIZE_EMAIL));
            $arr["password"] = $_POST["password1"];
            $arr["name"] = $_POST["name"];
            $arr["surname"] = $_POST["surname"];
            $arr["address"] = $_POST["address"];
            $arr["postalcode"] = $_POST["postalcode"];
            $arr["country"] = $_POST["country"];
            $arr["city"] = $_POST["city"];
            $arr["dob"] = $_POST["dob"];
            $arr["initial"] = $_POST["initial"];
            $arr["gender"] = $_POST["gender"];
            if (isset($_POST["handicap"]))
                $arr["handicap"] = 1;
            else
                $arr["handicap"] = 0;


            $userRepo = new UserRepository();
            $res = $userRepo->tryRegister($arr);
            if ($res === true) {
                $mailer = new Email();
                if ($userRepo->setActivateMail($mailer, $arr["username"])) {
                    $mailer->sendMail();
                    render("messageScreen.tpl", ["title" => "Email verzonden.", "message" => "Er is een email verstuurd naar " . $arr["username"] . " met een activatielink.
                    Deze link verschijnt binnen drie minuten.
                    als u niks binnenkrijgt, kijk alstublieft in uw spam folder."]);
                    exit(1);

                } else {
                    render("register.tpl", ["title" => "register", "error" => "Mail send error"]);
                    exit(1);
                }

            }
            render("register.tpl", ["title" => "register", "error" => $res]);
            exit(1);
            // usermodel register
            // send email with register token.


        } else {

            if (empty($_GET["type"])) {
                render("register.tpl", ["title" => "register"]);
                exit();
            }
            render("register.tpl", ["title" => "register", "type" => $_GET["type"]]);
            exit();

        }
    }


    private function manage()
    {
        //        // todo: render user-manage screen
        //        echo $_SESSION["user"]->email;

        render("account.tpl", ["title" => "account"]);
        exit();
    }


    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */

    public function logout()
    {
//        // unset any session variables?
//        $_SESSION = [];
//        // expire cookie
//        if (!empty($_COOKIE[session_name()])) {
//            setcookie(session_name(), "", time() - 42000);
//        }
//
//        // destroy session
//        session_destroy();
        $_SESSION["user"] = null;
        // redirect to main page
        redirect("/");
    }
}