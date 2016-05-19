<?php


class HomeController extends Controller
{

    public function run()
    {
        if (isset($_SESSION["user"]) && !(isset($_GET["action"]) && $_GET["action"] == "home"))
            (new WishesController())->run();
        else
            $this->render("home.tpl", ["title" => "Aladdin"]);
        exit(1);
    }

}