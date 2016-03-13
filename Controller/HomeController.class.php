<?php
/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 12-03-16
 * Time: 11:03
 */

class HomeController {

    public function Run()
    {
        if (isset($_SESSION["user"]) && !(isset($_GET["action"]) && $_GET["action"] == "home"))
            (new WishController())->run();
        else
            render("home.php", ["title" => "Home"]);
        exit(1);
    }
}




?>