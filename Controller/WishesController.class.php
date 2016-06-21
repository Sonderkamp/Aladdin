<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishesController extends Controller
{
    public $wishRepo, $talentRepo, $reportRepo, $userRepo, $matchRepo, $forbiddenWordRepo, $wishCreationController, $maxContentLength = 50;

    public function __construct()
    {
        $this->wishRepo = new WishRepository();
        $this->talentRepo = new TalentRepository();
        $this->userRepo = new UserRepository();
        $this->reportRepo = new ReportRepository();
        $this->matchRepo = new MatchRepository();
        $this->forbiddenWordRepo = new ForbiddenWordRepository();
        $this->wishCreationController = new WishCreationController($this);
    }

    //

    public function run()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        (new DashboardController())->guaranteeProfile();
        $this->renderOverview("myWishes");
    }

    private function renderOverview($currentPage)
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        (new DashboardController())->guaranteeProfile();

        $myWishes = $this->wishRepo->getMyWishes();
        $completedWishes = $this->wishRepo->getCompletedWishes();
        $myCompletedWishes = $this->wishRepo->getMyCompletedWishes();
        $incompletedWishes = $this->wishRepo->getIncompletedWishes();
        $matchedWishes = $this->wishRepo->getPossibleMatches();


        $canAddWish = $this->wishRepo->canAddWish($this->userRepo->getCurrentUser()->email);
        $displayNames = array();

        $this->render("wishOverview.tpl", ["title" => "Wensen Overzicht",
            "myWishes" => $myWishes,
            "completedWishes" => $completedWishes,
            "myCompletedWishes" => $myCompletedWishes,
            "incompletedWishes" => $incompletedWishes,
            "matchedWishes" => $matchedWishes,
            "currentPage" => $currentPage,
            "canAddWish" => $canAddWish
        ]);

        exit(0);
    }

    private function searchWish($key)
    {
        //Werkt als de sql versie geupdate wordt.
        $searchReturn = $this->wishRepo->searchMyWishes($key);
        $this->render("wishOverview.tpl", ["title" => "Wensen overzicht", "wishes" => $searchReturn]);
    }

    //used to shorten string if need be
    private function checkWishContent($string)
    {
        if (strlen($string) > $this->maxContentLength) {
            $returnString = substr($string, 0, $this->maxContentLength);
            $returnString = $returnString . '...';
            return $returnString;
        }
        return $string;
    }


    //Add en Edit wish

    /**
     * Receives call from view and calls right method for Edit
     */
    public function openEditView()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        (new DashboardController())->guaranteeProfile();
        $this->wishCreationController->openWishView(false);
    }

    /**
     * Receives call from view and calls right method for Add
     */
    public function openAddView()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->wishCreationController->openWishView(true);
    }

    public function addWish(){
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->wishCreationController->addWish();
    }

    public function editWish(){
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->wishCreationController->editWish();
    }

    //Specific Wish methods

    /**
     * @param null $id
     * @param null $error
     *
     * gets all attributes from the selected wish and all corresponding matches and comments
     *
     */
    public function getSpecificWish($id = null, $error = null)
    {
        $errorString = null;

        if ($id = null && empty($_GET["Id"])) {
            $this->apologize("Please provide a valid id");
            exit(0);
        } else if (!empty($_GET["Id"])) {
            $id = $_GET["Id"];
        }

        $returnPage = null;
        $selectedWish = $this->wishRepo->getWish($id);
        $matches = $this->matchRepo->getMatches($id);
        $comments = $this->wishRepo->getComments($id);
        $canMatch = false;
        $isMatched = false;
        $canComment = false;

        if(!empty($_SESSION["error"])){
            $errorString = $_SESSION["error"];
            unset($_SESSION["error"]);
        }

        if (!empty($selectedWish)) {
            if (!empty($_GET["admin"])) {
                (new AdminController())->guaranteeAdmin("/");
                $returnPage = "/AdminWish";

                $this->renderAlone("wishSpecificView.tpl",
                    ["title" => "Wens: " . $id,
                        "selectedWish" => $selectedWish,
                        "matches" => $matches,
                        "comments" => $comments,
                        "adminView" => true,
                        "errorString" => $errorString,
                        "canMatch" => false,
                        "canComment" => false,
                        "currentUser" => $this->userRepo->getCurrentUser()]);
                exit(0);

            } else if ($this->userRepo->getCurrentUser() === false || ($selectedWish->status == "Aangemaakt" && $selectedWish->user->email != $this->userRepo->getCurrentUser()->email)) {
                $this->apologize("U bent niet gemachtigd om deze wens te bekijken.");
            }

        } else {
            $this->apologize("De wens die u heeft proberen te bezoeken bestaat niet.");
            exit(0);
        }

        if ($selectedWish->status == "Aangemaakt" || $selectedWish->status == "Gepubliceerd") {
            $canMatch = true;
        }

        if ($this->userRepo->getCurrentUser()->email == $selectedWish->user->email) {
            $canMatch = false;
        }


        if (!empty($this->userRepo->getCurrentUser())) {
            if ($matches !== false) {
                foreach ($matches as $match) {
                    if ($match->user->email == $this->userRepo->getCurrentUser()->email
                        || $selectedWish->user->email == $this->userRepo->getCurrentUser()->email
                    ) {
                        if ($match->isActive == 1) {
                            $isMatched = true;
                        }

                        if ($match->isSelected == 1) {
                            $canComment = true;
                        }
                    }
                }
            }

        }

        if ($selectedWish->status != "Vervuld") {
            $canComment = false;
        }

        $this->render("wishSpecificView.tpl",
            ["title" => "Wens: " . $id,
                "selectedWish" => $selectedWish,
                "matches" => $matches,
                "returnPage" => $returnPage,
                "adminView" => false,
                "comments" => $comments,
                "canMatch" => $canMatch,
                "errorString" => $errorString,
                "isMatched" => $isMatched,
                "canComment" => $canComment,
                "currentUser" => $this->userRepo->getCurrentUser()]);
        exit(0);
    }

    public function setCompletionDate(){
        if(!empty($_POST["completionDate"]) && !empty($_POST["Id"])){
            if(strtotime($_POST["completionDate"]) > time()){
                $this->wishRepo->setCompletionDate($_POST["completionDate"] , $_POST["Id"]);
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            } else {
                $errorString = "Geef alsjeblieft een geldige datum op. Een geldige datum is minimaal 1 dag vanaf de dag van vandaag.";
                $_SESSION["error"] = $errorString;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            }
        } else {
            $errorString = "Geef alsjeblieft een geldige datum en wens id op";
            $_SESSION["error"] = $errorString;
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
        }
    }

    public function confirmCompletion(){
        if(!empty($_POST["completionDate"]) && !empty($_POST["Id"])){
            if(strtotime($_POST["completionDate"]) < time()){
                $this->wishRepo->confirmCompletionDate($_POST["Id"]);
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            } else {
                $errorString = "De geplande datum is nog neit bereikt. De wens kan niet worden afgesloten.";
                $_SESSION["error"] = $errorString;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            }
        } else {
            $errorString = "Er is iets fout gegaan bij het ophalen van de datum porbeer later opnieuw";
            $_SESSION["error"] = $errorString;
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
        }
    }

    public function editComment()
    {
        if (!empty($_POST["wishId"]) && !empty($_POST["creationDate"]) && !empty($_POST["username"])) {
            (new AdminController())->guaranteeAdmin("/wishes/action=getSpecificWish?Id=" . $_POST["wishId"]);

            if (!empty($_POST["removeButton"]) && $_POST["removeButton"] == "remove") {
                $this->wishRepo->removeComment($_POST["creationDate"], $_POST["username"], $_POST["wishId"]);
            } elseif (!empty($_POST["addGuestbook"]) && $_POST["addGuestbook"] == "add") {
                $this->wishRepo->addToGuestbook($_POST["creationDate"], $_POST["username"], $_POST["wishId"]);
            }
            $this->redirect("/wishes/action=getSpecificWish/admin=true/Id=" . $_POST["wishId"]);
        } else {
            $errorString = "Geef alsjeblieft een geldige wish id en mutatiedatum op";
            $_SESSION["error"] = $errorString;
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
        }
    }

    //Comment Panel for specific wish view

    /**
     *
     */
    public function AddComment()
    {
        if (!isset($_POST["comment"])) {
            $this->redirect("/Wishes/action=getSpecificWish/Id=" . $_GET["Id"]);
            exit();
        }

        if (!empty($wish = $this->wishRepo->getWish($_GET["Id"]))) {
            if ($wish->status != "Vervuld" && $this->userRepo->getCurrentUser()->email != $wish->user || !$this->wishRepo->canComment($_GET["Id"], $this->userRepo->getCurrentUser()->email)) {
                $this->redirect("/Wishes/action=getSpecificWish/Id=" . $_GET["Id"]);
                exit();
            }
        }

        if (empty($_FILES["img"]["tmp_name"])) {
            $check = false;
        } else {
            $check = getimagesize($_FILES["img"]["tmp_name"]);
        }

        if (!($check !== false)) {
            if (strlen(trim($_POST["comment"])) <= 1) {
                $errorString = "Vul een reactie in of stuur een plaatje in.";
                $_SESSION["error"] = $errorString;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
                exit();
            }
        }
        $user = $this->userRepo->getCurrentUser();


        // not logged in
        if ($user instanceof User) {

            if (($check !== false)) {

                $err = $this->wishRepo->addComment($_POST["comment"], $_GET["Id"], $user, $_FILES["img"]);
            } else {
                $err = $this->wishRepo->addComment($_POST["comment"], $_GET["Id"], $user);
            }
            if ($err != null) {
                $_SESSION["error"] = $err;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            }
        }

        $this->redirect("/Wishes/action=getSpecificWish/Id=" . $_GET["Id"]);
        exit();

    }

    // utility methods

    public function back()
    {
        (new DashboardController())->guaranteeProfile();
        $this->redirect("/wishes");
    }

    /**
     * Remove wish with id
     */
    public function remove()
    {
        $id = $_GET["Id"];
        if (isset($id)) {
            $this->wishRepo->deleteMyWish($id);
        }

        $this->back();
    }
}
