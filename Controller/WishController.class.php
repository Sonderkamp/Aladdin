<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController {

    public $wishes, $completedWishes, $incompletedWishes, $wishRepository;
    public $title, $description, $tag, $city, $country, $isAccepted, $wishContentId;

    public function __construct() {
        $this->wishRepository = new WishRepository();
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
                    $this->open_wish(true);
                    break;
                case "open_edit_wish":
                    $this->open_wish(false);
                    break;
                case "addwish":
                    $this->add_wish();
                    break;
                case "editwish":
                    $this->edit_wish();
                    break;
                case "go_back";
                    $this->go_back();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->getMyWishes();
        }
    }

    private function getMyWishes() {
        $mywishes = array();

        for ($i = 0; $i < count($this->wishes); $i++) {
            if ($this->wishes[$i]->user == $_SESSION["user"]->email) { // huidige user
                $mywishes[$i] = $this->wishes[$i];
            }
        }

        render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $mywishes]);
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

    private function open_wish($open) {
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
            if (!$canAddWish) {
                $this->getMyWishes();
                exit(1);
            }

            $tags = $this->wishRepository->getAllTalents();

            render("addWish.php", ["title" => "Wens toevoegen", "allTags" => $tags]);
        } else {
            $this->wishContentId = $_GET["editwishbtn"];
            $wish = $this->wishRepository->getSelectedWish($this->wishContentId);

            $this->title = $wish[0]["Title"];
            $this->description = $wish[0]["Content"];
            $this->city = $wish[0]["City"];
            $this->country = $wish[0]["Country"];

            render("addWish.php", ["wishtitle" => $this->title,
                "description" => $this->description,
                "city" => $this->city, "country" => $this->country, "edit" => "isset"]);
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
            $this->isAccepted = false;

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
                $this->tag = $description;
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

            // TODO: set id to wishContentId
            $id = 58;
            $this->wishRepository->wishContentQuery($editWish,$id);
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
