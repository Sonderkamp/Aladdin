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
//        $this->wishes = $this->wishRepository->getWishes();
    }

    public
    function run() {
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

        } else if (isset($_GET["wish_id"])) {
            $this->getSpecificWish($_GET["wish_id"]);
        } else if (isset($_GET["requestMatch"])) {
            $this->requestMatch($_GET["requestMatch"]);
        } else {
            if (isset($_GET["wish_id"])) {

                if (isset($_POST["page"])) {
                    $this->getSpecificWish($_GET["wish_id"], $_POST["page"]);
                } else {
                    $this->getSpecificWish($_GET["wish_id"], null);
                }

            }
            //werkt nog niet todat de hosting gefixt is
//        else if(isset($_GET["search"])){
//            $this->searchWish($_GET["search_key"]);
//        }
            else if (isset($_POST["match/wish_id"])) {
                $this->requestMatch($_POST["match/wish_id"]);
            } else {
                $this->currentPage = "mywishes";
                $this->getMyWishes();
            }
        }
    }


    private
    function searchWish($key) {
        //Werkt als de sql versie geupdate wordt.
        $searchReturn = $this->wishRepository->searchWish($key);
        render("wishOverview.tpl", ["title" => "Wensen overzicht", "wishes" => $searchReturn]);
    }


    /**
     * Gets all wishes where wish.user == current user
     */
    private
    function getMyWishes() {
        $mywishes = $this->wishRepository->getMyWishes();
        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        render("wishOverview.tpl",
            ["title" => "Wensen overzicht", "wishes" => $mywishes, "canAddWish" => $canAddWish]);
    }

    /**
     * Gets all wishes where wish.status == "vervuld"
     */
    private
    function getCompletedWishes() {
        $completedWishes = $this->wishRepository->getCompletedWishes();

        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        render("wishOverview.tpl",
            ["title" => "Vervulde wensen overzicht", "wishes" => $completedWishes, "canAddWish" => $canAddWish, "currentPage" => $this->currentPage]);
    }

    /**
     * Gets all wishes where wish.status != "vervuld"
     */
    private
    function getIncompletedWishes() {
        $incompletedWishes = $this->wishRepository->getIncompletedWishes();

        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        render("wishOverview.tpl",
            ["title" => "Vervulde wensen overzicht", "wishes" => $incompletedWishes, "canAddWish" => $canAddWish, "currentPage" => $this->currentPage]);
    }


    private
    function open_wish_view($open) {
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

    private
    function add_wish() {
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


    private
    function getSpecificwish($id, $previousPage) {

        $selectedWish = $this->wishRepository->getWish($id);

        if ($selectedWish->user != null) {
            render("wishSpecificView.tpl",
                ["title" => "Wens: " . $id, "selectedWish" => $selectedWish, "previousPage" => $previousPage]);
        } else {
            apologize("404 wish not found. Please wish for a better website!");
        }
    }


    private
    function requestMatch($id) {
        apologize($id);
    }

    private
    function edit_wish() {
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
            if (!$validTag) {
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

                $head = "Beste, \n\n";
                $msg = "Uw wensweiziging is ingedient, uw wens zal na goedkeuring zichtbaar zijn voor anderen, we houden u hiervan nog op de hoogte.\n\n";
                $wish = "Uw nieuwe wens is als volgt: \n";
                $wishName = "Naam van de wens: \t\t" . $this->title . " \n";
                $wishDescription = "Beschrijving van de wens: \t" . $this->description . "\n";
                $allTagsForMail = implode(' #', $new_array);
                $wishTags = "Uw tags zijn: \t\t\t\t#" . $allTagsForMail . "\n\n";
                $end = "Vriendelijke groeten, \n\n Alladin";

                $message = $head . $msg . $wish . $wishName . $wishDescription . $wishTags . $end;

                $mail = new Email();
                $mail->fromName = "Alladin";
                $mail->subject = "Wens is gewijzigd";
                $mail->message = $message;
                $mail->to = $_SESSION["user"]->email;
                $mail->sendMail();
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
