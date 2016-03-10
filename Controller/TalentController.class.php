<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $page, $all_talents, $current_all_talents_number, $all_talents_number, $talents, $talents_user, $talent_repository, $talent_numbers, $current_talent_number, $user_talents_number, $current_user_talent_number, $talent_name, $talent_error, $requested_talents, $requested_talents_number, $current_requested_talent_number;

    public function __construct()
    {
        guaranteeLogin("/Talents");

        $this->page = "m";
        $this->talent_repository = new TalentRepository();

        $this->all_talents_number = ceil($this->talent_repository->checkNumberOfAllTalents()/10);
        $this->user_talents_number = ceil($this->talent_repository->checkNumberOfTalentsFromUser()/10);
        $this->talent_numbers = ceil($this->talent_repository->checkNumberOfTalents()/10);
        $this->requested_talents_number = ceil($this->talent_repository->checkNumberOfRequestedTalents()/10);
    }

    public function run()
    {
//        $this->checkPost();
//        $this->checkGet();
//        $this->checkSessions();

        $this->checkAdminPost();
        $this->checkAdminGet();
        $this->checkAdminSession();

        render("Admin/talent.php",
            ["title" => "Talenten",
            "all_talents" => $this->all_talents,
            "all_talent_number" => $this->all_talents_number,
            "current_all_talents_number" => $this->current_all_talents_number,
            "requested_talents" => $this->requested_talents]);

//        render("talentOverview.php",
//            ["title" => "Talenten",
//                "talents" => $this->talents,
//                "user_talents" => $this->talents_user,
//                "number_of_talents" => $this->talent_repository->checkNumberOfTalentsFromUser(),
//                "talent_error" => "set",
//                "user_talents_number" => $this->user_talents_number,
//                "current_user_talent_number" => $this->current_user_talent_number,
//                "talent_number" => $this->talent_numbers,
//                "current_talent_number" => $this->current_talent_number,
//                "current_page" => $this->page,
//                "talent_name" => $this->talent_name,
//                "added_talent_error" => $this->talent_error,
//                "requested_talents" => $this->requested_talents,
//                "requested_talents_number" => $this->requested_talents_number,
//                "current_requested_talent_number" => $this->current_requested_talent_number]);
        exit(0);
    }

    private function checkPost()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_POST["talent_name"])) {
                if(strlen($_POST["talent_name"]) > 0 && strlen($_POST["talent_name"]) <= 45){
                    $correct = true;
                    foreach($this->talent_repository->getAllTalentsName() as $name_of_talent){
                        if(strtolower($name_of_talent) == strtolower($_POST["talent_name"])){
                            $_SESSION["talent_name"] = $_POST["talent_name"];
                            $_SESSION["err_talent"] = "De ingevoegde naam is al toegevoegd, aangevraagd of geweigerd.";
                            $correct = false;
                            break;
                        }
                    }
                    if($correct == true){
                        $this->talent_repository->addTalent($_POST["talent_name"]);
                        $_SESSION["talent_name"] = "";
                        $_SESSION["err_talent"] = "";
                    }
                }
                else{
                    $_SESSION["talent_name"] = $_POST["talent_name"];
                    $_SESSION["err_talent"] = "Het tekstbox moet minimaal 1 en maximaal 45 characters bevatten!";
                }

                $_SESSION["current_talent_page"] = "t";

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }

            if (!Empty($_POST["remove_id"])) {
                $this->talent_repository->deleteTalentFromUser($_POST["remove_id"]);

                $_SESSION["current_talent_page"] = "m";

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }
            else if (!Empty($_POST["add_id"])) {
                $this->talent_repository->addTalentToUser($_POST["add_id"]);

                $_SESSION["current_talent_page"] = "a";

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
    }

    private function checkGet()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!Empty($_GET["p"])) {
                if($_GET["p"] == "m"){
                    $this->page = $_GET["p"];
                    $_SESSION["current_talent_page"] = $this->page;
                }
                elseif($_GET["p"] == "a"){
                    $this->page = $_GET["p"];
                    $_SESSION["current_talent_page"] = $this->page;
                }
                elseif($_GET["p"] == "t"){
                    $this->page = $_GET["p"];
                    $_SESSION["current_talent_page"] = $this->page;
                }
                else{
                    $this->page = "m";
                    $_SESSION["current_talent_page"] = $this->page;
                }
            }

            if (!Empty($_GET["m"])) {
                if($_GET["m"] > 0 & $_GET["m"] <= $this->user_talents_number) {
                    $this->talents_user = $this->talent_repository->getSelectionUserTalents($_GET["m"]);
                    $this->current_user_talent_number = $_GET["m"];
                    $_SESSION["talent_m"] = $this->current_user_talent_number;
                }
                else{
                    $this->talents_user = $this->talent_repository->getSelectionUserTalents(1);
                    $this->current_user_talent_number = 1;
                    $_SESSION["talent_m"] = $this->current_user_talent_number;
                }
            }
            else {
                $this->talents_user = $this->talent_repository->getSelectionUserTalents(1);
                $this->current_user_talent_number = 1;
            }

            if (!Empty($_GET["a"])) {
                if($_GET["a"] > 0 & $_GET["a"] <= $this->talent_numbers) {
                    $this->talents = $this->talent_repository->getSelectionTalents($_GET["a"]);
                    $this->current_talent_number = $_GET["a"];
                    $_SESSION["talent_a"] = $this->current_talent_number;
                }
                else{
                    $this->talents = $this->talent_repository->getSelectionTalents(1);
                    $this->current_talent_number = 1;
                    $_SESSION["talent_a"] = $this->current_talent_number;
                }
            }
            else {
                $this->talents = $this->talent_repository->getSelectionTalents(1);
                $this->current_talent_number = 1;
            }

            if (!Empty($_GET["t"])) {
                if($_GET["t"] > 0 & $_GET["t"] <= $this->requested_talents_number) {
                    $this->requested_talents = $this->talent_repository->getRequestedTalents($_GET["t"]);
                    $this->current_requested_talent_number = $_GET["t"];
                    $_SESSION["talent_t"] = $this->current_requested_talent_number;
                }
                else{
                    $this->requested_talents = $this->talent_repository->getRequestedTalents(1);
                    $this->current_requested_talent_number = 1;
                    $_SESSION["talent_t"] = $this->current_requested_talent_number;
                }
            }
            else {
                $this->requested_talents = $this->talent_repository->getRequestedTalents(1);
                $this->current_requested_talent_number = 1;
            }
        }
    }

    private function checkSessions(){
        if(!Empty($_SESSION["current_talent_page"])){
            $this->page = $_SESSION["current_talent_page"];
        }
        if(!Empty($_SESSION["talent_m"])){
            if($this->user_talents_number > 1){
                $this->current_user_talent_number = $_SESSION["talent_m"];
                $this->talents_user = $this->talent_repository->getSelectionUserTalents($_SESSION["talent_m"]);
            }
            else{
                $_SESSION["talent_m"] = $this->user_talents_number;
            }
        }
        if(!Empty($_SESSION["talent_a"])){
            if($this->talent_numbers > 1){
                $this->current_talent_number = $_SESSION["talent_a"];
                $this->talents = $this->talent_repository->getSelectionTalents($_SESSION["talent_a"]);
            }
            else{
                $_SESSION["talent_a"] = $this->talent_numbers;
            }
        }
        if(!Empty($_SESSION["talent_t"])){
            if($this->requested_talents_number > 1){
                $this->current_requested_talent_number = $_SESSION["talent_t"];
                $this->requested_talents = $this->talent_repository->getRequestedTalents($_SESSION["talent_t"]);
            }
            else{
                $_SESSION["talent_t"] = $this->requested_talents_number;
            }
        }
        if(!Empty($_SESSION["talent_name"])){
            $this->talent_name = $_SESSION["talent_name"];
        }
        if(!Empty($_SESSION["err_talent"])){
            $this->talent_error = $_SESSION["err_talent"];
        }
    }

    public function runAdmin()
    {
        render("Admin/talent.php",
            ["title" => "Talenten"]);
        exit(0);
    }

    private function checkAdminSession()
    {
        if(!Empty($_SESSION["talent_admin"])){
            if($this->all_talents_number > 1){
                $this->current_all_talents_number = $_SESSION["talent_admin"];
                $this->all_talents = $this->talent_repository->getAllTalents($_SESSION["talent_admin"]);
            }
            else{
                $_SESSION["talent_admin"] = $this->all_talents_number;
            }
        }
    }

    private function checkAdminGet()
    {
        if (!Empty($_GET["admin_a"])) {
            if($_GET["admin_a"] > 0 & $_GET["admin_a"] <= $this->all_talents_number) {
                $this->all_talents = $this->talent_repository->getAllTalents($_GET["admin_a"]);
                $this->current_all_talents_number = $_GET["admin_a"];
                $_SESSION["talent_admin"] = $this->current_all_talents_number;
            }
            else{
                $this->all_talents = $this->talent_repository->getAllTalents(1);
                $this->current_all_talents_number = 1;
                $_SESSION["talent_admin"] = $this->current_all_talents_number;
            }
        }
        else {
            $this->all_talents = $this->talent_repository->getAllTalents(1);
            $this->current_all_talents_number = 1;
        }
    }

    private function checkAdminPost()
    {
        if (!Empty($_POST["admin_talent_name"]) && !Empty($_POST["admin_talent_id"])) {

            if(strlen($_POST["admin_talent_name"]) > 0 && strlen($_POST["admin_talent_name"]) <= 45){
                $correct = true;
                foreach($this->talent_repository->getAllTalentsName() as $name_of_talent){
                    if(strtolower($name_of_talent) == strtolower($_POST["admin_talent_name"])){
                        $_SESSION["talent_name"] = $_POST["admin_talent_name"];
                        $_SESSION["err_talent"] = "De ingevoegde naam is al toegevoegd, aangevraagd of geweigerd.";
                        $correct = false;
                        break;
                    }
                }
                if($correct == true){
                    $this->talent_repository->updateTalent($_POST["admin_talent_name"],$_POST["admin_talent_id"]);
                    $_SESSION["admin_talent_name"] = "";
                    $_SESSION["err_talent"] = "";
                }
            }
            else {

            }

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }
    }
}