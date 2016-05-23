<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 6-4-2016
 * Time: 14:25
 */
class AdmintalentsController extends Controller
{
    private $messageModel,
        $page,
        $allTalents,
        $currentTalentsCount,
        $talentsCount,
        $talentRepo,
        $wordsRepo,
        $synonymId;

    public function __construct()
    {
        (new AdminController())->guaranteeAdmin("admintalents");

        $this->page = "allTalents";
        $this->synonymId = "";
        
        $this->talentRepo = new TalentRepository();
        $this->wordsRepo = new ForbiddenWordRepository();
        $this->messageModel = new messageRepository();

        $this->talentsCount = ceil(count($this->talentRepo->getTalents())/10);
    }

    public function run()
    {
        $this->checkPage();
        $this->checkSynonyms();
        $this->fillAllTalents();
        $this->setSynonymId();

        $this->render("Admin/talent.tpl",
            ["title" => "Talenten beheer",
                "allTalents" => $this->allTalents,
                "talentsCount" => $this->talentsCount,
                "currentTalentsCount" => $this->currentTalentsCount,
                "unacceptedTalents" => $this->talentRepo->getAllRequestedTalents(),
                "talents" => $this->talentRepo->getTalents(),
                "synonymId" => $this->synonymId,
                "acceptedTalents" => $this->talentRepo->getAcceptedTalents(),
                "page" => $this->page]);
        exit(0);
    }

    public function acceptTalent() {

        if(!Empty($_GET["talent"])) {
            $id = htmlspecialchars($_GET["talent"]);

            if (is_numeric($id)) {

                $talent = $this->talentRepo->getTalent($id);

                if (!Empty($talent)) {
                    $talent = $talent[0];

                    if (Empty($talent->isRejected)) {
                        $this->talentRepo->updateTalent($talent->name, 1, $id);

                        $this->talentRepo->addTalentUser($id, $talent->user_email);

                        $messageId = $this->messageModel->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is geaccepteerd", "Het talent '" . $talent->name . "' is geaccepteerd, omdat het voldoet aan de algemene voorwaarden. Het talent is toegevoegt aan 'mijn talenten'.");
                        $this->messageModel->setLink("", "Talent", $messageId);
                    }
                }
            }
        }

        $this->run();
    }

    public function denyTalent() {

        if(!Empty($_GET["talent"])) {
            $id = htmlspecialchars($_GET["talent"]);

            if (isset($_GET["denyMessage"])) {
                $message = htmlspecialchars($_GET["denyMessage"]);
            } else {
                $message = "";
            }

            if(is_numeric($id)) {
                $talent = $this->talentRepo->getTalent($id);

                if(!Empty($talent)) {
                    $talent = $talent[0];

                    if (Empty($talent->isRejected)) {
                        $this->talentRepo->updateTalent($talent->name, 0, $id);

                        if(!Empty($message)) {
                            $messageId = $this->messageModel->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is afgewezen", $message);
                        } else {

                            $messageId = $this->messageModel->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is afgewezen", "Het talent '" . $talent->name . "' is afgewezen, omdat het niet voldoet aan de algemene voorwaarden.");
                        }

                        $this->messageModel->setLink("", "Talent", $messageId);
                    }
                }
            }
        }

        $this->run();
    }

