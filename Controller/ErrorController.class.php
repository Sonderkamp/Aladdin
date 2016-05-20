<?php

class ErrorController extends Controller
{
    public $message = "ERROR MESSGAGE!!!";

    public function render() {
        $this->render("error_view.tpl" , ["title" => "ErrorMessage",
            "message" => $this->message ]);
        exit(2);
    }

}
