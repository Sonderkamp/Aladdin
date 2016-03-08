<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 8-3-2016
 * Time: 17:50
 */
class AdminWishController
{


    public function run()
    {
        guaranteeLogin("/AdminWish");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "requested":
                    $this->wishPageAction('requested');
                    break;
                case "changed":
                    $this->wishPageAction('changed');
                    break;
                case "open":
                    $this->wishPageAction('open');
                    break;
                case "done":
                    $this->wishPageAction('done');
                    break;
                case "matched":
                    $this->wishPageAction('matched');
                    break;
                case "current":
                    $this->wishPageAction('current');
                    break;
                case "denied":
                    $this->wishPageAction('denied');
                    break;
                case "deleted":
                    $this->wishPageAction('deleted');
                    break;
                case "accept":
                    $this->wishAction('accept', $_GET["wishid"]);
                    break;
                case "deny":
                    $this->wishAction('deny', $_GET["wishid"]);
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->renderPage();
            exit();
        }

        $this->renderPage();
        exit();
    }





    public function renderPage()
    {
        if (!Empty($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "requested":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested')]);
                    break;
                case "changed":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('changed')]);
                    break;
                case "open":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('open')]);
                    break;
                case "matched":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('matched')]);
                    break;
                case "current":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('current')]);
                    break;
                case "done":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('done')]);
                    break;
                case "denied":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('denied')]);
                    break;

            }
            exit();
        }


        render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested')]);
        exit();
    }

    private function wishPageAction($page)
    {
        $wishmodel = new WishRepository();
        $reqwishes = $wishmodel;

        switch ($page) {
            case 'requested':
                $reqwishes = $wishmodel->getRequestedWishes('requested');
                break;
            case 'changed':
                $reqwishes = $wishmodel->getRequestedWishes('changed');
                break;
            case 'open':
                $reqwishes = $wishmodel->getRequestedWishes('open');
                break;
            case 'done':
                $reqwishes = $wishmodel->getRequestedWishes('done');
                break;
            case 'matched':
                $reqwishes = $wishmodel->getRequestedWishes('matched');
                break;
            case 'current':
                $reqwishes = $wishmodel->getRequestedWishes('current');
                break;
            case 'denied':
                $reqwishes = $wishmodel->getRequestedWishes('denied');
                break;
            case 'deleted':
                $reqwishes = $wishmodel->getRequestedWishes('deleted');
                break;

        }
        return $reqwishes;
    }


    private
    function wishAction($action, $wishID)
    {
        $wishmodel = new WishRepository();

        switch ($action) {
            case
            'accept':
                 $wishmodel->AdminAcceptWish($wishID);
                break;
            case 'deny':
                $wishmodel->AdminRefuseWish($wishID);
                break;
        }
        $this->renderPage();
    }

    private function sendRefuseMessage($wishid)
{
    $messagemodel = new messageModel();
    $wishmodel = new WishRepository();
    $test = $wishmodel->getWishOwner($wishid);
    return $test;
//    $messagemodel->sendMessage('Admin',)
}
}