<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $page, $talents, $talents_user, $talent_repository, $talent_numbers, $current_talent_number, $user_talents_number, $current_user_talent_number;

    public function __construct()
    {
        guaranteeLogin("/Talents");

        $this->page = "m";
        $this->talent_repository = new TalentRepository();
        $this->talents = $this->talent_repository->getTalentsWithoutAdded();
        $this->talents_user = $this->talent_repository->getUserTalents();
        $this->user_talents_number = ceil($this->talent_repository->checkNumberOfTalentsFromUser()/10);
        $this->talent_numbers = ceil($this->talent_repository->checkNumberOfTalents()/10);
    }

    public function run()
    {
        $this->checkPost();
        $this->checkGet();
        $this->checkSessions();

//        $redirect = "/talents/p=".$this->page."/a=".$this->current_talent_number."/m=".$this->current_user_talent_number;
//        redirect($redirect);

        render("talentOverview.php",
            ["title" => "Talenten",
                "talents" => $this->talents,
                "user_talents" => $this->talents_user,
                "number_of_talents" => $this->talent_repository->checkNumberOfTalentsFromUser(),
                "talent_error" => "set",
                "user_talents_number" => $this->user_talents_number,
                "current_user_talent_number" => $this->current_user_talent_number,
                "talent_number" => $this->talent_numbers,
                "current_talent_number" => $this->current_talent_number,
                "current_page" => $this->page]);
        exit(0);
    }

    private function checkPost()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_POST["talent_name"])) {
                $this->talent_repository->addTalent($_POST["talent_name"]);

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
    }
}