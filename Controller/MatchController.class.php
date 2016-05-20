<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-03-16
 * Time: 16:56
 */
class MatchController extends Controller
{

    private $wishRepository, $talenRepository, $reportRepository, $userRepository;

    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->wishRepository = new WishRepository();
        $this->talenRepository = new TalentRepository();
        $this->reportRepository = new ReportRepository();
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        $this->open_match_view();
    }

    public function ex(){
        exit(1);
    }

    public function open_match_view()
    {
        /** get my own talents */
        $userTalents = $this->talenRepository->getAddedTalents();

        /** get all sysnoynms of my talents */
        $synonmys = $this->talenRepository->getSynonymsOfTalents($userTalents);

        /** delete multipe talents */
        $allTalents = array_merge($userTalents, $synonmys);

        /** get wishes who match by talents/synonyms */
        $possibleMatches = $this->wishRepository->wishesByTalents($allTalents);

        /** Nothing with matching, only check if user can add a wish  */
        $canAddWish = $this->wishRepository->canAddWish($_SESSION["user"]->email);
        
        /** Get users I have reported */
        $report = $this->reportRepository->getReportedUsers();

        /** Get the displaynames of the users which I have reported */
        $displayNames = array();
        if (count($report) !== 0) {
            foreach ($report as $item) {
//                if ($item instanceof Report) {
                    $user = $item->getReported();
//                    if ($user instanceof User) {
                        $displayNames[] = $user->displayName;
//                        getDisplayName();
//                    };
//                }
            }
        }

        /** get my own displayname */
        $user = $this->userRepository->getUser($_SESSION["user"]->email);
        $displayName = $user->displayName;
//        getDisplayName();
        
        
        $_SESSION["current"] = "matchedWishes";

        $this->render("wishOverview.tpl",
            ["title" => "Vervulde wensen overzicht", "matchedWishes" => $possibleMatches,
                "canAddWish" => $canAddWish, "currentPage" => "match", "displayName" => $displayName, "reported" => $displayNames]);

    }


}