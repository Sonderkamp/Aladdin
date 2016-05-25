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

    public
        $completedWishes,
        $incompletedWishes,
        $wishRepo,
        $talentRepository,
        $reportRepository,
        $userRepostitory,
        $title,
        $description,
        $tag,
        $isAccepted,
        $wishContentId,
        $maxContentLength = 50,
        $currentPage;

    public function __construct()
    {
        $this->wishRepo = new WishRepository();
        $this->talentRepository = new TalentRepository();
        $this->userRepostitory = new UserRepository();
        $this->reportRepository = new ReportRepository();
    }

    //

    public function run()
    {
        (new AccountController())->guaranteeLogin("/Wishes");

        if (isset($_GET["show"])) {
            switch (strtolower($_GET["show"])) {
                case "mywishes":
                    (new DashboardController())->guaranteeProfile();
                    $this->renderOverview("myWishes");
                    break;
                case "incompletedwishes":
                    (new DashboardController())->guaranteeProfile();
                    $this->renderOverview("incompletedWishes");
                    break;
                case "completedwishes":
                    (new DashboardController())->guaranteeProfile();
                    $this->renderOverview("completedWishes");
                    break;
                case "open_edit_wish":
                    $this->openWishView(false);
                    break;
                case "open_wish":
                    $this->openWishView(true);
                    break;
            }

        }

        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                //remove refrences to match show=openeditwish
                case "open_edit_wish":
                    $this->openWishView(false);
                    exit(0);
                    break;
                case "open_wish":
                    $this->openWishView(true);
                    exit(0);
                    break;
                case "addwish":
                    $this->add_wish();
                    break;
                case "editwish":
                    $this->edit_wish();
                    break;
                case "remove":
                    $this->remove();
                    break;
                case "go_back":
                    $this->go_back();
                    break;
                case "report":
                    break;
                case "back":
                    $this->back();
                    break;
                default:
                    $this->apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else if (isset($_GET["wish_id"])) {
//            guaranteeProfile();
            if (isset($_POST["page"])) {
                $this->getSpecificWish($_GET["wish_id"], $_POST["page"]);
            } else {
                $this->getSpecificWish($_GET["wish_id"], null);
            }
        }

        //werkt nog niet todat de hosting gefixt is
        if (isset($_GET["search"])) {
            $this->searchWish($_GET["search"]);
        } else if (isset($_POST["match/wish_id"])) {

            (new DashboardController())->guaranteeProfile();
            $this->requestMatch($_POST["match/wish_id"]);
        } else {
            (new DashboardController())->guaranteeProfile();
            $this->renderOverview("myWishes");
        }
    }

    private function renderOverview($currentPage)
    {
        $myWishes = $this->wishRepo->getMyWishes();
        $completedWishes = $this->wishRepo->getCompletedWishes();
        $myCompletedWishes = $this->wishRepo->getMyCompletedWishes();
        $incompletedWishes = $this->wishRepo->getIncompletedWishes();
        $matchedWishes = $this->wishRepo->getMatchedWishes();

        $canAddWish = $this->wishRepo->canAddWish($_SESSION["user"]->email);
        $this->setCurrent($currentPage);

        $report = $this->reportRepository->getReportedUsers();
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

            //Might be deprecated
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


    private function openWishView($open)
    {
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepo->canAddWish($_SESSION["user"]->email);
            if (!$canAddWish) {
                $this->go_back();
                exit(1);
            }

            $this->render("addWish.tpl", ["title" => "Wens toevoegen"]);

        } else {
            $this->wishContentId = $_GET["editwishbtn"];
            $_SESSION["wishcontentid"] = $_GET["editwishbtn"];

            $wish = $this->wishRepo->getWish($this->wishContentId);
            
            $title = $wish->title;
            $description = $wish->content;
            $tempTag = $this->talentRepository->getWishTalents($wish);
            
            $returnArray = array();
            foreach ($tempTag as $item){
                if($item instanceof Talent){
                    $returnArray[] = $item->name;
                }
            }

            $tag = $this->prepend("#", implode(" #", $returnArray));

            $this->render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "edit" => "isset", "tag" => $tag, "previousPage"]);
        }
    }

    function prepend($string, $chunk)
    {
        if (!empty($chunk) && isset($chunk)) {
            return $string . $chunk;
        } else {
            return $string;
        }
    }


    private function add_wish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // check if user can add a wish
            if (!($this->wishRepo->canAddWish($_SESSION["user"]->email))) {
                $this->render("addWish.tpl", ["wishError" => "U heeft al 3 wensen, u kunt geen wensen meer toevoegen."]);
                exit(1);
            }

            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $input = array([$title, $description, $tag]);
            $size = strlen($this->gethashtags($tag));

            if (!$this->isValid($input) || $size == 0) {
                $this->renderEdit($title, $description, $tag);
            }

            $myWishes = $this->wishRepo->getMyWishes();
            if ($this->hasSameWish($myWishes, $title)) {
                $this->renderEdit($title, $description, $tag, "U heeft al een wens met een soortgelijke titel",true);
            }

            $myTags = array_map('ucfirst', explode(',', $this->gethashtags($tag)));

            // create an array with the wish
            $wish = new Wish();
            $wish->title = $title;
            $wish->content = $description;
            $wish->tags = $myTags;
            $this->wishRepo->addWish($wish);

            $this->currentPage = "mywishes";
            $this->go_back();
        }
    }

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
    }

    private function getSpecificwish($id, $previousPage)
    {
        $selectedWish = $this->wishRepo->getWish($id);
        $this->render("wishSpecificView.tpl",
            ["title" => "Wens: " . $id, "selectedWish" => $selectedWish, "previousPage" => $previousPage]);
        exit(0);
    }

    private function requestMatch($id)
    {
        $this->apologize($id);
    }


    private function edit_wish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $message = "Ongelidige tag #";
            if (strlen($this->gethashtags($tag)) == 0) {
                $this->renderEdit($title, $description, $tag, $message);

            }
            // Check if fields are filled
            if (!$this->isValid([$title, $description, $tag])) {
                $this->renderEdit($title, $description, $tag);
            }

            // set a comma , between the tags.
            $myTags = array_map('ucfirst', explode(',', $this->gethashtags($tag)));

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
            $this->go_back();
        }
    }

    public function isValid($array)
    {
        foreach ($array as $item) {
            if (empty($item)) {
                return false;
            }
        }
        return true;
    }

    public function renderEdit($title, $description, $tag, $message = null, $add = null)
    {
        $error = "vul AUB alles in!";

        if(isset($add)){
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message]);
            exit(0);
        }

        if (isset($message)) {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message, "edit" => "isset"]);
        } else {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
        }

        exit(1);
    }

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


    private function go_back()
    {
        (new DashboardController())->guaranteeProfile();
        $this->renderOverview("myWishes");
    }

    private function remove()
    {
        $id = $_GET["wishID"];
        if (isset($id)) {
            $this->wishRepo->deleteMyWish($id);
        }

        $this->currentPage = "mywishes";
        $this->go_back();
    }

    function gethashtags($text)
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

    public function back()
    {
        if (!empty($this->getCurrent())) {
            $this->redirect("/wishes/show=" . $this->getCurrent());
            exit(0);
        }

        $this->redirect("/wishes");
        exit(0);
    }

    public function setCurrent($page)
    {
        $_SESSION["current"] = $page;
    }

    public function getCurrent()
    {
        return $_SESSION["current"];
    }


}
