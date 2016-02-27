<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController
{
    public function run()
    {
        //hier
        $result = Database::query("select * from wish");
        $var = "success";
        if($result === false || $result == null){
            $var =  "fuck jou";
        }
        $wishes = array();
        $wishes[0] = new Wish("Max" , "Dave Grohl Ontmoeten" , "Verenigde Staten" , "Los Angeles");
        $wishes[1] = new Wish("Marius" , "Werkende code schrijven" , "Nederland", "Den Bosch");
        $wishes[2] = new Wish("Mevlüt" , "Iets opschrijven" , "Turkije", "Heusden");

        render("wishOverview.php", ["title" => "Wensen overzicht", "wishes" => $wishes]);
    }
}