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
    public function openEditView(){
        (new AccountController())->guaranteeLogin("/Wishes");
        (new DashboardController())->guaranteeProfile();
        $this->openWishView(false);
    }

    /**
     * Receives call from view and calls right method for Add
     */
    public function openAddView(){
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
//<<<<<<< HEAD
//
//    /** check if user has a wish with the same title
//     * @param $wishes = all wishes of user
//     * @param $title = title to check
//     */
//    public function hasSameWish($wishes, $title)
//    {
//        if (count($wishes) > 0) {
//            if(count($wishes) === 1){
//                $wishes = array($wishes);
//            }
//            foreach ($wishes as $item) {
//                if ($item instanceof Wish) {
//                    similar_text($item->title, $title, $percent);
//                    if ($percent > 80) {
//                        return true;
//                    }
//                }
//            }
//        } else {
//            return false;
//        }
//        return false;
//    }
//
//    private function getSpecificwish($id, $error = null)
//    {
//        $previousPage = null;
//        if (isset($_POST["page"])) {
//            $previousPage = $_GET["wish_id"];
//        }
//
//        $selectedWish = $this->wishRepo->getWish($id);
//        $comments = $this->wishRepo->getComments($id);
//        if (!empty($selectedWish)) {
//            $this->render("wishSpecificView.tpl",
//                ["title" => "Wens: " . $id, "selectedWish" => $selectedWish, "previousPage" => $previousPage, "comments" => $comments, "wishError" => $error]);
//            exit(0);
//        } else {
//            $this->apologize("This wish doesn't exist");
//        }
//    }
//
//    private function requestMatch($id)
//    {
//        $this->apologize($id);
//    }
//
//
//    private function editWish()
//=======

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

//<<<<<<< HEAD
//    /** renders to edit page
//     * @param $title = title of the wish
//     * @param $description = content of the wish
//     * @param $tag = the tag's of the wish
//     * @param $message = to show
//     * @param $add (optional), set if users want to add a wish
//     * @param $edit (optional), set if users want to edit a wish
//=======
    /**
    //    ** renders to edit page
    //     * @param $title = title of the wish
    //     * @param $description = content of the wish
    //     * @param $tag = the tag's of the wish
    //     * @param $message = to show
    //     * @param $add (optional), set if users want to add a wish
    //     * @param $edit (optional), set if users want to edit a wish
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

//<<<<<<< HEAD
//    /** adds hashtags to a string with spaces
//     * @return string with hashtags */
//=======
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

//<<<<<<< HEAD
//
//    private function go_back()
//    {
//        (new DashboardController())->guaranteeProfile();
//        $this->renderOverview("myWishes");
//    }
//
//    /** removes a wish with id */
//    private function remove()
//    {
//        $id = $_GET["wishID"];
//        if (isset($id)) {
//            $this->wishRepo->deleteMyWish($id);
//        }
//
//        $this->currentPage = "mywishes";
//        $this->go_back();
//    }
//
//    function gethashtags($text)
//=======
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

        if($id = null && empty($_GET["Id"])){
            $this->apologize("Please provide a valid id");
        } else if(!empty($_GET["Id"])){
            $id = $_GET["Id"];
        }
        
        $selectedWish = $this->wishRepo->getWish($id);
        $matches = $this->matchRepo->getMatches($id);
        $comments = $this->wishRepo->getComments($id);

        if(!empty($selectedWish)){
            $this->render("wishSpecificView.tpl",
                ["title" => "Wens: " . $id,
                    "selectedWish" => $selectedWish,
                    "matches" => $matches,
                    "comments" => $comments]);
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

        if(empty($_FILES["img"]["tmp_name"]))
        {
            $check = false;
        }
        else
        {
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
        if(!empty($_GET["Id"]) && !empty($this->userRepo->getCurrentUser())){
            $this->matchRepo->setMatch($_GET["Id"] , $this->userRepo->getCurrentUser()->email);

        } else {
            $this->apologize("Please supply a valid wishId and make sure to be logged in");
        }

        $this->getSpecificWish($_GET["Id"]);
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
