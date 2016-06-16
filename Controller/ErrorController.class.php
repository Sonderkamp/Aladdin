<?php

class ErrorController extends Controller
{
    public $message = "ERROR MESSGAGE!!!";

    public function run() {
        $this->render("errorView.tpl" , ["title" => "ErrorMessage",
            "message" => $this->message ]);
        exit(2);
    }

}
