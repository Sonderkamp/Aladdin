<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController {

    public $wishes, $completedWishes, $incompletedWishes, $wishRepository, $talentRepository, $title, $description, $tag, $isAccepted, $wishContentId;

    public function __construct() {
        $this->wishRepository = new WishRepository();
        $this->talentRepository = new TalentRepository();
        $this->wishes = $this->wishRepository->getWishes();
    }

    public function run() {
        guaranteeLogin("/Wishes");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "mywishes":
                    $this->getMyWishes();
                    break;
                case "incompletedwishes":
                    $this->getWishes(false);
                    break;
                case "completedwishes":
                    $this->getWishes(true);
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
                case "searchWish":
                    $this->searchWish($_GET["action"]);
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else if (isset($_GET["wish_id"])) {
            $this->getSpecificWish($_GET["wish_id"]);
        } else if (isset($_GET["requestMatch"])) {
            $this->requestMatch($_GET["requestMatch"]);
        } else {
            $this->getMyWishes();
        }
    }

    private function searchWish($key) {
        //TODO WERKEND MAKEN
        $searchReturn = array_search($key, $this->wishes);
        render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $searchReturn]);
    }

    private function getMyWishes() {
        $mywishes = array();

        for ($i = 0; $i < count($this->wishes); $i++) {
            if ($this->wishes[$i]->user == $_SESSION["user"]->email) { // huidige user
                $mywishes[] = $this->wishes[$i];
            }
        }

        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        if (!$canAddWish) {
            render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $mywishes, "isset" => ""]);
        } else {
            render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $mywishes]);
        }

    }

    private function getWishes($completed) {
        $this->incompletedWishes = array();
        $this->completedWishes = array();

        for ($i = 0; $i < count($this->wishes); $i++) {
            if (!$this->wishes[$i]->completed) {
                $this->completedWishes[$i] = $this->wishes[$i];
            } else {
                $this->incompletedWishes[$i] = $this->wishes[$i];
            }
        }

        if ($completed) {
            render("completedWishOverview.php",
                ["title" => "Vervulde wensen overzicht", "wishes" => $this->completedWishes]);
        } else {
            render("incompletedWishOverview.php",
                ["title" => "Onvervulde wensen overzicht", "wishes" => $this->incompletedWishes]);
        }
    }

    private function open_wish_view($open) {
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
            if (!$canAddWish) {
                $this->getMyWishes();
                exit(1);
            }

            render("addWish.php", ["title" => "Wens toevoegen"]);
        } else {
            $this->wishContentId = $_GET["editwishbtn"];
            $_SESSION["wishcontentid"] = $_GET["editwishbtn"];

            $wish = $this->wishRepository->getSelectedWish($this->wishContentId);

            $this->title = $wish[0]["Title"];
            $this->description = $wish[0]["Content"];

            $tempTag = $this->wishRepository->getWishTalent($this->wishContentId);
            $this->tag = $this->prepend("#", implode(" #", $tempTag));

            render("addWish.php", ["wishtitle" => $this->title,
                "description" => $this->description, "edit" => "isset", "tag" => $this->tag]);
        }
    }

    function prepend($string, $chunk) {
        if (!empty($chunk) && isset($chunk)) {
            return $string . $chunk;
        } else {
            return $string;
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

            // check if input of form is not null
            if (Empty($this->title)
                || Empty($this->description)
                || Empty($this->tag)
            ) {
                render("addWish.php", ["error" => "Vul AUB alles in", "wishtitle" => $this->title,
                    "description" => $this->description, "edit" => "isset"]);
                exit(1);
            }

            $allTags = $this->gethashtags($this->tag);
            $myArray = explode(',', $allTags);
            $new_array = array_map('ucfirst', $myArray);

            // create an array with the wish
            $newWish = array();
            $newWish["title"] = $this->title;
            $newWish["description"] = $this->description;
            $newWish["tag"] = $new_array;

            // send the array to the repository to add to the database
            $this->wishRepository->addWish($newWish);

            $this->go_back();
        }
    }

    private function getSpecificwish($id) {

        $selectedWish = $this->wishRepository->getWish($id);

        if ($selectedWish->user != null) {
            render("wishSpecificView.tpl", ["title" => "Wens: " . $id, "selectedWish" => $selectedWish]);
        } else {
            apologize("404 wish not found. Please wish for a better website!");
        }
    }

    private function requestMatch($id) {
        //TODO WRITE QUERY TO CREATE MATCH
    }

    private function edit_wish() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $title = $_GET["title"];
            $description = $_GET["description"];
            $tag = $_GET["tag"];

            $valid = true;
            $validTag = true;

            if (!Empty($title)) {
                $this->title = $title;
            } else
                $valid = false;
            if (!Empty($description)) {
                $this->description = $description;
            } else
                $valid = false;
            if (!Empty($tag)) {
                $this->tag = $tag;
            } else
                $valid = false;

            $tagErrorMessage = "een tag moet minimaal uit 3 tekens bestaan en beginnen met een #";
            if(!$validTag){
                render("addWish.php", ["error" => "vul AUB alles in!", "wishtitle" => $this->title,
                    "description" => $this->description, "tag" => $this->tag, "tagerror" => $tagErrorMessage, "edit" => "isset"]);
                exit(1);
            }

            if (!$valid) {
                render("addWish.php", ["error" => "vul AUB alles in!", "wishtitle" => $this->title,
                    "description" => $this->description, "tag" => $this->tag, "edit" => "isset"]);
                exit(1);
            }


            $allTags = $this->gethashtags($this->tag);
            $myArray = explode(',', $allTags);
            $new_array = array_map('ucfirst', $myArray);

            // create an array with the wish
            $editWish = array();
            $editWish["title"] = $this->title;
            $editWish["description"] = $this->description;
            $editWish["tag"] = $new_array;

            if (isset($_SESSION["wishcontentid"])) {
                $id = $_SESSION["wishcontentid"];
                $this->wishRepository->wishContentQuery($editWish, $id);
            }

            $this->go_back();
        }
    }

    private
    function go_back() {
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
