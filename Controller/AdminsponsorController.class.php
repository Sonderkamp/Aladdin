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
        if (!isset($currentPage)) {
            $currentPage = "sponsors";
        }

        $this->renderOverview($currentPage);
    }

    public function renderOverview($currentPage)
    {

        $users = $this->userRepo->getAllUsers();
        $sponsors = $this->getSponsors();


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


    public function getSponsors($search = null)
    {
        if (empty($search)) {
            return $this->sponsorRepo->getAllSponsors();
        } else {
            return $this->sponsorRepo->searchSponsor($search);
        }

    }

    public function searchSponsor()
    {
        if (empty($_GET["searchKey"])) {
            $this->run();
        } else {

            // validate $_GET["searchKey"];

            if (preg_match("/[^a-z 0-9]/i", $_GET["searchKey"])) {
                $this->error = "Zoeken kan alleen met alphanumerieke karakters";
            }

            $users = $this->userRepo->getAllUsers();
            $sponsors = $this->getSponsors($_GET["searchKey"]);


            if (isset($this->error)) {
                $this->render("adminSponsor.tpl", ["title" => $this->pageTitle,
                    "sponsors" => $this->getSponsors(),
                    "users" => $users,
                    "currentPage" => "sponsors",
                    "error" => $this->error
                ]);
                exit();
            }

            $this->render("adminSponsor.tpl", ["title" => $this->pageTitle,
                "sponsors" => $sponsors,
                "users" => $users,
                "currentPage" => "sponsors",
                "search" => $_GET["searchKey"]
            ]);
        }

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

        if ($sponsor->image !== null) {
            $image = $this->wishRepo->upload($sponsor->image);
            $sponsor->image = $image;
        }

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


    public function sponsorFromRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sponsor = new Sponsor();
            $sponsor->name = $_POST["name"];
            $sponsor->description = $_POST["description"];
            $sponsor->url = str_replace("http://", "", $_POST["url"]);


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