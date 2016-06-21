<?php
//
///**
// * Created by PhpStorm.
// * User: MevlutOzdemir
// * Date: 21-03-16
// * Time: 16:56
// */
class MatchController extends Controller
{
    private $wishRepo , $matchRepo, $userRepo;

    public function __construct()
    {
        $this->wishRepo = new WishRepository();
        $this->matchRepo = new MatchRepository();
        $this->userRepo = new UserRepository();
    }

    public function removeMatch()
    {
        if (!empty($_GET["Id"])) {
            if($this->wishRepo->getWish("Id")->status != "Vervuld"){
                if(count($this->matchRepo->getMatches($_GET["Id"])) <= 1){
                    $this->wishRepo->removeMatchStatus($_GET["Id"]);
                }
                $this->matchRepo->clearSelected($_GET["Id"]);
                $this->wishRepo->removeMatch($_GET["Id"]);
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
            } else {
                $err = "Je kunt je match niet terug trekken wanneer een wens is vervuld.";
                $_SESSION["error"] = $err;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
            }
        }
    }

    public function requestMatch()
    {
        if (!empty($_GET["Id"]) && !empty($this->userRepo->getCurrentUser())) {

            if ($this->matchRepo->checkOwnWish($this->userRepo->getCurrentUser()->email, $_GET["Id"])) {
                $err = "Je kan niet met je eigen wensen matchen";
                $_SESSION["error"] = $err;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
                exit(0);
            }

            if ($this->matchRepo->checkDuplicates($this->userRepo->getCurrentUser()->email, $_GET["Id"])) {
                $err = "Je hebt al met deze wens gematched";
                $_SESSION["error"] = $err;
                $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
                exit(0);
            }

            $this->matchRepo->setMatch($_GET["Id"], $this->userRepo->getCurrentUser()->email);

        } else {
            $err = "Geef alsjeblieft een wens id en zorg ervoor dat je bent ingelogd.";
            $_SESSION["error"] = $err;
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
        }

        $this->redirect("/wishes/action=getSpecificWish?Id=" . $_GET["Id"]);
    }

    public function selectMatch()
    {
        if ($this->userRepo->getCurrentUser()->email && !empty($_POST["Id"]) && !empty($_POST["User"])) {
            $this->matchRepo->clearSelected($_POST["Id"]);
            $this->matchRepo->selectMatch($_POST["Id"], $_POST["User"]);
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
        } else {
            $err = "Geef alsjeblieft een wens id en gebruikers id op";
            $_SESSION["error"] = $err;
            $this->redirect("/wishes/action=getSpecificWish?Id=" . $_POST["Id"]);
        }
    }
}