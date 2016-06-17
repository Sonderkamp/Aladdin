<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishesController extends Controller
{
    public $wishRepo, $talentRepo, $reportRepo, $userRepo, $matchRepo, $forbiddenWordRepo, $maxContentLength = 50;

    public function __construct()
    {
        $this->wishRepo = new WishRepository();
        $this->talentRepo = new TalentRepository();
        $this->userRepo = new UserRepository();
        $this->reportRepo = new ReportRepository();
        $this->matchRepo = new MatchRepository();
        $this->forbiddenWordRepo = new ForbiddenWordRepository();
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
//        render("wishOverview.tpl", ["title" => "Wensen overzicht", "wishes" => $searchReturn]);
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
        $this->openWishView(false);
    }

    /**
     * Receives call from view and calls right method for Add
     */
    public function openAddView()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->openWishView(true);
    }


    /**
     * Open corresponding view based on $open param
     */
    private function openWishView($open)
    {
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepo->canAddWish($this->userRepo->getCurrentUser()->email);
            if (!$canAddWish) {
                $this->back();
                exit(1);
            }

            $this->render("addWish.tpl", ["title" => "Wens toevoegen"]);

        } else {
            $wishContentId = $_GET["Id"];
            $_SESSION["wishcontentid"] = $_GET["Id"];

            $wish = $this->wishRepo->getWish($wishContentId);

            $title = $wish->title;
            $description = $wish->content;
            $tempTag = $this->talentRepo->getWishTalents($wish);

            $returnArray = array();
            foreach ($tempTag as $item) {
                if ($item instanceof Talent) {
                    $returnArray[] = $item->name;
                }
            }

            $tag = $this->prepend("#", implode(" #", $returnArray));

            $this->render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "edit" => "isset", "tag" => $tag, "previousPage"]);
        }
    }


    /**
     * @param $string
     * @param $chunk
     * @return string
     * ?
     */
    function prepend($string, $chunk)
    {
        if (!empty($chunk) && isset($chunk)) {
            return $string . $chunk;
        } else {
            return $string;
        }
    }


    /**
     * add's wish to database
     */
    public function addWish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // check if user can add a wish
            if (!($this->wishRepo->canAddWish($this->userRepo->getCurrentUser()->email))) {
                $this->render("addWish.tpl", ["wishError" => "U heeft al 3 wensen, u kunt geen wensen meer toevoegen."]);
                exit(1);
            }

            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $this->validateWish($title, $description, $tag, $returnVal);

            if ($returnVal === 0) {
                $myTags = array_map('ucfirst', explode(',', $this->getHashTags($tag)));

                $wish = new Wish();
                $wish->title = $title;
                $wish->content = $description;
                $wish->tags = $myTags;
                $this->wishRepo->addWish($wish);

                $this->back();
            }
        }
    }

    /** edit's wish */
    public function editWish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $message = "Ongelidige tag #";
            if (strlen($this->getHashTags($tag)) == 0) {
                $this->renderWishView($title, $description, $tag, $message);
            }

            $tempContent = preg_replace('/\s+/', '', $description);
            $tempTitle = preg_replace('/\s+/', '', $title);

            $this->validateWish($tempTitle, $tempContent, $tag, $returnVal, true);

            if ($returnVal === 1) {
                $this->renderWishView($title, $description, $tag);
            } else if ($returnVal === 3) {
                $this->renderWishView($title, $description, $tag, "U heeft verboden woorden in uw wens staan!", null, true);
            }

            // set a comma , between the tags.
            $myTags = array_map('ucfirst', explode(',', $this->getHashTags($tag)));

            // create a wish
            $wish = new Wish();
            $wish->title = $title;
            $wish->content = $description;
            $wish->tags = $myTags;

            if (isset($_SESSION["wishcontentid"])) {
                $wish->id = $_SESSION["wishcontentid"];
                $this->wishRepo->editWishContent($wish);

//                /* uitgecomment anders wordt je volgespamt
//                $this->wishRepo->sendEditMail($wish->id, $title, $description, $myTags);
//                */
            }
            $this->back();
        }
    }

    /** checks if wish is valid, returns number, 0 is valid.
     * @param $title = wish title
     * @param $content = wish content
     * @param $tag = wish tag's
     * @param &$returnVal = reference variable
     * @param $edit , set if editing a wish
     * @return number
     */
    public function validateWish($title, $content, $tag, &$returnVal, $edit = null)
    {
        $input = array([$title, $content, $tag]);
        $size = strlen($this->getHashTags($tag));
        $tempContent = preg_replace('/\s+/', '', $content);
        $tempTitle = preg_replace('/\s+/', '', $title);

        $returnVal = 0;

        if (!$this->isValid($input) || (strlen($tempTitle) === 0) || strlen($tempContent) === 0 || ($size == 0)) {
            if (isset($edit)) {
                $returnVal = 1;
                return;
            }
            $this->renderWishView($title, $content, $tag, "Vul aub alles in.", true);
        }

        if (!isset($edit)) {
            $myWishes = $this->wishRepo->getMyWishes();
            if ($this->hasSameWish($myWishes, $title)) {
                $this->renderWishView($title, $content, $tag, "U heeft al een wens met een soortgelijke titel.", true);
            }
        }

        if ($this->inForbiddenWords($title, $tempContent, $tag)) {
            if (isset($edit)) {
                $returnVal = 3;
                return;
            }
            $this->renderWishView($title, $content, $tag, "U heeft verboden woorden in uw wens staan!", true);
        }
    }

    /** check if user has a wish with the same title
     * @param $wishes = all wishes of user
     * @param $title = title to check
     * @return true if title is similar for more then 80%
     */
    public function hasSameWish($wishes, $title)
    {
        if (count($wishes) > 0) {
            foreach ($wishes as $item) {
                if ($item instanceof Wish) {
                    similar_text($item->title, $title, $percent);
                    if ($percent > 80) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function inForbiddenWords($title, $content, $tag)
    {
        $forbiddenWords = $this->forbiddenWordRepo->getForbiddenWords();
        $tag = str_replace("#", "", $tag);

        foreach ($forbiddenWords as $word) {
            if (strpos($title, $word) !== FALSE) {
                return true;
            }
            if (strpos($content, $word) !== FALSE) {
                return true;
            }
            if (strpos($tag, $word) !== FALSE) {
                return true;
            }
        }
        return false;
    }


    /** check if there are empty values in an array
     * @param $array = the array to check
     * @return true if there are no empty values
     */
    public function isValid($array)
    {
        foreach ($array as $item) {
            if (empty($item)) {
                return false;
            }
        }
        return true;
    }


    /**
     * //    ** renders to edit page
     * //     * @param $title = title of the wish
     * //     * @param $description = content of the wish
     * //     * @param $tag = the tag's of the wish
     * //     * @param $message = to show
     * //     * @param $add (optional), set if users want to add a wish
     * //     * @param $edit (optional), set if users want to edit a wish
     */
    public function renderWishView($title, $description, $tag, $message = null, $add = null, $edit = null)
    {

        if (isset($add)) {
            $this->render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message]);
            exit();
        }

        if (isset($edit)) {
            $this->render("addWish.tpl", ["error" => $message, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
            exit();
        }

        $error = "Vul aub alles in!";

        if (isset($message)) {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message, "edit" => "isset"]);
        } else {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
        }

        exit();
    }


    /** adds hashtags to a string with spaces
     * @return string with hashtags
     */
    public function addHashTag($string)
    {
        if (substr($string, 0, 1) != "#") {
            $tempTag = "#";
            $tempTag .= $string;
            return $tempTag;
        } else {
            return $string;
        }
    }

    /**
     * @param $text
     * @return string
     */
    public function getHashTags($text)
    {
        //Match the hashtags
        preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $text, $matchedHashtags);
        $hashtag = '';
        // For each hashtag, strip all characters but alpha numeric
        if (!empty($matchedHashtags[0])) {
            foreach ($matchedHashtags[0] as $match) {
                $hashtag .= preg_replace("/[^a-z0-9]+/i", "", $match) . ',';
            }
        }
        //to remove last comma in a string
        return rtrim($hashtag, ',');
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

        $returnPage = null;
        $selectedWish = $this->wishRepo->getWish($id);
        $matches = $this->matchRepo->getMatches($id);
        $comments = $this->wishRepo->getComments($id);
        $canMatch = false;
        $isMatched = false;
        $canComment = false;

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
                        "canMatch" => false,
                        "canComment" => false,
                        "currentUser" => $this->userRepo->getCurrentUser()]);
                exit(0);

            } else if ($this->userRepo->getCurrentUser() === false || ($selectedWish->status == "Aangemaakt" && $selectedWish->user->email != $this->userRepo->getCurrentUser()->email)) {
                $this->apologize("You are not allowed to view this wish");
            }

        } else {
            $this->apologize("This wish doesn't exist");
            exit(0);
        }

        if ($selectedWish->status == "Aangemaakt" || $selectedWish->status == "Gepubliseerd") {
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

        // Todo: Al gematcht

        // TODO: Ontmatch knop

        $this->render("wishSpecificView.tpl",
            ["title" => "Wens: " . $id,
                "selectedWish" => $selectedWish,
                "matches" => $matches,
                "returnPage" => $returnPage,
                "adminView" => false,
                "comments" => $comments,
                "canMatch" => $canMatch,
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
                $this->apologize("Geef alsjeblieft een geldige datum op. Een geldige datum is minimaal 1 dag vanaf de dag van vandaag.");
            }
        } else {
            $this->apologize("Geef alsjeblieft een geldige datum op");
        }
    }

    public function confirmCompletion(){
        if(!empty($_POST["completionDate"]) && !empty($_POST["Id"])){
            if(strtotime($_POST["completionDate"]) < time()){
                $this->wishRepo->confirmCompletionDate($_POST["Id"]);
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
            } else {
                $this->apologize("De geplande datum is nog niet bereikt. De wens kan niet worden afgesloten");
            }
        } else {
            $this->apologize("Er is iets fout gegaan bij het ophalen van de datum probeer later opnieuw");
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
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["wishId"]);
        } else {
            $this->apologize("Geef alsjeblieft een geldige wish id en creationDate op");
        }
    }

    public function removeMatch()
    {
        if (!empty($_GET["Id"])) {
            $this->wishRepo->removeMatch($_GET["Id"]);
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
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
                $this->getSpecificwish($_GET["Id"], "Vul een reactie in of stuur een plaatje in.");
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
                $this->getSpecificwish($_GET["Id"], $err);
            }
        }

        $this->redirect("/Wishes/action=getSpecificWish/Id=" . $_GET["Id"]);
        exit();

    }

    public function requestMatch()
    {
        if (!empty($_GET["Id"]) && !empty($this->userRepo->getCurrentUser())) {

            if ($this->matchRepo->checkOwnWish($this->userRepo->getCurrentUser()->email, $_GET["Id"])) {
                $this->apologize("You can't match with your own wishes");
                exit(0);
            }

            if ($this->matchRepo->checkDuplicates($this->userRepo->getCurrentUser()->email, $_GET["Id"])) {
                $this->apologize("You already matched with this wish");
                exit(0);
            }

            $this->matchRepo->setMatch($_GET["Id"], $this->userRepo->getCurrentUser()->email);

        } else {
            $this->apologize("Please supply a valid wishId and make sure to be logged in");
        }

        $this->getSpecificWish($_GET["Id"]);
    }

    public function selectMatch()
    {
        if ($this->userRepo->getCurrentUser()->email && !empty($_POST["Id"]) && !empty($_POST["User"])) {
            $this->matchRepo->clearSelected($_POST["Id"]);
            $this->matchRepo->selectMatch($_POST["Id"], $_POST["User"]);
            $this->redirect("/wishes//wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
        } else {
            $this->apologize("Please supply a valid wishId and User email");
        }
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
