<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentsController extends Controller
{
    private $talentRepo,
        $wordsRepo,
        $userRepo,
        $messageRepo,
        $page,
        $talents,
        $talentsUser,
        $talentCount,
        $currentTalentCount,
        $userCount,
        $currentUserCount,
        $talentName,
        $talentError,
        $requestedTalents,
        $requestedCount,
        $currentRequestedCount,
        $talentSuccess;

    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/talents");

        $this->talentRepo = new TalentRepository();
        $this->wordsRepo = new ForbiddenWordRepository();
        $this->messageRepo = new messageRepository();
        $this->userRepo = new UserRepository();

        $this->userCount = ceil(count($this->talentRepo->getAddedTalents())/10);
        $this->talentCount = ceil(count($this->talentRepo->getUnaddedTalents())/10);
        $this->requestedCount = ceil(count($this->talentRepo->getRequestedTalents())/10);
    }

    public function run()
    {
        $this->checkGet();

        $this->render("talentOverview.tpl",
            ["title" => "Talenten",
                "talents" => $this->talents,
                "talentsUser" => $this->talentsUser,
                "talentsNumber" => count($this->talentRepo->getAddedTalents()),
                "userCount" => $this->userCount,
                "currentUserCount" => $this->currentUserCount,
                "talentCount" => $this->talentCount,
                "currentTalentCount" => $this->currentTalentCount,
                "page" => $this->page,
                "talentName" => $this->talentName,
                "talentError" => $this->talentError,
                "talentSuccess" => $this->talentSuccess,
                "requestedTalents" => $this->requestedTalents,
                "requestedCount" => $this->requestedCount,
                "currentRequestedCount" => $this->currentRequestedCount]);
        exit(0);
    }

    public function removeTalent() {
        $id = $this->checkTalentId();
        if($id !== false) {
            foreach($this->talentRepo->getAddedTalents() as $talent) {
                if($talent->id == $id) {
                    $succes = true;
                    break;
                }
            }

            if(Isset($succes)) {
                $this->talentRepo->deleteTalent($id);

                $talent = $this->talentRepo->getTalent($id);
                if(!Empty($talent)) {
                    $talent = $talent[0];
                    
                    $this->talentSuccess = "Het talent " . $talent->name . " is succesvol verwijderd!";
                }
            } else {
                $this->talentError = "Het talent dat u probeert te verwijderen is niet door u toegevoegd!";
            }
        }

        $this->page = "myTalents";
        $this->run();
    }

    public function addTalent() {
        $id = $this->checkTalentId();
        if($id !== false) {
            foreach($this->talentRepo->getAddedTalents() as $talent) {
                if($talent->id == $id) {
                    $failed = true;
                    break;
                }
            }

            if(!Isset($failed)) {
                $this->talentRepo->addTalentUser($id);

                $talent = $this->talentRepo->getTalent($id);
                if(!Empty($talent)) {
                    $talent = $talent[0];

                    $this->talentSuccess = "Het talent " . $talent->name . " is succesvol toegevoegd!";
                }
            } else {
                $this->talentError = "Het talent dat u probeert toe te voegen is al toegevoegd!";
            }
        }

        $this->page = "allTalents";
        $this->run();
    }

    public function createTalent()
    {

        if(isset($_GET["talent"])) {
            if (!Empty($_GET["talent"])) {
                $newTalent = htmlspecialchars($_GET["talent"]);

                if ($this->wordsRepo->isValid($newTalent)) {

                    if (strlen($newTalent) > 0 && strlen($newTalent) <= 45) {

                        $correct = true;

                        foreach ($this->talentRepo->getTalents() as $talent) {

                            if (strtolower($talent->name) == strtolower($newTalent)) {

                                if (strtolower($talent->user_email) == strtolower($_SESSION["user"]->email)) {

                                    $this->talentError = "Het talent " . $newTalent . " is al door u toegevoegd of aangevraagd.";
                                } else {

                                    $this->talentSuccess = "Het talent " . $newTalent . " is al toegevoegd, aangevraagd of geweigerd. Indien het talent is geweigerd wordt deze toegevoegd zodra het nog geaccepteerd word.";

                                    $this->talentRepo->addTalentUser($talent->id, $_SESSION["user"]->email);
                                }

                                $correct = false;

                                break;
                            }
                        }

                        if ($correct == true) {

                            if (!preg_match('/[^a-z\s]/i', $newTalent)) {

                                $this->talentRepo->addTalent($newTalent);
                            } else {
                                $this->talentError = "Er mogen alleen letters en spaties worden gebruikt in het talent!";
                            }
                        }
                    } else {
                        $this->talentError = "Het tekstbox moet minimaal 1 en maximaal 45 characters bevatten!";
                    }
                } else {
                    $this->talentError  = "De ingevoerde talent is verboden, omdat het niet aan de algemene voorwaarden voldoet!";
                }

                if(!Empty($this->talentError)) {
                    $this->talentName = $newTalent;
                }
            } else {
                $this->talentError = "Vul a.u.b. een waarde in!";
            }
        }

        $this->page = "createTalent";
        $this->run();
    }

    private function checkTalentId() {

        if(isset($_GET["talent"])) {
            if (!Empty($_GET["talent"])) {

                $id = htmlspecialchars($_GET["talent"]);

                if (is_numeric($id)) {
                    return $id;
                } else {
                    $this->talentError = "De waarde van talent moet numeriek zijn!";
                    return false;
                }
            } else {
                $this->talentError = "De attribuut talent was leeg in de URL!";
                return false;
            }
        } else {
            return false;
        }
    }

    private function checkGet()
    {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (Empty($this->page)) {
                if(!Empty($_GET["p"])) {

                    $page = htmlentities(trim($_GET["p"]),ENT_QUOTES);
                    $this->setPage($page);
                } else {
                    $this->page = "myTalents";
                }
            }

            $this->fillMyTalents();
            $this->fillAllTalents();
            $this->fillRequestedTalents();

            $this->checkSearch();
        }
    }

    private function setPage($page) {

        switch ($page) {
            case "myTalents":
            case "allTalents":
            case "createTalent":
                $this->page = $page;
                break;
            default:
                $this->page = "myTalents";
        }
    }

    private function fillMyTalents() {

        if (!Empty($_GET["myTalents"])) {
            $myTalents = htmlspecialchars($_GET["myTalents"]);

            if($myTalents > 0 && $myTalents <= $this->userCount) {
                $this->talentsUser = $this->talentRepo->getAddedTalents($this->userRepo->getCurrentUser()->email, $myTalents);
                $this->currentUserCount = $myTalents;
            } else{
                $this->talentsUser = $this->talentRepo->getAddedTalents($this->userRepo->getCurrentUser()->email, 1);
                $this->currentUserCount = 1;
            }
        } else {
            $this->talentsUser = $this->talentRepo->getAddedTalents($this->userRepo->getCurrentUser()->email, 1);
            $this->currentUserCount = 1;
        }
    }

    private function fillAllTalents() {

        if (!Empty($_GET["allTalents"])) {
            $allTalents = htmlspecialchars($_GET["allTalents"]);
            
            if($allTalents > 0 && $allTalents <= $this->talentCount) {
                $this->talents = $this->talentRepo->getUnaddedTalents($this->userRepo->getCurrentUser()->email, $allTalents);
                $this->currentTalentCount = $allTalents;
            } else{
                $this->talents = $this->talentRepo->getUnaddedTalents($this->userRepo->getCurrentUser()->email, 1);
                $this->currentTalentCount = 1;
            }
        } else {
            $this->talents = $this->talentRepo->getUnaddedTalents($this->userRepo->getCurrentUser()->email, 1);
            $this->currentTalentCount = 1;
        }
    }

    private function fillRequestedTalents() {

        if (!Empty($_GET["createTalent"])) {
            $requestedTalents = htmlspecialchars($_GET["createTalent"]);

            if($requestedTalents > 0 & $requestedTalents <= $this->requestedCount) {
                $this->requestedTalents = $this->talentRepo->getRequestedTalents($this->userRepo->getCurrentUser()->email, $requestedTalents);
                $this->currentRequestedCount = $requestedTalents;
            } else{
                $this->requestedTalents = $this->talentRepo->getRequestedTalents($this->userRepo->getCurrentUser()->email, 1);
                $this->currentRequestedCount = 1;
            }
        } else {
            $this->requestedTalents = $this->talentRepo->getRequestedTalents($this->userRepo->getCurrentUser()->email, 1);
            $this->currentRequestedCount = 1;
        }
    }

    private function checkSearch() {

        if (!Empty($_GET["searchAdded"])) {

            $search = htmlentities(trim($_GET["searchAdded"], ENT_QUOTES));

            $this->talentsUser = $this->talentRepo->searchAddedTalents($this->userRepo->getCurrentUser()->email, $search);

            $this->userCount = 0;
            $this->currentUserCount = 0;

            $this->page = "myTalents";
        } else if (!Empty($_GET["searchAll"])) {

            $search = htmlentities(trim($_GET["searchAll"], ENT_QUOTES));

            $this->talents = $this->talentRepo->searchUnaddedTalents($this->userRepo->getCurrentUser()->email, $search);

            $this->currentTalentCount = 0;
            $this->talentCount = 0;

            $this->page = "allTalents";
        }
    }
}