<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $message_model, $page, $all_talents, $unaccepted_talents,$current_all_talents_number, $all_talents_number, $talents, $talents_user, $talent_repository, $talent_numbers, $current_talent_number, $user_talents_number, $current_user_talent_number, $talent_name, $talent_error, $requested_talents, $requested_talents_number, $current_requested_talent_number;

    public function __construct()
    {
        guaranteeLogin("/Talents");

        $this->page = "m";
        $this->talent_repository = new TalentRepository();
        $this->message_model = new MessageModel();

        $this->all_talents_number = ceil($this->talent_repository->checkNumberOfAllTalents()/10);
        $this->user_talents_number = ceil($this->talent_repository->checkNumberOfTalentsFromUser()/10);
        $this->talent_numbers = ceil($this->talent_repository->checkNumberOfTalents()/10);
        $this->requested_talents_number = ceil($this->talent_repository->checkNumberOfRequestedTalents()/10);
        $this->unaccepted_talents = $this->talent_repository->getAllRequestedTalents();
    }

    public function run()
    {
        // if admin
        // else deze
        $this->runAdmin();
//        $this->checkPost();
//        $this->checkGet();
//        $this->checkSessions();
//
//        render("talentOverview.tpl",
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
                        if(!preg_match('/[^a-z\s]/i', $_POST["talent_name"])) {
                            $this->talent_repository->addTalent($_POST["talent_name"]);
                            $_SESSION["talent_name"] = "";
                            $_SESSION["err_talent"] = "";
                        }
                        else{
                            $_SESSION["talent_name"] = $_POST["talent_name"];
                            $_SESSION["err_talent"] = "Er mogen alleen letters en spaties worden gebruikt in het talent!";
                        }
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
        $this->checkAdminPost();
        $this->checkAdminGet();
        $this->checkAdminSession();

        render("Admin/talent.tpl",
            ["title" => "Talenten",
            "all_talents" => $this->all_talents,
            "all_talent_number" => $this->all_talents_number,
            "current_all_talents_number" => $this->current_all_talents_number,
            "unaccepted_talents" => $this->unaccepted_talents]);
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
                        //De ingevoegde naam is al toegevoegd, aangevraagd of geweigerd.
                        $correct = false;
                        break;
                    }
                }
                if($correct == true){
                    if(!preg_match('/[^a-z\s]/i', $_POST["admin_talent_name"])) {
                        $this->talent_repository->updateTalentName($_POST["admin_talent_name"], $_POST["admin_talent_id"]);
                    }
                    else{
                        //Er mogen alleen letters en spaties worden gebruikt in het talent!
                    }
                }
            }

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }

        if(!Empty($_POST["deny_message"]) && !Empty($_POST["deny_id"])){
            $this->talent_repository->rejectTalent($_POST["deny_id"]);

            $talent = $this->talent_repository->getTalentById($_POST["deny_id"]);

            $message_id = $this->message_model->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is afgewezen", $_POST["deny_message"]);
            $this->message_model->setLink("", "Talent", $message_id);

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }

        if(!Empty($_POST["accept_id"])){
            $this->talent_repository->acceptTalent($_POST["accept_id"]);

            $talent = $this->talent_repository->getTalentById($_POST["accept_id"]);
            $this->talent_repository->addTalentToUser2($_POST["accept_id"],$talent->user_email);

            $message_id = $this->message_model->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is geaccepteerd", "Het talent '" . $talent->name . "' is geaccepteerd, omdat het voldoet aan de algemene voorwaarden. Het talent is toegevoegt aan 'mijn talenten'.");
            $this->message_model->setLink("", "Talent", $message_id);

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }
    }
}