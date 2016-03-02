<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController
{

    public $wishes;

    public function __construct() {
        $this -> wishes = array();
        $this -> wishes[0] = new Wish("Max" , "Dave Grohl Ontmoeten" , "Verenigde Staten" , "Los Angeles", true);
        $this -> wishes[1] = new Wish("Marius" , "Werkende code schrijven" , "Nederland", "Den Bosch", false);
        $this -> wishes[2] = new Wish("Mevlüt" , "Iets opschrijven" , "Turkije", "Heusden" , true);
    }

    public function run()
    {
        //hier
        $result = Database::query("select * from wish");
        $var = "success";
        if($result === false || $result == null){
            $var =  "fuck jou";
        }

        $wishes = array();
        $wishes[0] = new Wish("Max" , "Dave Grohl Ontmoeten" , "Verenigde Staten" , "Los Angeles", true);
        $wishes[1] = new Wish("Marius" , "Werkende code schrijven" , "Nederland", "Den Bosch", false);
        $wishes[2] = new Wish("Mevlüt" , "Iets opschrijven" , "Turkije", "Heusden" , true);

        render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $this -> wishes]);
    }

    public function getIncompleteWishes(){
        $newWishes = array();

        for($i = 0; $i < count($this->wishes); $i++){
            if(!$this->wishes[i] -> completed){
                $newWishes[i] = $this -> wishes[i];
            }
        }

        render("wishOverview.php", ["title" => "Onvervulde wensen overzicht", "wishes" => $newWishes]);

    }
}