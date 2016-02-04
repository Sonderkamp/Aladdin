<?php

class ErrorController
{
    public $message = "ERROR MESSGAGE!!!";

    public function render() {
        render("error_view.php" , ["title" => "ErrorMessage",
            "message" => $this->message ]);
    }

}
