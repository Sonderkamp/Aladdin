<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController
{

    public $wishes, $completedWishes, $incompletedWishes, $wishRepository;

    public function __construct()
    {
        $this->wishRepository = new WishRepository();
        $this->wishes = $this->wishRepository->getWishes();
    }

    public function run()
    {
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
                    $this->open_wish();
                    break;
                case "addwish":
                    $this->add_wish();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->getMyWishes();
        }
    }

    private function getMyWishes()
    {
        $mywishes = array();

        for ($i = 0; $i < count($this->wishes); $i++) {
            if ($this->wishes[$i]->user == $_SESSION["user"]->email) { // huidige user
                $mywishes[$i] = $this->wishes[$i];
            }
        }

        render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $mywishes]);
    }

    private function getWishes($completed)
    {
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
            render("completedWishOverview.php", ["title" => "Vervulde wensen overzicht", "wishes" => $this->completedWishes]);
        } else {
            render("incompletedWishOverview.php", ["title" => "Onvervulde wensen overzicht", "wishes" => $this->incompletedWishes]);
        }
    }


    private function open_wish()
    {

        // Check if users has 3 wishes, true if wishes are [<] 3
        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);

        if (!$canAddWish) $this->getMyWishes();

        render("addWish.php", ["title" => "Wens toevoegen"]);


    }

    private function add_wish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            // boolean if user has less than 3 wishes
            $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);

            // check if user has 3 wishes
            if (!$canAddWish) {
                render("addWish.php", ["wishError" => "U heeft al 3 wensen, u kunt geen wensen meer toevoegen."]);
                exit(1);
            }

            $title = $_GET["title"];
            $description = $_GET["description"];
            $tag = $_GET["tag"];

            // check if input of form is not null
            if (Empty($title)
                || Empty($description)
                || Empty($tag)
            ) {
                render("addWish.php", ["error" => "Vul AUB alles in"]);
                exit(1);
            }

            // create an array with the wish
            $newWish = array();
            $newWish["title"] = $title;
            $newWish["description"] = $description;
            $newWish["tag"] = $tag;

            // send the array to the repository to add to the database
            $this->wishRepository->addWish($newWish);

            $this->getMyWishes();
        }
    }


}
