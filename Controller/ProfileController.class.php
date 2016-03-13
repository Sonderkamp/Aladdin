<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 8-3-2016
 * Time: 17:23
 */
class ProfileController
{


    public function run()
    {
        guaranteeLogin("/Profile");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "change":
                    $this->changeDetails();
                    break;
                case "changepw":
                    $this->changePass();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->manage();
        }
    }



//    public function checkgender()
//    {
//        $myGender = $_SESSION["user"]->gender;
//        // dit werkt maar ik moet alleen nog weten hoe ik vanuit deze class het geslacht van een user kan opvragen
//        if ($myGender == 'male') {
//            $result = "
//                <input type='radio' name='gender' value ='male' checked> Man
//                <input type='radio' name='gender' value ='female'> Vrouw
//                <input type='radio' name='gender' value ='other'> Anders";
//        } else if ($myGender == 'female') {
//            $result = "
//                <input type='radio' name='gender' value ='male'> Man
//                <input type='radio' name='gender' value ='female'checked> Vrouw
//                <input type='radio' name='gender' value ='other'> Anders";
//        } else if ($myGender == 'other') {
//            $result = "
//                <input type='radio' name='gender' value ='male'> Man
//                <input type='radio' name='gender' value ='female'> Vrouw
//                <input type='radio' name='gender' value ='other' checked> Anders";
//        } else {
//            $result = "
//                <input type='radio' name='gender'  value ='male'> Man
//                <input type='radio' name='gender'  value ='female'> Vrouw
//                <input type='radio' name='gender'  value ='other'> Anders";
//        };
//
//        return $result;
//    }

//    public function checkHandicap()
//    {
//        $myHandicap = $_SESSION["user"]->handicap;
//        if ($myHandicap == true) {
//            $result = "
//                <input type='checkbox' name='handicap' checked>";
//        } else {
//            $result = "
//                <input type='checkbox' name='handicap' >";
//        };
//        return $result;
//    }

    private function manage()
    {

        render("account.php", ["title" => "profile", "error" => ""]);
        exit();
    }

    private function changeDetails()
    {


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (Empty($_POST["name"])
                || Empty($_POST["surname"])
                || Empty($_POST["address"])
                || Empty($_POST["postalcode"])
                || Empty($_POST["country"])
                || Empty($_POST["city"])
                || Empty($_POST["initials"])
                || Empty($_POST["dob"])
                || Empty($_POST["gender"])
            ) {
                render("account.php", ["title" => "profile", "error" => "Vul AUB alles in"]);
                exit(1);
            }

            $arr = [];
            $arr["email"] = strtolower(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));

            $arr["name"] = strtolower($_POST["name"]);
            $arr["surname"] = $_POST["surname"];
            $arr["address"] = $_POST["address"];
            $arr["postalcode"] = $_POST["postalcode"];
            $arr["country"] = $_POST["country"];
            $arr["city"] = $_POST["city"];
            $arr["dob"] = $_POST["dob"];
            $arr["initials"] = $_POST["initials"];
            $arr["gender"] = $_POST["gender"];

            if (Empty($_POST["handicap"]))
                $arr["handicap"] = false;
            else
                $arr["handicap"] = true;

echo $_SESSION["user"]->checkPassword($_SESSION["user"]->email)["password"];
            $_SESSION["user"]->updateUser($arr);
            render("account.php", ["title" => "profile", "error" => ""]);


        }
    }
    private function changePass()
    {
        $userModel = new User();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if($_SESSION["user"]->checkPassword($_POST["pwo"])) {

                $userModel = new User();
                if ((Empty($_POST["username"]) || !$userModel->validateUsername($_POST["username"]))) {
                    render("account.php", ["error" => "Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.", "title" => "nieuw wachtwoord"]);
                    exit();
                }
                if ($_SESSION["user"]->email != $_POST["username"]) {
                    render("account.php", ["error" => "Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.", "title" => "nieuw wachtwoord"]);
                    exit();
                }
                // check passwords
                if (Empty($_POST["password1"]) || Empty($_POST["password2"])) {
                    render("account.php", ["error" => "Niet alles ingevuld", "title" => "nieuw wachtwoord"]);
                    exit(1);
                }
                if ($_POST["password1"] != $_POST["password2"]) {
                    render("account.php", ["error" => "Wachtwoorden komen niet overeen.", "title" => "nieuw wachtwoord"]);
                    exit(1);
                }

                // save password
                if (!$userModel->newPassword($_POST["username"], $_POST["password1"])) {
                    render("account.php", ["error" => "Wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter, een nummer en een speciaal teken bevatten.", "title" => "nieuw wachtwoord"]);
                    exit(1);
                }



                if (!Empty($_POST["username"]) && $userModel->validateUsername($_POST["username"]) && $_POST["password1"] == $_POST["password2"]) {
                    if ($_POST["password1"] != $_POST["pwo"]) {
                        $userModel->newPassword($_POST["username"], $_POST["password1"]);
                    }

                    render("account.php", ["error" => "", "title" => "nieuw wachtwoord","error" => "Uw wachtwoord is veranderd"]);
                    exit();
                }
                // new recovery creation
            }

            elseif(!$_SESSION["user"]->checkPassword($_POST["pwo"])) {
                render("account.php", ["error" => "", "title" => "nieuw wachtwoord","error" => "Oud password klopt niet"]);
                exit(0);
            }

        }
        render("account.php", ["error" => "", "title" => "nieuw wachtwoord","error" => ""]);

    }
}