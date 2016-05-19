<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-03-16
 * Time: 16:56
 */
class MatchController
{

    private $wishRepository, $talenRepository, $reportRepository, $userRepository;

    public function __construct()
    {
        guaranteeLogin("/Wishes");
        $this->wishRepository = new WishRepository();
        $this->talenRepository = new TalentRepository();
        $this->reportRepository = new ReportRepository();
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        $this->open_match_view();
    }

    public function open_match_view()
    {
        /* get my own talents */
        $userTalents = $this->talenRepository->getAddedTalents();

        /* get all sysnoynms of my talents */
        $synonmys = $this->talenRepository->getSynonymsOfTalents($userTalents);

        /* delete multipe talents */
        $allTalents = array_merge($userTalents, $synonmys);

        /* get wishes who match by talents/synonyms */
        $possibleMatches = $this->wishRepository->getAllWishesWithTag($allTalents);

        /* Nothing with matching, only check if user can Add a wish and get his DisplayName */
        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);

        /* Get users I have reported */
        $report = $this->reportRepository->getReportedUsers();

        /* Get the displaynames of the users which I have reported */
        $displayNames = array();
        if (count($report) !== 0) {
            foreach ($report as $item) {
                if ($item instanceof Report) {
                    $user = $item->getReported();
                    if ($user instanceof User) {
                        $displayNames[] = $user->getDisplayName();
                    };
                }
            }
        }

        /* get my own displayname */
        $user = $this->userRepository->getUser($_SESSION["user"]->email);
        $displayName = $user->getDisplayName();

        /* set current to 'match' so I can go back to the correct page */
        $_SESSION["current"] = "match";

        render("wishOverview.tpl",
            ["title" => "Vervulde wensen overzicht", "wishes" => $possibleMatches,
                "canAddWish" => $canAddWish, "currentPage" => "match", "displayName" => $displayName, "reported" => $displayNames]);

    }


}