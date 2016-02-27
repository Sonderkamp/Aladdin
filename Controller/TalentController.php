<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    public function Run()
    {
        render("home.php", ["title" => "Talenten"]);
        exit(1);
    }
}