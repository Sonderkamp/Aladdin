<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 1-6-2016
 * Time: 15:43
 */
class AdminmailController extends Controller
{


    public function run(){
        $this->redirect("/");
    }

    public function show()
    {
        (new AdminController())->guaranteeAdmin("/");

        // get message
        if (empty($_GET["id"])) {
            $this->redirect("/");
            exit();
        }
        $message = (new messageRepository())->getMessage($_GET["id"], $_GET["user"]);

        if ($message === false) {
            $this->redirect("/");
            exit();
        }

        // render
        $this->renderAlone("adminMessage.tpl", ["message" => $message]);
        exit();

    }
}