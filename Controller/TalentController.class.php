<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $talentRepo,
        $wordsRepo,
        $messageModel,
        $page,
        $talents,
        $talentsUser,
        $talentCount,
        $currentTalentCount,
        $userCount,
        $currentUserCount,
        $talentName,
        $talentError,
        $talentWarning,
        $requestedTalents,
        $requestedCount,
        $currentRequestedCount;

    public function __construct()
    {
        guaranteeLogin("/talents");

        $this->page = "m";
        $this->talentRepo = new TalentRepository();
        $this->wordsRepo = new ForbiddenWordRepository();
        $this->messageModel = new messageRepository();

        $this->userCount = ceil(count($this->talentRepo->getAddedTalents())/10);
        $this->talentCount = ceil(count($this->talentRepo->getUnaddedTalents())/10);
        $this->requestedCount = ceil(count($this->talentRepo->getRequestedTalents())/10);
    }

    public function run()
    {
        $this->checkSessions();
        $this->checkGet();
        $this->checkPost();

        render("talentOverview.tpl",
            ["title" => "Talenten",
                "talents" => $this->talents,
                "user_talents" => $this->talentsUser,
                "number_of_talents" => count($this->talentRepo->getAddedTalents()),
                "talent_error" => "set",
                "user_talents_number" => $this->userCount,
                "current_user_talent_number" => $this->currentUserCount,
                "talent_number" => $this->talentCount,
                "current_talent_number" => $this->currentTalentCount,
                "current_page" => $this->page,
                "talent_name" => $this->talentName,
                "added_talent_error" => $this->talentError,
                "added_talent_warning" => $this->talentWarning,
                "requested_talents" => $this->requestedTalents,
                "requested_talents_number" => $this->requestedCount,
                "current_requested_talent_number" => $this->currentRequestedCount]);
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

                $this->redirect();
            }

            if (!Empty($_POST["remove_id"])) {
                $this->talentRepo->deleteTalent($_POST["remove_id"]);

                $_SESSION["current_talent_page"] = "m";

                $this->redirect();
            }
            else if (!Empty($_POST["add_id"])) {
                $this->talentRepo->addTalentUser($_POST["add_id"]);

                $_SESSION["current_talent_page"] = "a";

                $this->redirect();
            }
        }
    }

    private function checkGet()
    {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!Empty($_GET["p"])) {

                $page = htmlentities(trim($_GET["p"]),ENT_QUOTES);
                $this->setPage($page);
            }

            if (!Empty($_GET["m"])) {
                if($_GET["m"] > 0 & $_GET["m"] <= $this->userCount) {
                    $this->talentsUser = $this->talentRepo->getAddedTalents($_GET["m"]);
                    $this->currentUserCount = $_GET["m"];
                    $_SESSION["talent_m"] = $this->currentUserCount;
                } else{
                    $this->talentsUser = $this->talentRepo->getAddedTalents(1);
                    $this->currentUserCount = 1;
                    $_SESSION["talent_m"] = $this->currentUserCount;
                }
            } else {
                $this->talentsUser = $this->talentRepo->getAddedTalents(1);
                $this->currentUserCount = 1;
            }

            if (!Empty($_GET["a"])) {
                if($_GET["a"] > 0 & $_GET["a"] <= $this->talentCount) {
                    $this->talents = $this->talentRepo->getUnaddedTalents($_GET["a"]);
                    $this->currentTalentCount = $_GET["a"];
                    $_SESSION["talent_a"] = $this->currentTalentCount;
                } else{
                    $this->talents = $this->talentRepo->getUnaddedTalents(1);
                    $this->currentTalentCount = 1;
                    $_SESSION["talent_a"] = $this->currentTalentCount;
                }
            } else {
                $this->talents = $this->talentRepo->getUnaddedTalents(1);
                $this->currentTalentCount = 1;
            }

            if (!Empty($_GET["t"])) {
                if($_GET["t"] > 0 & $_GET["t"] <= $this->requestedCount) {
                    $this->requestedTalents = $this->talentRepo->getRequestedTalents($_GET["t"]);
                    $this->currentRequestedCount = $_GET["t"];
                    $_SESSION["talent_t"] = $this->currentRequestedCount;
                } else{
                    $this->requestedTalents = $this->talentRepo->getRequestedTalents(1);
                    $this->currentRequestedCount = 1;
                    $_SESSION["talent_t"] = $this->currentRequestedCount;
                }
            } else {
                $this->requestedTalents = $this->talentRepo->getRequestedTalents(1);
                $this->currentRequestedCount = 1;
            }

            if (!Empty($_GET["search_added"])) {

                $search = htmlentities(trim($_GET["search_added"],ENT_QUOTES));

                $this->talentsUser = $this->talentRepo->searchAddedTalents($search);

                $this->userCount = 0;
                $this->currentUserCount = 0;

                $this->page = "m";
            } else if (!Empty($_GET["search_all"])) {

                $search = htmlentities(trim($_GET["search_all"],ENT_QUOTES));

                $this->talents = $this->talentRepo->searchUnaddedTalents($search);

                $this->currentTalentCount = 0;
                $this->talentCount = 0;

                $this->page = "a";
            }
        }
    }

    private function checkSessions(){

        if(!Empty($_SESSION["current_talent_page"])){
            $this->page = $_SESSION["current_talent_page"];
            $_SESSION["current_talent_page"] = "";
        }

        if(!Empty($_SESSION["talent_m"])){
            if($this->userCount > 1){
                $this->currentUserCount = $_SESSION["talent_m"];
                $this->talentsUser = $this->talentRepo->getAddedTalents($_SESSION["talent_m"]);
            } else{
                $_SESSION["talent_m"] = $this->userCount;
            }
        }

        if(!Empty($_SESSION["talent_a"])){
            if($this->talentCount > 1){
                $this->currentTalentCount = $_SESSION["talent_a"];
                $this->talents = $this->talentRepo->getUnaddedTalents($_SESSION["talent_a"]);
            } else{
                $_SESSION["talent_a"] = $this->talentCount;
            }
        }

        if(!Empty($_SESSION["talent_t"])){
            if($this->requestedCount > 1){
                $this->currentRequestedCount = $_SESSION["talent_t"];
                $this->requestedTalents = $this->talentRepo->getRequestedTalents($_SESSION["talent_t"]);
            } else{
                $_SESSION["talent_t"] = $this->requestedCount;
            }
        }

        if(!Empty($_SESSION["talent_name"])){
            $this->talentName = $_SESSION["talent_name"];
            $_SESSION["talent_name"] = "";
        }

        if(!Empty($_SESSION["err_talent"])){
            $this->talentError = $_SESSION["err_talent"];
            $_SESSION["err_talent"] = "";
        }

        if(!Empty($_SESSION["wrn_talent"])){
            $this->talentWarning = $_SESSION["wrn_talent"];
            $_SESSION["wrn_talent"] = "";
        }
    }

    private function addTalent($new_talent) {

        if($this->wordsRepo->isValid($new_talent)) {

            if (strlen($new_talent) > 0 && strlen($new_talent) <= 45) {

                $correct = true;

                foreach ($this->talentRepo->getTalents() as $talent) {

                    if (strtolower($talent->name) == strtolower($new_talent)) {

                        if(strtolower($talent->user_email) == strtolower($_SESSION["user"]->email)) {

                            $_SESSION["err_talent"] = "Het talent " . $new_talent . " is al door u toegevoegd of aangevraagd.";
                        } else {

                            $_SESSION["wrn_talent"] = "Het talent " . $new_talent . " is al toegevoegd, aangevraagd of geweigerd. Indien het talent is geweigerd wordt deze toegevoegd zodra het nog geaccepteerd word.";

                            $this->talentRepo->addTalentUser($talent->id, $_SESSION["user"]->email);
                        }

                        $correct = false;

                        break;
                    }
                }

                if ($correct == true) {

                    if (!preg_match('/[^a-z\s]/i', $new_talent)) {

                        $this->talentRepo->addTalent($new_talent);
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

    private function setPage($page) {

        switch ($page) {
            case "m":
            case "a":
            case "t":
                $this->page = $page;
                $_SESSION["current_talent_page"] = $page;
                break;
            default:
                $this->page = "m";
                $_SESSION["current_talent_page"] = $this->page;
        }
    }

    private function redirect() {

        // Set header
        header("HTTP/1.1 303 See Other");
        header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        // Exit with succes status
        exit(0);
    }
}