<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $message_model, $page, $talents, $talents_user, $talent_repository, $talent_numbers, $current_talent_number, $user_talents_number, $current_user_talent_number, $talent_name, $talent_error, $talent_warning, $requested_talents, $requested_talents_number, $current_requested_talent_number, $forbidden_words_repo;

    public function __construct()
    {
        guaranteeLogin("/talents");

        $this->page = "m";
        $this->talent_repository = new TalentRepository();
        $this->forbidden_words_repo = new ForbiddenWordRepository();
        $this->message_model = new MessageModel();

        $this->user_talents_number = ceil($this->talent_repository->getNumberOfTalents(true)/10);
        $this->talent_numbers = ceil($this->talent_repository->getNumberOfTalents(null,true)/10);
        $this->requested_talents_number = ceil($this->talent_repository->getNumberOfTalents(null,null,true)/10);
    }

    public function run()
    {
        $this->checkGet();
        $this->checkPost();
        $this->checkSessions();

        render("talentOverview.tpl",
            ["title" => "Talenten",
                "talents" => $this->talents,
                "user_talents" => $this->talents_user,
                "number_of_talents" => $this->talent_repository->getNumberOfTalents(true),
                "talent_error" => "set",
                "user_talents_number" => $this->user_talents_number,
                "current_user_talent_number" => $this->current_user_talent_number,
                "talent_number" => $this->talent_numbers,
                "current_talent_number" => $this->current_talent_number,
                "current_page" => $this->page,
                "talent_name" => $this->talent_name,
                "added_talent_error" => $this->talent_error,
                "added_talent_warning" => $this->talent_warning,
                "requested_talents" => $this->requested_talents,
                "requested_talents_number" => $this->requested_talents_number,
                "current_requested_talent_number" => $this->current_requested_talent_number]);
        exit(0);
    }

    private function checkPost()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (Isset($_POST["talent_name"])) {

                if (!Empty($_POST["talent_name"])) {

                    $this->addTalent($_POST["talent_name"]);
                } else {

                    $_SESSION["err_talent"] = "Vul a.u.b. een waarde in!";
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
                } elseif($_GET["p"] == "a"){
                    $this->page = $_GET["p"];
                    $_SESSION["current_talent_page"] = $this->page;
                } elseif($_GET["p"] == "t"){
                    $this->page = $_GET["p"];
                    $_SESSION["current_talent_page"] = $this->page;
                } else{
                    $this->page = "m";
                    $_SESSION["current_talent_page"] = $this->page;
                }
            }

            if (!Empty($_GET["m"])) {
                if($_GET["m"] > 0 & $_GET["m"] <= $this->user_talents_number) {
                    $this->talents_user = $this->talent_repository->getTalents($_GET["m"],null,null,null,true);
                    $this->current_user_talent_number = $_GET["m"];
                    $_SESSION["talent_m"] = $this->current_user_talent_number;
                } else{
                    $this->talents_user = $this->talent_repository->getTalents(1,null,null,null,true);
                    $this->current_user_talent_number = 1;
                    $_SESSION["talent_m"] = $this->current_user_talent_number;
                }
            } else {
                $this->talents_user = $this->talent_repository->getTalents(1,null,null,null,true);
                $this->current_user_talent_number = 1;
            }

            if (!Empty($_GET["a"])) {
                if($_GET["a"] > 0 & $_GET["a"] <= $this->talent_numbers) {
                    $this->talents = $this->talent_repository->getTalents($_GET["a"],null,true);
                    $this->current_talent_number = $_GET["a"];
                    $_SESSION["talent_a"] = $this->current_talent_number;
                } else{
                    $this->talents = $this->talent_repository->getTalents(1,null,true);
                    $this->current_talent_number = 1;
                    $_SESSION["talent_a"] = $this->current_talent_number;
                }
            } else {
                $this->talents = $this->talent_repository->getTalents(1,null,true);
                $this->current_talent_number = 1;
            }

            if (!Empty($_GET["t"])) {
                if($_GET["t"] > 0 & $_GET["t"] <= $this->requested_talents_number) {
                    $this->requested_talents = $this->talent_repository->getTalents($_GET["t"],null,null,null,null,true);
                    $this->current_requested_talent_number = $_GET["t"];
                    $_SESSION["talent_t"] = $this->current_requested_talent_number;
                } else{
                    $this->requested_talents = $this->talent_repository->getTalents(1,null,null,null,null,true);
                    $this->current_requested_talent_number = 1;
                    $_SESSION["talent_t"] = $this->current_requested_talent_number;
                }
            } else {
                $this->requested_talents = $this->talent_repository->getTalents(1,null,null,null,null,true);
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
                $this->talents_user = $this->talent_repository->getTalents($_SESSION["talent_m"],null,null,null,true);
            } else{
                $_SESSION["talent_m"] = $this->user_talents_number;
            }
        }

        if(!Empty($_SESSION["talent_a"])){
            if($this->talent_numbers > 1){
                $this->current_talent_number = $_SESSION["talent_a"];
                $this->talents = $this->talent_repository->getTalents($_SESSION["talent_a"],null,true);
            } else{
                $_SESSION["talent_a"] = $this->talent_numbers;
            }
        }

        if(!Empty($_SESSION["talent_t"])){
            if($this->requested_talents_number > 1){
                $this->current_requested_talent_number = $_SESSION["talent_t"];
                $this->requested_talents = $this->talent_repository->getTalents($_SESSION["talent_t"],null,null,null,null,true);
            } else{
                $_SESSION["talent_t"] = $this->requested_talents_number;
            }
        }

        if(!Empty($_SESSION["talent_name"])){
            $this->talent_name = $_SESSION["talent_name"];
            $_SESSION["talent_name"] = "";
        }

        if(!Empty($_SESSION["err_talent"])){
            $this->talent_error = $_SESSION["err_talent"];
            $_SESSION["err_talent"] = "";
        }

        if(!Empty($_SESSION["wrn_talent"])){
            $this->talent_warning = $_SESSION["wrn_talent"];
            $_SESSION["wrn_talent"] = "";
        }
    }

    private function addTalent($new_talent) {

        if($this->forbidden_words_repo->isValid($new_talent)) {

            if (strlen($new_talent) > 0 && strlen($new_talent) <= 45) {

                $correct = true;

                foreach ($this->talent_repository->getTalents() as $talent) {
                    if (strtolower($talent->name) == strtolower($new_talent)) {

                        $_SESSION["wrn_talent"] = "Het talent " . $new_talent . " al toegevoegd, aangevraagd of geweigerd. Het talent wordt toegevoegd zodra het nog geaccepteerd word.";

                        $this->talent_repository->addTalentToUser($talent->id);
                        $correct = false;

                        break;
                    }
                }

                if ($correct == true) {

                    if (!preg_match('/[^a-z\s]/i', $new_talent)) {

                        $this->talent_repository->addTalent($new_talent);
                    } else {

                        $_SESSION["talent_name"] = $new_talent;
                        $_SESSION["err_talent"] = "Er mogen alleen letters en spaties worden gebruikt in het talent!";
                    }
                }
            } else {

                $_SESSION["talent_name"] = $new_talent;
                $_SESSION["err_talent"] = "Het tekstbox moet minimaal 1 en maximaal 45 characters bevatten!";
            }
        } else {

            $_SESSION["err_talent"] = "De ingevoerde talent is verboden, omdat het niet aan de algemene voorwaarden voldoet!";
            $_SESSION["talent_name"] = $new_talent;
        }
    }
}