    public function editTalent() {

        if(isset($_GET["talent"])) {
            $id = htmlspecialchars($_GET["talent"]);
            if(isset($talentName)) {
                $talentName = htmlspecialchars($_GET["talentName"]);
            } else {
                $talentName = "";
            }
            if (isset($_GET["accepted"])) {
                $accepted = htmlspecialchars($_GET["accepted"]);
            } else {
                $accepted = "";
            }

            $talent = $this->talentRepo->getTalent($id);
            if(!Empty($talent)) {

                $talent = $talent[0];
                if (!Empty($talent->moderator_username)) {

                    $correct = true;

                    if (Empty($talentName)) {

                        $name = $this->talentRepo->getTalent($id)[0]->name;
                    } else {

                        $name = $talentName;

                        if ($name != $talent->name) {
                            if ($this->wordsRepo->isValid($name)) {

                                if (strlen($name) > 0 && strlen($name) <= 45) {

                                    foreach ($this->talentRepo->getTalents() as $item) {

                                        if (strtolower($item->name) == strtolower($name)) {

                                            //De ingevoegde naam is al toegevoegd, aangevraagd of geweigerd.
                                            $correct = false;
                                            break;
                                        }
                                    }
                                }
                            } else {

                                // De ingevoerde naam voldoet niet aan de algemene voorwaarden en is daarom verboden.
                                $name = $talent->name;
                            }
                        }
                    }

                    if ($correct == true) {

                        if (!Empty($accepted) && $accepted == "on") {

                            $accepted = 1;
                        } else {

                            $accepted = 0;
                        }

                        if (!preg_match('/[^a-z\s]/i', $name)) {

                            $this->talentRepo->updateTalent($name, $accepted, $id);
                        } else {

                            //Er mogen alleen letters en spaties worden gebruikt in het talent!
                        }
                    }
                }
            }
        }

        $this->run();
    }

    private function setSynonymId()
    {

        if(!Empty($_SESSION["synonymId"])){
            
            $this->synonymId = $_SESSION["synonymId"];
            $_SESSION["synonymId"] = "";
        }
    }

    private function fillAllTalents()
    {
        if(!Empty($_SESSION["pageTalentAdmin"])){
            
            $this->allTalents = $this->talentRepo->getTalents($_SESSION["pageTalentAdmin"],true);
            $this->currentTalentsCount = $_SESSION["pageTalentAdmin"];
            $_SESSION["pageTalentAdmin"] = null;
        } else if (!Empty($_GET["allTalents"])) {

            $page = htmlspecialchars($_GET["allTalents"]);

            if($page > 0 & $page <= $this->talentsCount) {
                $this->allTalents = $this->talentRepo->getTalents($page,true);
                $this->currentTalentsCount = $page;
            } else{
                $this->allTalents = $this->talentRepo->getTalents(1,true);
                $this->currentTalentsCount = 1;
            }
        } else {
            $this->allTalents = $this->talentRepo->getTalents(1,true);
            $this->currentTalentsCount = 1;
        }
    }

    private function checkSynonyms()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!Empty($_POST["addButton"]) && !Empty($_POST["addSynonym"]) && !Empty($_POST["synonymId"])) {

                $id = htmlspecialchars($_POST["synonymId"]);

                foreach ($_POST["addSynonym"] as $synonym) {

                    $synonym = htmlspecialchars($synonym);

                    $this->talentRepo->addSynonym($id, $synonym);
                }

                $this->setSessions($id);
            }

            if (!Empty($_POST["removeButton"]) && !Empty($_POST["removeSynonym"]) && !Empty($_POST["synonymId"])) {

                $id = htmlspecialchars($_POST["synonymId"]);

                foreach ($_POST["removeSynonym"] as $synonym) {

                    $synonym = htmlspecialchars($synonym);

                    $this->talentRepo->deleteSynonym($id, $synonym);
                }

                $this->setSessions($id);
            }
        }
    }

    private function setSessions($id) {
        
        $_SESSION["synonymId"] = $id;

        if(!Empty($_POST["page"])) {
            $this->redirect("/admintalents/p=allTalents/allTalents=" . htmlspecialchars($_POST["page"]));
        } else {
            $this->redirect("/admintalents/p=allTalents/allTalents=1");
        }

    }

    private function checkPage() {
        if(!Empty($_GET["p"])) {

            $page = htmlspecialchars($_GET["p"]);

            if($page == "allTalents" || $page == "unacceptedTalents") {
                $this->page = htmlspecialchars($_GET["p"]);
            } else {
                $this->page = "allTalents";
            }
        }
    }
}