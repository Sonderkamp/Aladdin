<?php

class ErrorController
{
    public $message = "ERROR MESSGAGE!!!";

    public function render() {
        render("error_view.tpl" , ["title" => "ErrorMessage",
            "message" => $this->message ]);
        exit(2);
    }

}
