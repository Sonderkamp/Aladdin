<?php

/**
 * Created by PhpStorm.
 * User: max
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
        (new AdminController())->guaranteeAdmin("/AdminWish");
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

        $this->render("adminWish.tpl", ["title" => "WensBeheer",
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

    public function refuseWish()
    {
        if (!empty($_POST["Id"]) && !empty($_POST["Reason"])) {
            $wishId = $_POST["Id"];
            $this->wishRepo->refuseWish($wishId);
            $this->sendConfirmationMessage($wishId, $_POST["Reason"], false);
            $this->redirect("/AdminWish");
        } else {
            $this->apologize("Please provide a valid wish Id and reason");
        }

    }

    public function acceptWish()
    {
        if (!empty($_POST["Id"])&& !empty($_POST["Reason"])) {
            $wishId = $_POST["Id"];
            $this->wishRepo->acceptWish($wishId);
            $this->sendConfirmationMessage($wishId, $_POST["Reason"], true);
            $this->redirect("/AdminWish");
        } else {
            $this->apologize("Please provide a valid wish Id and reason");
        }
    }

    public function deleteWish()
    {
        if (!empty($_GET["Id"])) {
            $wishId = $_GET["Id"];
            $this->wishRepo->deleteWish($wishId);
            $this->redirect("/AdminWish");
        } else {
            $this->apologize("Please provide a valid wish Id");
        }
    }

    private function sendConfirmationMessage($wishId, $reason, $accepted)
    {

        if ($accepted) {
            $verdict = "geaccepteerd";
        } else {
            $verdict = "geweigerd";
        }

        $acceptedWish = $this->wishRepo->getWish($wishId);

        $message = "De wens met de titel '" . $acceptedWish->title . "' is " . $verdict . ". De reden opgegeven is: "
            . $reason . ".\n De inhoud van deze wens is alsvolgt: \n" . $acceptedWish->content
            . "\n \n Wij hopen u hiermee voldoende te hebben geinformeerd.";
        $title = "Uw wens is " . $verdict . "!";

        $messageId = $this->messRepo->sendMessage($_SESSION["admin"]->username, $acceptedWish->user->displayName, $title, $message);
        $this->messRepo->setLink($wishId, 'Wens', $messageId);
    }
}