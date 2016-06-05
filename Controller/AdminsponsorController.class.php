<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 04-06-16
 * Time: 16:09
 */
class AdminsponsorController extends Controller
{

    private $sponsorRepo, $userRepo,$wishRepo;

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
        $this->renderOverview("sponsors");
    }

    public function renderOverview($currentPage)
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        $sponsors = $this->sponsorRepo->getAllSponsors();
        $users = $this->userRepo->getAllUsers();


        $this->render("adminSponsor.tpl", ["title" => "Sponsor Beheer",
            "sponsors" => $sponsors,
            "users" => $users,
            "currentPage" => $currentPage
        ]);
    }

    public function addSponsor(Sponsor $sponsor = null)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sponsor = new Sponsor();
            $sponsor->name = $_POST["name"];
            $sponsor->description = $_POST["description"];
            $sponsor->url = $_POST["url"];
            $sponsor->image = $this->getImage();

            if ($_POST["userEmail"] != "default") {
                $sponsor->userMail = $_POST["userEmail"];
            }
        }

        $this->sponsorRepo->addSponsor($sponsor);
        $this->uploadeImage($sponsor->image);
    }
    
    public function getImage(){
//        echo "IMAGE: " . $_FILES["img"]["tmp_name"];
        if (!empty($_FILES["img"]["tmp_name"])) {
            return $_FILES["img"];
        }
    }

    public function uploadeImage($img){
        if ($img != null) {
            if (!empty($img['tmp_name'])) {
                $url = $this->upload($img);
                $this->wishRepo->upload($img);

                if ($url == null) {
                    return "Er is een invalide bestand meegegeven. Alleen foto bestanden worden geaccepteerd tot 10MB";
                }
            }
        }
    }

    public function valid($sponsors)
    {
        foreach ($sponsors as $item) {
            if (empty($item->name) || empty($item->description) || empty($item->url)) {
                return false;
            }
        }
        return true;
    }


}