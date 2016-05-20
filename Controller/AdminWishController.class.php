<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 8-3-2016
 * Time: 17:50
 */
class AdminwishController extends Controller
{
    public $wishRepo, $messRepo;

    public function __construct()
    {
        (new AdminController())->guaranteeAdmin("/AdminWish");
        $this->wishRepo = new WishRepository();
        $this->messRepo = new MessageRepository();
    }

    public function run()
    {

        // BREEKT MET NIEUWE STRCTUUR TODO

        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "revert":
                    $this->revertWish($_POST["wish_id"]);
                    break;
                case "delete":
                    $this->deleteWish($_POST["wish_id"]);
                    break;
                case "accept":
                    $this->acceptWish($_POST["wish_id"]);
                    break;
                case "refuse":
                    $this->refuseWish($_POST["wish_id"]);
                    break;
                default:
                    $this->apologize("404 page not found");
                    break;
            }
        }

        if (isset($_GET["show"])) {
            switch (strtolower($_GET["show"])) {
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
                default:
                    $this->apologize("404 page not found");
                    break;
            }
        }

        $this->renderPage("requested");
    }


    private function renderPage($currentPage)
    {
        $requestedWishes = $this->wishRepo->getRequestedWishes();
        $publishedWishes = $this->wishRepo->getPublishedWishes();
        $matchedWishes = $this->wishRepo->getMatchedWishes();
        $currentWishes = $this->wishRepo->getCurrentWishes();
        $completedWishes = $this->wishRepo->getCompletedWishes();
        $deniedWishes = $this->wishRepo->getDeniedWishes();
        $deletedWishes = $this->wishRepo->getDeletedWishes();

        $this->render("AdminWish.tpl", ["title" => "WensBeheer",
            "requested" => $requestedWishes,
            "published" => $publishedWishes,
            "matched" => $matchedWishes,
            "current" => $currentWishes,
            "completed" => $completedWishes,
            "denied" => $deniedWishes,
            "deleted" => $deletedWishes,
            "currentPage" => $currentPage
        ]);

        exit(0);
    }

    private function refuseWish($wishId)
    {
        $this->wishRepo->refuseWish($wishId);
        $this->sendConfirmationMessage($wishId, false);
    }

    private function acceptWish($wishId)
    {
        $this->wishRepo->acceptWish($wishId);
        $this->sendConfirmationMessage($wishId, true);
    }

    private function deleteWish($wishId)
    {
        $this->wishRepo->deleteWish($wishId);
    }

    private function revertWish($wishId)
    {
        $this->wishRepo->revertWishAction($wishId);
    }

    private function sendConfirmationMessage($wishId, $accepted)
    {

        if ($accepted) {
            $verdict = "geaccepteerd";
        } else {
            $verdict = "geweigerd";
        }

        $acceptedWish = $this->wishRepo->getWish($wishId);

        $message = "De wens met de titel '" . $acceptedWish->title . "' is " . $verdict . ". De inhoud van deze wens is alsvolgt: \n" . $acceptedWish->content .
            "\n \n Wij hopen u hiermee voldoende te hebben geinformeerd.";
        $title = "Uw wens is " . $verdict . "!";

        $messageId = $this->messRepo->sendMessage($_SESSION["admin"]->username, $acceptedWish->user->displayName, $title, $message);
        $this->messRepo->setLink($wishId, 'Wens', $messageId);
    }
}