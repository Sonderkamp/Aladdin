<?php


class HomeController extends Controller
{

    private $sponsorRepo;

    public function __construct()
    {
        $this->sponsorRepo = new SponsorRepository();
    }

    public function run()
    {
        $sponsors = $this->sponsorRepo->getAllSponsors();
        $this->render("home.tpl", ["title" => "Aladdin", "sponsors" => $sponsors]);
        exit(0);
    }

}