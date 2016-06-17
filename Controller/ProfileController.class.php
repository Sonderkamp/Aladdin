<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 8-3-2016
 * Time: 17:23
 */
class ProfileController extends Controller
{


    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/Profile");
    }

    public function run()
    {
        $this->manage();
    }

    public function saved()
    {
        $this->manage(null, "Gegevens gewijzigd");
        exit();
    }

    public function error()
    {
        if (isset($_GET["err"])) {
            $this->manage(htmlspecialchars($_GET["err"]));
            exit();
        }
        $this->manage();
        exit();
    }


    private
    function manage($error = null, $success = null)
    {
        $email = ((new UserRepository())->getCurrentUser());
        $email = $email->email;
        $donations = (new DonationRepository())->getDonations($email);

        if ($error != null) {
            $this->render("account.tpl", ["title" => "profile", "error" => $error, "donations" => $donations]);
            exit();
        }
        if ($success != null) {
            $this->render("account.tpl", ["title" => "profile", "success" => $success, "donations" => $donations]);
            exit();
        }
        $this->render("account.tpl", ["title" => "account", "donations" => $donations]);
        exit();
    }

    public
    function change()
    {
        $usermodel = new UserRepository();


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $user = $usermodel->getUser($usermodel->getCurrentUser()->email);


            $user->lat = $_POST["Lat"];
            $user->lon = $_POST["Lon"];
            $user->name = $_POST["name"];
            $user->surname = $_POST["surname"];
            $user->initials = $_POST["initials"];
            $user->address = $_POST["address"];
            $user->city = $_POST["city"];
            $user->postalcode = $_POST["postalcode"];
            $user->country = $_POST["country"];


            if (isset($_POST["handicap_info"])) {
                if (Empty(trim($_POST["handicap_info"]))) {
                    $user->handicap = 0;
                    $user->handicapInfo = null;
                } else {
                    $user->handicap = 1;
                    $user->handicapInfo = trim($_POST["handicap_info"]);
                }
            }

            $res = $usermodel->updateUser($user);

            if (!is_string($res)) {
                $this->redirect("/Profile/action=saved");
                exit();
            }
            $this->redirect("/Profile/action=error/err=" . $res);
            exit();

        }
        $this->manage("Call klopt niet");
        exit();
    }

    public
    function changepw()
    {
        $userModel = new UserRepository();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_SESSION["user"]->checkPassword($_POST["pwo"])) {

                $userModel = new UserRepository();
                if ((Empty($_POST["username"]) || !$userModel->validateUsername($_POST["username"]))) {
                    $this->manage("Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.");
                    exit();
                }
                if ($_SESSION["user"]->email != $_POST["username"]) {
                    $this->manage("Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.");
                    exit();
                }
                // check passwords
                if (Empty($_POST["password1"]) || Empty($_POST["password2"])) {
                    $this->manage("Niet alles ingevuld");
                    exit(1);
                }
                if ($_POST["password1"] != $_POST["password2"]) {
                    $this->manage("Wachtwoorden komen niet overeen.");
                    exit(1);
                }

                // save password
                if (!$userModel->newPassword($_POST["username"], $_POST["password1"])) {
                    $this->manage("Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.");
                    exit(1);
                }


                if (!Empty($_POST["username"]) && $userModel->validateUsername($_POST["username"]) && $_POST["password1"] == $_POST["password2"]) {
                    if ($_POST["password1"] != $_POST["pwo"]) {
                        $userModel->newPassword($_POST["username"], $_POST["password1"]);
                        $messagemodel = new messageRepository();
                        $messagemodel->sendMessage("Admin", $_SESSION["user"]->email, "Je wachtwoord voor Aladdin is veranderd", "Je wachtwoord voor Aladdin is veranderd heeft u dit niet zelf gedaan vraag dan een nieuw wachtwoord aan op http://localhost/Account/action=Recover");
                        $this->manage(null, "Uw wachtwoord is veranderd");
                        exit();
                    }


                }
                // new recovery creation
            } elseif (!$_SESSION["user"]->checkPassword($_POST["pwo"])) {
                $this->manage("Oud password klopt niet");
                exit(0);
            }

        }
        $this->manage();

    }
}