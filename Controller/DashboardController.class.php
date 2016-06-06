<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/04/2016
 * Time: 16:10
 */
class DashboardController extends Controller
{
    private $wishRepo, $talentRepo, $userRepo, $matchRepo, $wishLimit, $talentMinimum;

    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/dashboard");
        $this->userRepo = new UserRepository();
        $this->wishRepo = new WishRepository();
        $this->matchRepo = new MatchRepository();
        $this->talentRepo = new TalentRepository();
        $this->wishLimit = $this->wishRepo->wishLimit;
        $this->talentMinimum = $this->talentRepo->TALENT_MINIMUM;
    }

    public function run()
    {
        if (!$this->checkAmounts()) {
            $this->showForcedProfile();
        } else {
            $this->showProfile();
        }
    }

    public function guaranteeProfile()
    {
        if (!$this->checkAmounts()) {
            $this->showForcedProfile();
        }
    }

    public function showForcedProfile()
    {
        $talentAmount = $this->getTalentAmount();
        $wishAmount = $this->getWishAmount();
        $wishLimit = $this->wishRepo->getWishLimit($this->userRepo->getCurrentUser()->email);
        $wishCheck = false;

        if($wishAmount < $wishLimit){
            $wishCheck = true;
        }

        $this->render("dashboard.tpl", ["title" => $this->userRepo->getCurrentUser()->displayName,
            "user" => $this->userRepo->getCurrentUser(),
            "wishes" => $this->getMyWishes(),
            "talents" => $this->getMyTalents(),
            "wishCheck" => $wishCheck,
            "wishLimit" => $wishLimit,
            "errorString" => $this->generateErrorSentence($wishAmount, $talentAmount)]);
        exit(0);
    }

    public function showProfile()
    {
        $wishAmount = $this->getWishAmount();
        $wishLimit = $this->wishRepo->getWishLimit($this->userRepo->getCurrentUser()->email);
        $wishCheck = false;

        if($wishAmount < $wishLimit){
            $wishCheck = true;
        }

        $this->render("dashboard.tpl", ["title" => $this->userRepo->getCurrentUser()->displayName,
            "user" => $this->userRepo->getCurrentUser(),
            "wishes" => $this->getMyWishes(),
            "talents" => $this->getMyTalents(),
            "wishLimit" => $wishLimit,
            "wishCheck" => $wishCheck
        ]);
        exit(0);
    }


    private function generateErrorSentence($wishAmount, $talentAmount)
    {
        $wishAmount = $this->wishLimit - $wishAmount;
        $talentAmount = $this->talentMinimum - $talentAmount;

        $prefix = "<strong>Pas op!</strong> U heeft uw profiel nog niet voltooid. Vul alstublieft nog ";
        $str = "";
        if ($wishAmount <= $this->wishLimit && $wishAmount > 0) {
            $str .= $wishAmount;
            if ($wishAmount > 1) {
                $str .= " wensen in";
            } else {
                $str .= " wens in";
            }
        }


        if ($talentAmount <= $this->talentMinimum && $talentAmount > 0) {

            if ($str != "") {
                $str .= " en vul ";
            }

            $str .= $talentAmount;
            if ($talentAmount > 1) {
                $str .= " talenten in";
            } else {
                $str .= " talent in";
            }
        }
        if (!empty($str)) {
            return $prefix . $str;
        }
        return $str;
    }

    private function getMyWishes()
    {
        return $this->wishRepo->getMyDashboardWishes();
    }

    private function getMyTalents()
    {
        return $this->talentRepo->getAddedTalents();
    }

    private function getWishAmount()
    {
        return $this->wishRepo->getWishAmount($this->userRepo->getCurrentUser()->email);
    }

    private function getTalentAmount()
    {
        return count($this->talentRepo->getAddedTalents());
    }

    private function checkAmounts()
    {
        if (!empty($this->userRepo->getCurrentUser())) {
            $wishAmount = $this->getWishAmount();
            $talentAmount = $this->getTalentAmount();

            //3 wishes and 3 talents are mandatory
            if ($wishAmount >= $this->wishLimit && $talentAmount >= $this->talentMinimum) {
                return true;
            }
        }
        return false;
    }

}