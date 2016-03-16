<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController {

    public
        $completedWishes,
        $incompletedWishes,
        $wishRepository,
        $title,
        $description,
        $tag,
        $isAccepted,
        $wishContentId,
        $currentPage;

    public function __construct() {
        $this->wishRepository = new WishRepository();
    }

    public function run() {
        guaranteeLogin("/Wishes");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "mywishes":
                    $this->currentPage = "mywishes";
                    $this->getMyWishes();
                    break;
                case "incompletedwishes":
                    $this->currentPage = "incompletedwishes";
                    $this->getIncompletedWishes();
                    break;
                case "completedwishes":
                    $this->currentPage = "completedwishes";
                    $this->getCompletedWishes();
                    break;
                case "open_wish":
                    $this->open_wish_view(true);
                    break;
                case "open_edit_wish":
                    $this->open_wish_view(false);
                    break;
                case "addwish":
                    $this->add_wish();
                    break;
                case "editwish":
                    $this->edit_wish();
                    break;
                case "go_back":
                    $this->go_back();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        }
        else if(isset($_GET["wish_id"])) {

            if(isset($_POST["page"])){
                $this->getSpecificWish($_GET["wish_id"], $_POST["page"]);
            } else {
                $this->getSpecificWish($_GET["wish_id"], null);
            }

        }
        //werkt nog niet todat de hosting gefixt is
//        else if(isset($_GET["search"])){
//            $this->searchWish($_GET["search_key"]);
//        }
        else if(isset($_POST["match/wish_id"])){
            $this->requestMatch($_POST["match/wish_id"]);
        }
        else {
            $this->currentPage = "mywishes";
            $this->getMyWishes();
        }
    }

    private function searchWish($key){
        //Werkt als de sql versie geupdate wordt.
        $searchReturn = $this->wishRepository->searchWish($key);
        render("wishOverview.tpl", ["title" => "Wensen overzicht", "wishes" => $searchReturn]);
    }


    /**
     * Gets all wishes where wish.user == current user
     */
    private function getMyWishes(){
        $mywishes = $this->wishRepository->getMyWishes();

        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        render("wishOverview.tpl", ["title" => "Wensen overzicht", "wishes" => $mywishes, "canAddWish" => $canAddWish , "currentPage" => $this->currentPage]);
    }

    /**
     * Gets all wishes where wish.status == "vervuld"
     */
    private function getCompletedWishes(){
        $completedWishes = $this->wishRepository->getCompletedWishes();

        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        render("wishOverview.tpl", ["title" => "Vervulde wensen overzicht", "wishes" => $completedWishes, "canAddWish" => $canAddWish , "currentPage" => $this->currentPage]);
    }

    /**
     * Gets all wishes where wish.status != "vervuld"
     */
    private function getIncompletedWishes(){
        $incompletedWishes = $this->wishRepository->getIncompletedWishes();

        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        render("wishOverview.tpl", ["title" => "Vervulde wensen overzicht", "wishes" => $incompletedWishes, "canAddWish" => $canAddWish , "currentPage" => $this->currentPage]);
    }


    private function open_wish_view($open) {
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
            if (!$canAddWish) {
                $this->getMyWishes();
                exit(1);
            }

            $tag = $this->wishRepository->getAllTalents();

            render("addWish.php", ["title" => "Wens toevoegen", "allTags" => $tag]);
        } else {
            $this->wishContentId = $_GET["editwishbtn"];
            $_SESSION["wishcontentid"] = $_GET["editwishbtn"];

            $wish = $this->wishRepository->getSelectedWish($this->wishContentId);

            $this->title = $wish[0]["Title"];
            $this->description = $wish[0]["Content"];
            $this->city = $wish[0]["City"];
            $this->country = $wish[0]["Country"];

            $this->tag = $this->wishRepository->getWishTalent($this->wishContentId);

            $tags = $this->wishRepository->getAllTalents();

            render("addWish.php", ["wishtitle" => $this->title,
                "description" => $this->description,
                "city" => $this->city, "country" => $this->country, "edit" => "isset", "tag" => $this->tag, "allTags" => $tags]);
        }
    }

    private function add_wish() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            // boolean if user has less than 3 wishes
            $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);

            // check if user has 3 wishes
            if (!$canAddWish) {
                render("addWish.php", ["wishError" => "U heeft al 3 wensen, u kunt geen wensen meer toevoegen."]);
                exit(1);
            }

            $this->title = $_GET["title"];
            $this->description = $_GET["description"];
            $this->tag = $_GET["tag"];
            $this->city = $_GET["city"];
            $this->country = $_GET["country"];

            $tags = $this->wishRepository->getAllTalents();
//            echo $this->gethashtags($this->tag);

            // check if input of form is not null
            if (Empty($this->title)
                || Empty($this->description)
                || Empty($this->tag)
            ) {
                render("addWish.php", ["error" => "Vul AUB alles in", "wishtitle" => $this->title,
                    "description" => $this->description,
                    "city" => $this->city, "country" => $this->country, "allTags" => $tags, "edit" => "isset"]);
                exit(1);
            }

            // create an array with the wish
            $newWish = array();
            $newWish["title"] = $this->title;
            $newWish["description"] = $this->description;
            $newWish["tag"] = $this->tag;
            $newWish["city"] = $this->city;
            $newWish["country"] = $this->country;
            $newWish["isAccepted"] = $this->isAccepted;

            // send the array to the repository to add to the database
            $this->wishRepository->addWish($newWish);

            $this->go_back();
        }
    }

    private function getSpecificwish($id , $previousPage){

        $selectedWish = $this->wishRepository->getWish($id);

        if($selectedWish->user != null) {
            render("wishSpecificView.tpl", ["title" => "Wens: " . $id, "selectedWish" => $selectedWish, "previousPage" => $previousPage]);
        } else {
            apologize("404 wish not found. Please wish for a better website!");
        }
    }

    private function requestMatch($id){
        apologize($id);
    }

    private function edit_wish() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $title = $_GET["title"];
            $description = $_GET["description"];
            $tag = $_GET["tag"];
            $city = $_GET["city"];
            $country = $_GET["country"];

            if (!Empty($title))
                $this->title = $title;
            if (!Empty($description))
                $this->description = $description;
            if (!Empty($tag))
                $this->tag = $tag;
            if (!Empty($city))
                $this->city = $city;
            if (!Empty($country))
                $this->country = $country;

            // create an array with the wish
            $editWish = array();
            $editWish["title"] = $this->title;
            $editWish["description"] = $this->description;
            $editWish["tag"] = $this->tag;
            $editWish["city"] = $this->city;
            $editWish["country"] = $this->country;

            if(isset($_SESSION["wishcontentid"])){
                $id = $_SESSION["wishcontentid"];
                $this->wishRepository->wishContentQuery($editWish, $id);
            }

            $this->go_back();
        }
    }

    private function go_back() {
        $this->getMyWishes();
    }

    function gethashtags($text) {
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


}
