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
        $talentSuccess,
        $searchAll,
        $searchAdded;

    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/talents");

        $this->talentRepo = new TalentRepository();
        $this->wordsRepo = new ForbiddenWordRepository();
        $this->messageRepo = new messageRepository();
        $this->userRepo = new UserRepository();

        $this->userCount = ceil(count($this->talentRepo->getAddedTalents()) / 10);
        $this->talentCount = ceil(count($this->talentRepo->getUnaddedTalents()) / 10);
        $this->requestedCount = ceil(count($this->talentRepo->getRequestedTalents()) / 10);
    }

    // Render page
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
                "currentRequestedCount" => $this->currentRequestedCount,
                "searchAdded" => $this->searchAdded,
                "searchAll" => $this->searchAll]);
        exit(0);
    }

    // Remove talent if given values are correct, otherwise set error message
    public function removeTalent()
    {
        $id = $this->checkTalentId();
        if ($id !== false) {
            foreach ($this->talentRepo->getAddedTalents() as $talent) {
                if ($talent->id == $id) {
                    $succes = true;
                    break;
                }
            }

            if (Isset($succes)) {
                $this->talentRepo->deleteTalent($id);

                $talent = $this->talentRepo->getTalent($id);
                if (!Empty($talent)) {
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

    // Add talent if values are given correctly
    public function addTalent()
    {
        $id = $this->checkTalentId();
        if ($id !== false) {
            foreach ($this->talentRepo->getAddedTalents() as $talent) {
                if ($talent->id == $id) {
                    $failed = true;
                    break;
                }
            }

            if (!Isset($failed)) {
                $this->talentRepo->addTalentUser($id);

                $talent = $this->talentRepo->getTalent($id);
                if (!Empty($talent)) {
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

    // Create talent if name is given correctly
    public function createTalent()
    {
        // Check if isset, if not set set the error message
        if (isset($_GET["talent"])) {
            // If it is set, then check if it is not empty
            // Isset is first to check if the user wanted to create a talent
            if (!Empty($_GET["talent"])) {
                $newTalent = htmlspecialchars($_GET["talent"]);

                // Check if the name is valid and not a forbidden word
                if ($this->wordsRepo->isValid($newTalent)) {

                    // Check length
                    if (strlen($newTalent) > 0 && strlen($newTalent) <= 45) {

                        $correct = true;

                        // Check if the talent is also requested by a user or by the current user.
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
                        // if everything is correct than add talent
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
                    $this->talentError = "De ingevoerde talent is verboden, omdat het niet aan de algemene voorwaarden voldoet!";
                }

                if (!Empty($this->talentError)) {
                    $this->talentName = $newTalent;
                }
            } else {
                $this->talentError = "Vul a.u.b. een waarde in!";
            }
        }

        $this->page = "createTalent";
        $this->run();
    }

    // Check if the id is given correctly
    private function checkTalentId()
    {
        if (isset($_GET["talent"])) {
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

    // Check which page and which pagination values are requested in the URL
    private function checkGet()
    {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (Empty($this->page)) {
                if (!Empty($_GET["p"])) {

                    $page = htmlentities(trim($_GET["p"]), ENT_QUOTES);
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

    // Set the pill to load on startup
    private function setPage($page)
    {

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

    // Fill talents added by the user
    private function fillMyTalents()
    {

        if (!Empty($_GET["myTalents"])) {
            $myTalents = htmlspecialchars($_GET["myTalents"]);

            if ($myTalents > 0 && $myTalents <= $this->userCount) {
                $this->talentsUser = $this->talentRepo->getAddedTalents($this->userRepo->getCurrentUser()->email, $myTalents);
                $this->currentUserCount = $myTalents;
            } else {
                $this->talentsUser = $this->talentRepo->getAddedTalents($this->userRepo->getCurrentUser()->email, 1);
                $this->currentUserCount = 1;
            }
        } else {
            $this->talentsUser = $this->talentRepo->getAddedTalents($this->userRepo->getCurrentUser()->email, 1);
            $this->currentUserCount = 1;
        }
    }

    // Fill allTalents with all the talents minus the talents which has been added by the user
    private function fillAllTalents()
    {

        if (!Empty($_GET["allTalents"])) {
            $allTalents = htmlspecialchars($_GET["allTalents"]);

            if ($allTalents > 0 && $allTalents <= $this->talentCount) {
                $this->talents = $this->talentRepo->getUnaddedTalents($this->userRepo->getCurrentUser()->email, $allTalents);
                $this->currentTalentCount = $allTalents;
            } else {
                $this->talents = $this->talentRepo->getUnaddedTalents($this->userRepo->getCurrentUser()->email, 1);
                $this->currentTalentCount = 1;
            }
        } else {
            $this->talents = $this->talentRepo->getUnaddedTalents($this->userRepo->getCurrentUser()->email, 1);
            $this->currentTalentCount = 1;
        }
    }

    // Fill requestedTalents with the requested talents BY THE USER
    private function fillRequestedTalents()
    {

        if (!Empty($_GET["createTalent"])) {
            $requestedTalents = htmlspecialchars($_GET["createTalent"]);

            if ($requestedTalents > 0 & $requestedTalents <= $this->requestedCount) {
                $this->requestedTalents = $this->talentRepo->getRequestedTalents($this->userRepo->getCurrentUser()->email, $requestedTalents);
                $this->currentRequestedCount = $requestedTalents;
            } else {
                $this->requestedTalents = $this->talentRepo->getRequestedTalents($this->userRepo->getCurrentUser()->email, 1);
                $this->currentRequestedCount = 1;
            }
        } else {
            $this->requestedTalents = $this->talentRepo->getRequestedTalents($this->userRepo->getCurrentUser()->email, 1);
            $this->currentRequestedCount = 1;
        }
    }

    // Check if the user wanted to search an added or unadded talent
    private function checkSearch()
    {
        $this->searchAdded = "";
        $this->searchAll = "";

        if (!Empty($_GET["searchAdded"])) {
            $search = htmlentities(trim($_GET["searchAdded"], ENT_QUOTES));
            $this->searchAdded = $search;

            $this->talentsUser = $this->talentRepo->searchAddedTalents($this->userRepo->getCurrentUser()->email, $search);

            $this->userCount = 0;
            $this->currentUserCount = 0;

            $this->page = "myTalents";
        } else if (!Empty($_GET["searchAll"])) {
            $search = htmlentities(trim($_GET["searchAll"], ENT_QUOTES));
            $this->searchAll = $search;

            $this->talents = $this->talentRepo->searchUnaddedTalents($this->userRepo->getCurrentUser()->email, $search);

            $this->currentTalentCount = 0;
            $this->talentCount = 0;

            $this->page = "allTalents";
        }
    }
}