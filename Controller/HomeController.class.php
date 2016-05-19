<?php


class HomeController
{

    public function run()
    {
        if (isset($_SESSION["user"]) && !(isset($_GET["action"]) && $_GET["action"] == "home"))
            (new WishesController())->run();
        else
            render("home.tpl", ["title" => "Aladdin"]);
        exit(1);
    }

}