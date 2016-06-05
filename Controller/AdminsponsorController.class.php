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
    private $errorNoUserOrCompany = "Er moet een bedrijfsnaam of gebruiker worden ingevuld bij het toevoegen van een sponsor.";

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
        $sponsors = $this->sponsorRepo->getAllSponsors();
        $users = $this->userRepo->getAllUsers();

        if (isset($this->error)) {
            $this->render("adminSponsor.tpl", ["title" => "Sponsor Beheer",
                "sponsors" => $sponsors,
                "users" => $users,
                "currentPage" => $currentPage,
                "error" => $this->error
            ]);
            exit();
        }

        $this->render("adminSponsor.tpl", ["title" => "Sponsor Beheer",
            "sponsors" => $sponsors,
            "users" => $users,
            "currentPage" => $currentPage
        ]);
    }

    public function addSponsor(Sponsor $sponsor = null)
    {
        if (!isset($sponsor)) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $sponsor = $this->sponsorFromRequest();
            }
        }

        if (empty($sponsor->userMail) && empty($sponsor->name)) {
            $this->error = $this->errorNoUserOrCompany;
            $this->run();
            exit();
        }

        $this->sponsorRepo->addSponsor($sponsor);
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
            return $sponsor;
        }
        return null;
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

    public function goBack()
    {
        $this->redirect("AdminSponsor");
    }


}