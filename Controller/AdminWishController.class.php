<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 8-3-2016
 * Time: 17:50
 */
class AdminWishController
{
    public $wishRepository;

    public function __construct()
    {
        $this->wishRepository = new WishRepository();
    }

    public function run()
    {
        guaranteeAdmin("/AdminWish");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "requested":
                    $this->renderPage("requested");
                    break;
                case "published":
                    $this->renderPage("published");
                    break;
                case "matched":
                    $this->renderPage("matched");
                    break;
                case "current":
                    $this->renderPage("current");
                    break;
                case "completed":
                    $this->renderPage("completed");
                    break;
                case "denied":
                    $this->renderPage("denied");
                    break;
                case "deleted":
                    $this->renderPage("deleted");
                    break;
                case "profile":
//                    $this->wishAction('accept', $_GET["wishid"],$_GET["mdate"]);
//                    $this->wishPageAction('accept');
                    break;
                case "redraw":
                    $this->wishAction('redraw', $_POST["wishid"], $_POST["mdate"], $_POST["user"], "", "");
                    $this->wishPageAction('open');
                    $this->page = 'open';
                    break;
                case "delete":
                    $this->wishAction('delete', $_POST["wishid"], $_POST["mdate"], $_POST["user"], "", "");
                    $this->wishPageAction('open');
                    break;
                case "accept":
                    $this->wishAction('accept', $_POST["wishid"], $_POST["mdate"], $_POST["user"], "", "");
                    $this->wishPageAction('requested');
                    break;
                case "deny":
                    $this->wishAction('deny', $_POST["wishid"], $_POST["mdate"], $_POST["user"], $_POST["message"], $_POST["messagetitle"]);
                    $this->wishPageAction('requested');
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->renderPage("requested");
        }
    }


    public function renderPage($currentPage)
    {
        $requestedWishes = $this->wishRepository->getRequestedWishes();
        $publishedWishes = $this->wishRepository->getPublishedWishes();
        $matchedWishes   = $this->wishRepository->getMatchedWishes();
        $currentWishes   = $this->wishRepository->getCurrentWishes();
        $completedWishes = $this->wishRepository->getCompletedWishes();
        $deniedWishes    = $this->wishRepository->getDeniedWishes();
        $deletedWishes   = $this->wishRepository->getDeletedWishes();

        render("AdminWish.tpl", ["title" => "WensBeheer",
            "requested"   => $requestedWishes,
            "published"   => $publishedWishes,
            "matched"     => $matchedWishes,
            "current"     => $currentWishes,
            "completed"   => $completedWishes,
            "denied"      => $deniedWishes,
            "deleted"     => $deletedWishes,
            "currentPage" => $currentPage
        ]);
    }

    private function refuseWish($wishId){

    }

    private function acceptWish($wishId){

    }

    private function deleteWish($wishId){

    }


    private function wishAction($action, $wishID, $mdate, $username, $message = null, $title = null)
    {
        $wishmodel = new WishRepository();
        $messagemmodel = new messageRepository();
        $wishdetails = $wishmodel->getWish($wishID);
        $newdate = str_replace('%20', ' ', $mdate);
        switch ($action) {
            case
            'accept':
                $wishmodel->AdminAcceptWish($wishID, $newdate);
                $newmessage = "Je wens met de titel: " . $wishdetails->title . ". is geaccepteerd, de inhoud van deze wens is: " . $wishdetails->content . "";
                $this->sendRefuseMessage($username, $wishID, $newmessage, "Je wens is geaccepteerd");


                break;
            case 'deny':
                $wishmodel->AdminRefuseWish($wishID, $newdate);

                $this->sendRefuseMessage($username, $wishID, $message, $title);
                // set link message?

                break;

            case 'delete':
                $wishmodel->AdminDeleteWish($wishID, $newdate);
//                $this->sendRefuseMessage($username,$wishID,$message,$title);
                break;

            case 'redraw':
                $wishmodel->AdminRedrawWish($wishID, $newdate);
//                $this->sendRefuseMessage($username,$wishID,$message,$title);
                break;
        }
    }

    private function sendRefuseMessage($user, $wishid, $message, $title)
    {
        $messagemodel = new messageRepository();
        $wishmodel = new WishRepository();
        $test = $wishmodel->getWishOwner($wishid);
        //"Geachte " + $_GET["wishdisplay"] +"<p> Je wens is afgewezen als u de reden hiervoor wilt weten kunt u contact opnemen via de website. <p> hieronder kunt u de inhoud van de wens nog inzien.<p><p><h4>" + $_GET["wishtitle"] +"</h4><p>" + $_GET["wishcontent"] +"</p>"
        $messageID = $messagemodel->sendMessage('Admin', $user, $title, $message);
        $messagemodel->setLink($wishid,'Wens',$messageID);
    }
}