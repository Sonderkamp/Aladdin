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
        $this->wishRepository = new WishRepository();
        $this->talenRepository = new TalentRepository();
        $this->reportRepository = new ReportRepository();
        $this->userRepository = new UserRepository();
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

    public function open_match_view()
    {
        $userTalents = $this->talenRepository->getUserTalents();
        $synonmys = $this->talenRepository->getSynonymsOfTalents($userTalents);
        $allTalents = array_merge($userTalents, $synonmys);

        $possibleMatches = $this->wishRepository->getAllWishesWithTag($allTalents);
        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);

        $report = $this->reportRepository->getUsersIHaveReported($_SESSION["user"]->email);

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

        $user = $this->userRepository->getUser($_SESSION["user"]->email);
        $displayName = $user->getDisplayName();

        $_SESSION["current"] = "match";

        render("wishOverview.tpl",
            ["title" => "Vervulde wensen overzicht", "wishes" => $possibleMatches,
                "canAddWish" => $canAddWish, "currentPage" => "match", "displayName" => $displayName, "reported" => $displayNames]);

    }


}