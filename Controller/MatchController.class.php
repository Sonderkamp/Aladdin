<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-03-16
 * Time: 16:56
 */
class MatchController
{

    private $wishRepository;
    private $talenRepository;

    public function __construct()
    {
        $this->wishRepository = new WishRepository();
        $this->talenRepository = new TalentRepository();
    }

    public function run()
    {
        guaranteeLogin("/Wishes");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "open_match_view":
                    $this->open_match_view();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->open_match_view();
        }
    }

    public function open_match_view(){

        $userTalents = $this->talenRepository->getUserTalents();
        $possibleMatches = $this->wishRepository->getAllWishesWithTag($userTalents);

        render("match_view.tpl", ["currentPage"=> "match", "possibleMatches" => $possibleMatches]);
    }



}