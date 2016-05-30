<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishesController extends Controller
{

    // BREEKT MET NIEUWE STRCTUUR TODO

    public $wishRepo, $talentRepo, $reportRepo, $userRepo, $matchRepo, $maxContentLength = 50;

    public function __construct()
    {
        $this->wishRepo = new WishRepository();
        $this->talentRepo = new TalentRepository();
        $this->userRepo = new UserRepository();
        $this->reportRepo = new ReportRepository();
        $this->matchRepo = new MatchRepository();
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
        $report = $this->reportRepo->getReportedUsers();
        $displayNames = array();
        $amountReports = count($report);

        if ($amountReports !== 0) {
            foreach ($report as $item) {
                $displayNames[] = $item->getReported()->getDisplayName();
            }
        }


        $this->render("wishOverview.tpl", ["title" => "Wensen Overzicht",
            "myWishes" => $myWishes,
            "completedWishes" => $completedWishes,
            "myCompletedWishes" => $myCompletedWishes,
            "incompletedWishes" => $incompletedWishes,
            "matchedWishes" => $matchedWishes,
            "currentPage" => $currentPage,
            "canAddWish" => $canAddWish,
            "reported" => $displayNames
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
     *
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

            $input = array([$title, $description, $tag]);
            $size = strlen($this->getHashTags($tag));

            if (!$this->isValid($input) || $size == 0) {
                $this->renderEdit($title, $description, $tag);
            }

            $myWishes = $this->wishRepo->getMyWishes();
            if ($this->hasSameWish($myWishes, $title)) {
                $this->renderEdit($title, $description, $tag, "U heeft al een wens met een soortgelijke titel", true);
            }

            $myTags = array_map('ucfirst', explode(',', $this->getHashTags($tag)));

            // create an array with the wish
            $wish = new Wish();
            $wish->title = $title;
            $wish->content = $description;
            $wish->tags = $myTags;
            $this->wishRepo->addWish($wish);

            $this->back();
        }
    }

    public function editWish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $message = "Ongelidige tag #";
            if (strlen($this->getHashTags($tag)) == 0) {
                $this->renderEdit($title, $description, $tag, $message);

            }
            // Check if fields are filled
            if (!$this->isValid([$title, $description, $tag])) {
                $this->renderEdit($title, $description, $tag);
            }

            $myWishes = $this->wishRepo->getMyWishes();
            if ($this->hasSameWish($myWishes, $title)) {
                $this->renderEdit($title, $description, $tag, "U heeft al een wens met een soortgelijke titel", null, true);
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

                /* uitgecomment anders wordt je volgespamt
                $this->wishRepo->sendEditMail($wish->id, $title, $description, $myTags);
                */
            }
            $this->back();
        }
    }

    /**
     * @param $array
     * @return bool
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
     * @param $title
     * @param $description
     * @param $tag
     * @param null $message
     * @param null $add
     * @param null $edit
     */
    public function renderEdit($title, $description, $tag, $message = null, $add = null, $edit = null)
    {

        if (isset($add)) {
            $this->render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message]);
            exit(0);
        }

        if (isset($edit)) {
            $this->render("addWish.tpl", ["error" => $message, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
            exit(0);
        }

        $error = "Vul aub alles in!";

        if (isset($message)) {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message, "edit" => "isset"]);
        } else {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
        }

        exit(1);
    }

    /**
     * @param $string
     * @return string
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

    /**
     * @param $wishes
     * @param $title
     * @return bool
     *
     * ?
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
        } else {
            return false;
        }
        return false;
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

        // TODO: Error?

        if ($id = null && empty($_GET["Id"])) {
            $this->apologize("Please provide a valid id");
        } else if (!empty($_GET["Id"])) {
            $id = $_GET["Id"];
        }

        $selectedWish = $this->wishRepo->getWish($id);
        $matches = $this->matchRepo->getMatches($id);
        $comments = $this->wishRepo->getComments($id);
        $canMatch = false;

        if($selectedWish->status == "Aangemaakt" || $selectedWish->status == "Gepubliseerd" ){
            $canMatch = true;
        }

        if (!empty($selectedWish)) {
            $this->render("wishSpecificView.tpl",
                ["title" => "Wens: " . $id,
                    "selectedWish" => $selectedWish,
                    "matches" => $matches,
                    "comments" => $comments,
                    "canMatch" => $canMatch,
                    "currentUser" => $this->userRepo->getCurrentUser()]);
            exit(0);
        } else {
            $this->apologize("This wish doesn't exist");
        }
    }

    //Comment Panel for specific wish view

    /**
     *
     */
    public function AddComment()
    {

        if (!isset($_POST["comment"])) {
            $this->redirect("/Wishes/Id=" . $_GET["Id"]);
            exit();
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

        $this->redirect("/Wishes/Id=" . $_GET["Id"]);
        exit();

    }

    public function requestMatch()
    {
        if (!empty($_GET["Id"]) && !empty($this->userRepo->getCurrentUser())) {
            if (!$this->matchRepo->setMatch($_GET["Id"], $this->userRepo->getCurrentUser()->email)) {
                $this->apologize("You can't match with your own wishes");
            }
        } else {
            $this->apologize("Please supply a valid wishId and make sure to be logged in");
        }

        $this->getSpecificWish($_GET["Id"]);
    }

    public function selectMatch()
    {
//        if (!empty($_GET["user"]) && !empty($_GET["wish"])) {
//            $this->matchRepo->selectMatch($_GET["wish"], $_GET["user"]);
//        } else {
//            $this->apologize("Please supply a valid wishId and User email");
//        }

        if ($this->userRepo->getCurrentUser()->email && !empty($_GET["wish"])) {
            $this->matchRepo->selectMatch($_GET["wish"], $this->userRepo->getCurrentUser());
            $this->redirect("/wishes//wishes/action=getSpecificWish?Id=" . $_GET["wish"]);
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
     *
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
