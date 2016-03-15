<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 8-3-2016
 * Time: 17:50
 */
class AdminWishController
{
    private $page;
    public function __construct()
    {
        guaranteeLogin("/AdminWish");

        if (!isset($this->page))
        {

        $this->page = "requested";
        }
    }
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
                    $this->wishAction('redraw', $_POST["wishid"],$_POST["mdate"],$_POST["user"],"","");
                    $this->wishPageAction('open');
                    $this->page = 'open';
                    break;
                case "delete":
                    $this->wishAction('delete', $_POST["wishid"],$_POST["mdate"],$_POST["user"],"","");
                    $this->wishPageAction('open');
                    break;
                case "accept":
                    $this->wishAction('accept', $_POST["wishid"],$_POST["mdate"],$_POST["user"],"","");
                    $this->wishPageAction('requested');
                    break;
                case "deny":
                    $this->wishAction('deny', $_POST["wishid"],$_POST["mdate"],$_POST["user"],$_POST["message"],$_POST["messagetitle"]);
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
//        if (!Empty($_GET["action"])) {
//            switch (strtolower($_GET["action"])) {
//                case "requested":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested'),"current_page" => $this->page ]);
//                    break;
//                case "accept":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested'),"current_page" => $this->page ]);
//                    break;
//                case "deny":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested'),"current_page" => $this->page ]);
//                    break;
//                case "changed":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('changed'),"current_page" => $this->page ]);
//                    break;
//                case "open":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('open'),"current_page" => $this->page ]);
//                    break;
//                case "redraw":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('open'),"current_page" => $this->page ]);
//                    break;
//                case "delete":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('open'),"current_page" => $this->page ]);
//                    break;
//                case "matched":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('matched'),"current_page" => $this->page ]);
//                    break;
//                case "current":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('current'),"current_page" => $this->page ]);
//                    break;
//                case "done":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('done'),"current_page" => $this->page ]);
//                    break;
//                case "denied":
//                    render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('denied'),"current_page" => $this->page ]);
//                    break;
//                default:
                    render("AdminWish.tpl", ["title" => "WensBeheer",
                        "reqwishes" => $this->wishPageAction('requested'),
                        "openwishes" => $this->wishPageAction('open'),
                        "matchedwishes" => $this->wishPageAction('matched'),
                        "currentwishes" => $this->wishPageAction('current'),
                        "donewishes" => $this->wishPageAction('done'),
                        "deniedwishes" => $this->wishPageAction('denied'),
                        "deletedwishes" => $this->wishPageAction('deleted'),
                        "current_page" => $this->page
                    ]);
//                    break;
//
//
//            }
//            exit();
//        }
//
//        render("AdminWish.tpl", ["title" => "WensBeheer", "reqwishes" => $this->wishPageAction('requested'),"current_page" => $this->page]);
//        exit();
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
            case 'matched':
                $reqwishes = $wishmodel->getRequestedWishes('matched');
                break;
            case 'current':
                $reqwishes = $wishmodel->getRequestedWishes('current');
                break;
            case 'done':
                $reqwishes = $wishmodel->getRequestedWishes('done');
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
    function wishAction($action, $wishID, $mdate,$username,$message,$title)
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

                $this->sendRefuseMessage($username,$wishID,$message,$title);
                break;

            case 'delete':
                $wishmodel->AdminDeleteWish($wishID,$newdate);
//                $this->sendRefuseMessage($username,$wishID,$message,$title);
                break;

            case 'redraw':
                $wishmodel->AdminRedrawWish($wishID,$newdate);
//                $this->sendRefuseMessage($username,$wishID,$message,$title);
                break;
        }
    }

    private function sendRefuseMessage($user,$wishid,$message,$title)
{
    $messagemodel = new messageModel();
    $wishmodel = new WishRepository();
    $test = $wishmodel->getWishOwner($wishid);
        //"Geachte " + $_GET["wishdisplay"] +"<p> Je wens is afgewezen als u de reden hiervoor wilt weten kunt u contact opnemen via de website. <p> hieronder kunt u de inhoud van de wens nog inzien.<p><p><h4>" + $_GET["wishtitle"] +"</h4><p>" + $_GET["wishcontent"] +"</p>"
        $messagemodel->sendMessage($_SESSION["user"]->email,$user,$title,$message);
}
}