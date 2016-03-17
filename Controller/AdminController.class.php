<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 20:17
 */
class AdminController
{
    public function run()
    {

        guaranteeLogin("/admin");

        if(!Empty($_SESSION["admin"]))
        {
            render("adminHome.tpl", ["title" => "Statistiek"]);
            exit();
        }
        else
        {
            // log IP van gebruiker die admin pagina probeert te openen
            apologize("Niet als admin ingelogd.");
        }

    }

}