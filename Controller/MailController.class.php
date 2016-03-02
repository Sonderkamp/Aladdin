<?php

class MailController
{
    public $message = "ERROR MESSGAGE!!!";

    public function run() {

        guaranteeLogin("/Inbox");

        render("inbox.php" , ["title" => "Inbox", "folder" => "Postvak in"]);
        exit(2);
    }

}
