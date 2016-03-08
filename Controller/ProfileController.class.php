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


            $_SESSION["user"]->updateUser($arr);
            render("account.php", ["title" => "profile", "error" => ""]);


        }
    }
}