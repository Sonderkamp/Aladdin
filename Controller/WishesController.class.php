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

    private function renderOverview($currentPage, array $search = null)
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        (new DashboardController())->guaranteeProfile();

        $searchKey = null;

        if ($search == null) {
            $myWishes = $this->wishRepo->getMyWishes();
            $completedWishes = $this->wishRepo->getCompletedWishes();
            $myCompletedWishes = $this->wishRepo->getMyCompletedWishes();
            $incompletedWishes = $this->wishRepo->getIncompletedWishes();
            $matchedWishes = $this->wishRepo->getPossibleMatches();
        } else {
            $myWishes = $search[0];
            $completedWishes = $search[1];
            $myCompletedWishes = $search[2];
            $incompletedWishes = $search[3];
            $matchedWishes = $search[4];
            $searchKey = $search[5];
        }


        $canAddWish = $this->wishRepo->canAddWish($this->userRepo->getCurrentUser()->email);

        $this->render("wishOverview.tpl", ["title" => "Wensen Overzicht",
            "myWishes" => $myWishes,
            "completedWishes" => $completedWishes,
            "myCompletedWishes" => $myCompletedWishes,
            "incompletedWishes" => $incompletedWishes,
            "matchedWishes" => $matchedWishes,
            "searchKey" => $searchKey,
            "currentPage" => $currentPage,
            "canAddWish" => $canAddWish
        ]);

        exit(0);
    }

    public function searchWish()
    {
        if (!empty($_GET["search"])) {
            $key = $_GET["search"];

            if (preg_match("/[^a-z 0-9]/i", $key)) {
                $this->apologize("Zoeken kan alleen met alphanumerieke karakters");
                exit(0);
            }

            $key = "%" . $key . "%";

            $myWishes = $this->wishRepo->searchMyWishes($key);
            $completedWishes = $this->wishRepo->searchCompletedWishes($key);
            $myCompletedWishes = $this->wishRepo->searchMyCompletedWishes($key);
            $incompletedWishes = $this->wishRepo->searchIncopletedWishes($key);
            $possibleMatches = $this->wishRepo->searchPossibleMatches($key);

            $this->renderOverview(null, array($myWishes, $completedWishes, $myCompletedWishes, $incompletedWishes, $possibleMatches, $_GET["search"]));

        }
        $this->redirect("/wishes");
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

    public function addWish()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->wishCreationController->addWish();
    }

    public function editWish()
    {
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
        if ($id = null && empty($_GET["Id"])) {
            $this->apologize("Please provide a valid id");
            exit(0);
        } else if (!empty($_GET["Id"])) {
            $id = $_GET["Id"];
        }

        $selectedWish = $this->wishRepo->getWish($id);
        $newestWish = $this->wishRepo->getNewestWish($id);
        $matches = $this->matchRepo->getMatches($id);
        $comments = $this->wishRepo->getComments($id);

        $isMatched = false;
        $canComment = false;
        $errorString = null;

        if (!empty($_SESSION["error"])) {
            $errorString = $_SESSION["error"];
            unset($_SESSION["error"]);
        }

        if (empty($selectedWish)) {
            if (empty($newestWish)) {
                $this->apologize("De wens die u heeft proberen te bezoeken bestaat niet.");
                exit(0);
            } else {
                $selectedWish = $newestWish;
            }

        }
        $canMatch = $this->canMatch($selectedWish);

        if (!empty($this->userRepo->getCurrentUser())) {
            if ($matches !== false) {
                foreach ($matches as $match) {
                    if ($match->user->email == $this->userRepo->getCurrentUser()->email) {
                        if ($selectedWish->user->email != $this->userRepo->getCurrentUser()->email) {
                            if ($match->isActive == 1) {
                                $isMatched = true;
                            }
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
        } elseif ($selectedWish->user->email == $this->userRepo->getCurrentUser()->email) {
            $canComment = true;
        }

        $arr = array("title" => "Wens: " . $id,
            "selectedWish" => $selectedWish,
            "matches" => $matches,
            "returnPage" => null,
            "adminView" => false,
            "comments" => $comments,
            "canMatch" => $canMatch,
            "errorString" => $errorString,
            "isMatched" => $isMatched,
            "canComment" => $canComment,
            "currentUser" => $this->userRepo->getCurrentUser());

        if (!empty($_GET["admin"])) {

            $arr["selectedWish"] = $newestWish;
            $arr["adminView"] = true;
            $arr["canMatch"] = false;
            $arr["canComment"] = false;
            $arr["currentUser"] = null;

            $this->specificWishAdmin($arr);

        } else if ($this->userRepo->getCurrentUser() === false || (($selectedWish->status == "Aangemaakt"
                    || $selectedWish->status == "Geweigerd"
                    || $selectedWish->status == "Verwijderd")
                && $selectedWish->user->email != $this->userRepo->getCurrentUser()->email)
        ) {
            $this->apologize("U bent niet gemachtigd om deze wens te bekijken.");
        }

        if ($this->userRepo->getCurrentUser()->email !== $selectedWish->user->email) {
            (new DashboardController())->guaranteeProfile();
        }


        if ($this->userRepo->getCurrentUser()->email != $selectedWish->user->email) {
            $this->render("wishSpecificView.tpl", $arr);
        } else {
            $arr["selectedWish"] = $newestWish;
            $this->render("wishSpecificView.tpl", $arr);
        }
    }

    private function specificWishAdmin(array $values)
    {
        (new AdminController())->guaranteeAdmin("/");
        $this->renderAlone("wishSpecificView.tpl", $values);
        exit(0);
    }

    private function canMatch($selectedWish)
    {

        $canMatch = false;

        if ($selectedWish->status == "Aangemaakt" || $selectedWish->status == "Gepubliceerd") {
            $canMatch = true;
        }

        if ($this->userRepo->getCurrentUser()->email == $selectedWish->user->email) {
            $canMatch = false;
        }

        return $canMatch;
    }

    public function setCompletionDate()
    {
        if (!empty($_POST["completionDate"]) && !empty($_POST["Id"])) {
            if (strtotime($_POST["completionDate"]) > time()) {
                $this->wishRepo->setCompletionDate($_POST["completionDate"], $_POST["Id"]);
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

    public function confirmCompletion()
    {
        if (!empty($_POST["completionDate"]) && !empty($_POST["Id"])) {
            if (strtotime($_POST["completionDate"]) < time()) {
                $this->wishRepo->confirmCompletionDate($_POST["Id"]);
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            } else {
                $errorString = "De geplande datum is nog niet bereikt. De wens kan niet worden afgesloten.";
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

        $wish = $this->wishRepo->getWish($_GET["Id"]);

        if (empty($wish) || $wish->status != "Vervuld") {
            $this->redirect("/Wishes/action=getSpecificWish/Id=" . $_GET["Id"]);
            exit();
        }

        if ($this->userRepo->getCurrentUser()->email != $wish->user->email && !$this->wishRepo->canComment($_GET["Id"], $this->userRepo->getCurrentUser()->email)) {
            $this->redirect("/Wishes/action=getSpecificWish/Id=" . $_GET["Id"]);
            exit();
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
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
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
