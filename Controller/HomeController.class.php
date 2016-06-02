<?php


class HomeController extends Controller
{

    public function run()
    {

        $this->render("home.tpl", ["title" => "Aladdin"]);
        exit(0);
    }

}