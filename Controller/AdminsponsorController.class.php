<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 04-06-16
 * Time: 16:09
 */
class AdminsponsorController extends Controller
{

    private $sponsorRepo, $userRepo, $wishRepo, $error;

    private $pageTitle = "Sponsor Beheer";

    private $errorDefault = "Bedrijfsnaam of gebruiker moet worden ingevuld bij het";
    private $errorAdd = " toevoegen";
    private $errorUpdate = " wijzigen";
    private $errorDefaultEnd = " van een sponsor.";

    private $errorNoSponsorsFound = "Er zijn geen sponsors gevonden.";

    public function __construct()
    {
        (new AdminController())->guaranteeAdmin("/Wishes");

        $this->sponsorRepo = new SponsorRepository();
        $this->userRepo = new UserRepository();
        $this->wishRepo = new WishRepository();
    }

    public function run()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        if (!isset($currentPage)) {
            $currentPage = "sponsors";
        }

        $this->renderOverview($currentPage);
    }

    public function renderOverview($currentPage)
    {
        (new AccountController())->guaranteeLogin("/Wishes");

        $users = $this->userRepo->getAllUsers();
        $sponsors = $this->getSponsors();

        if(count($sponsors) === 1){
            $sponsors = array($sponsors);
        }
        
        if (isset($this->error)) {
            $this->render("adminSponsor.tpl", ["title" => $this->pageTitle,
                "sponsors" => $sponsors,
                "users" => $users,
                "currentPage" => $currentPage,
                "error" => $this->error
            ]);
            exit();
        }

        $this->render("adminSponsor.tpl", ["title" => $this->pageTitle,
            "sponsors" => $sponsors,
            "users" => $users,
            "currentPage" => $currentPage
        ]);
    }


    public function getSponsors()
    {
        $sponsors = null;
        if (isset($_SESSION["search"])) {
            $sponsors = $_SESSION["search"];

            if (count($sponsors) === 0) {
                $this->error = $this->errorNoSponsorsFound;
                return $this->sponsorRepo->getAllSponsors();
            }

            if (count($sponsors) === 1) {
                $sponsors = array($_SESSION["search"]);
            }
            unset($_SESSION["search"]);
        } else {
            $sponsors = $this->sponsorRepo->getAllSponsors();
        }
        return $sponsors;
    }

    public function addSponsor(Sponsor $sponsor = null)
    {
        if (!isset($sponsor)) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $sponsor = $this->sponsorFromRequest();
            }
        }

        $this->checkInput($sponsor, $valid);
        if (!$valid) {
            $this->run();
            exit();
        }

        $image = $this->wishRepo->upload($sponsor->image);
        $sponsor->image = $image;
        $this->sponsorRepo->addSponsor($sponsor);
        $this->goBack();

    }

    public function updateSponsor(Sponsor $sponsor = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sponsor = $this->sponsorFromRequest();
            $sponsor->id = $_POST["id"];
        }

        $this->checkInput($sponsor, $valid, true);
        if (!$valid) {
            $this->run();
            exit();
        }

        $image = $this->wishRepo->upload($sponsor->image);
        $sponsor->image = $image;
        $this->sponsorRepo->updateSponsor($sponsor);
        $this->goBack();
    }

    public function deleteSponsor(Sponsor $sponsor = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $sponsor = new Sponsor();
            $sponsor->id = $_GET["sponsorID"];
        }
        $this->sponsorRepo->deleteSponsor($sponsor);
        $this->goBack();
    }

    public function searchSponsor($searchKey = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $searchKey = $_GET["searchKey"];
        }

        $searchKey = str_replace(' ', '', $searchKey);
        if (strlen($searchKey) > 0) {
            $result = $this->sponsorRepo->searchSponsor($searchKey);
            if (count($result) > 0) {
                $_SESSION["search"] = $result;
            } else {
                $_SESSION["search"] = array();
            }
        }
        $this->goBack();
    }

    public function sponsorFromRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sponsor = new Sponsor();
            $sponsor->name = $_POST["name"];
            $sponsor->description = $_POST["description"];
            $sponsor->url = $_POST["url"];

            if ($_POST["userEmail"] != "default") {
                $sponsor->userMail = $_POST["userEmail"];
            }


            if (!empty($_FILES["img"]["tmp_name"])) {
                $sponsor->image = $_FILES["img"];
            }

            return $sponsor;
        }
        return null;
    }

    public function checkInput(Sponsor $sponsor, &$valid, $update = null)
    {
        if (empty($sponsor->userMail) && empty($sponsor->name)) {
            if (isset($update)) {
                $this->error = $this->errorDefault . $this->errorUpdate . $this->errorDefaultEnd;
            } else {
                $this->error = $this->errorDefault . $this->errorAdd . $this->errorDefaultEnd;
            }
            $valid = false;
        } else {
            $valid = true;
        }
    }

    public function goBack()
    {
        $this->redirect("AdminSponsor");
    }


}