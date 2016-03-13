<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 12-03-16
 * Time: 11:12
 */
class ErrorController {

    public $message = "ERROR MESSGAGE!!!";

    public function render() {
        render("error_view.php", ["title" => "ErrorMessage",
            "message" => $this->message]);
        exit(2);
    }

}