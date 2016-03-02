<?php


class HomeController
{

    public function Run()
    {
        if (isset($_SESSION["user"]) && !(isset($_GET["action"]) && $_GET["action"] == "home"))
            (new WishController())->run();
        else
            render("home.php", ["title" => "Aladdin"]);
        exit(1);
    }

}