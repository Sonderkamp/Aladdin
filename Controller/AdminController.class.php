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


        if (Empty($_SESSION["admin"])) {

            if (!empty($_GET["csv"])) {

                $val = new Graph();
                switch ($_GET["csv"]) {
                    case "usersMonth":
                        $val->getUsersMonth();
                        exit();
                    case "cats":
                        $val->getCats();
                        exit();
                    case
                        "age":
                        $val->getAge();
                        exit();
                    case
                    "monthly":
                        $val->getCatsMonth($_GET["month"]);
                        exit();
                }


            } else {
                render("adminHome.tpl", ["title" => "Statistiek"]);
                exit();
            }
        } else {
            // log IP van gebruiker die admin pagina probeert te openen
            apologize("Niet als admin ingelogd.");
        }

    }

}