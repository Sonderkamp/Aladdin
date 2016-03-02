<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController
{

    public $wishes, $completedWishes, $incompletedWishes , $wishRepository;

    public function __construct() {
        $this -> wishRepository = new WishRepository();
        $this -> wishes = $this -> wishRepository -> getWishes();
    }

    public function run()
    {
        if (isset($_GET["action"])) {
            $_SESSION["Redirect"] = null;
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
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this -> getMyWishes();
        }
    }

    private function getMyWishes(){
        //to do filter array
        render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $this -> wishes]);
    }

    private function getWishes($completed){
         $this -> incompletedWishes = array();
         $this -> completedWishes = array();

         for($i = 0; $i < count($this->wishes); $i++){
             if(!$this->wishes[$i] -> completed){
                $this ->completedWishes[$i] = $this -> wishes[$i];
             } else {
                 $this -> incompletedWishes[$i] = $this -> wishes[$i];
             }
         }

        if($completed){
            render("completedWishOverview.php", ["title" => "Vervulde wensen overzicht", "wishes" => $this->completedWishes]);
        } else {
            render("incompletedWishOverview.php", ["title" => "Onvervulde wensen overzicht", "wishes" => $this->incompletedWishes]);
        }
    }

}
