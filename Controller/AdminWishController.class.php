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
                case "profile":
//                    $this->wishAction('accept', $_GET["wishid"],$_GET["mdate"]);
//                    $this->wishPageAction('accept');
                    break;
                case "redraw":
                    $this->wishAction('redraw', $_GET["wishid"],$_GET["mdate"],$_GET["user"]);
                    $this->wishPageAction('open');
                    break;
                case "delete":
                    $this->wishAction('delete', $_GET["wishid"],$_GET["mdate"],$_GET["user"]);
                    $this->wishPageAction('open');
                    break;
                case "accept":
                    $this->wishAction('accept', $_GET["wishid"],$_GET["mdate"],$_GET["user"]);
                    $this->wishPageAction('requested');
                    break;
                case "deny":
                    $this->wishAction('deny', $_GET["wishid"],$_GET["mdate"],$_GET["user"]);
                    $this->wishPageAction('requested');
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
                case "accept":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested')]);
                    break;
                case "deny":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested')]);
                    break;
                case "changed":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('changed')]);
                    break;
                case "open":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('open')]);
                    break;
                case "redraw":
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('open')]);
                    break;
                case "delete":
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
                default:
                    render("AdminWish.php", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('reuqested')]);
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
    function wishAction($action, $wishID, $mdate,$username)
    {
        $wishmodel = new WishRepository();

        $newdate = str_replace('%20', ' ', $mdate);
        switch ($action) {
            case
            'accept':
                 $wishmodel->AdminAcceptWish($wishID,$newdate);
                break;
            case 'deny':
                $wishmodel->AdminRefuseWish($wishID,$newdate);

//                $this->sendRefuseMessage($username,$wishID);
                break;

            case 'delete':
                $wishmodel->AdminDeleteWish($wishID,$newdate);
//                $this->sendRefuseMessage($username,$wishID);
                break;

            case 'redraw':
                $wishmodel->AdminRedrawWish($wishID,$newdate);
//                $this->sendRefuseMessage($username,$wishID);
                break;
        }
    }

    private function sendRefuseMessage($user,$wishid)
{
    $messagemodel = new messageModel();
    $wishmodel = new WishRepository();
    $test = $wishmodel->getWishOwner($wishid);
        //"Geachte " + $_GET["wishdisplay"] +"<p> Je wens is afgewezen als u de reden hiervoor wilt weten kunt u contact opnemen via de website. <p> hieronder kunt u de inhoud van de wens nog inzien.<p><p><h4>" + $_GET["wishtitle"] +"</h4><p>" + $_GET["wishcontent"] +"</p>"
        $messagemodel->sendMessage($_SESSION["user"]->email,$user,'je wens is afgewezen','je wens is afgewezen');
}
}