<?php


class HomeController
{

    public function Run()
    {
        render("home.php", ["title" => "Homepagina"]);
        exit(1);
    }

}