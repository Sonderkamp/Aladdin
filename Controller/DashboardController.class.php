<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/04/2016
 * Time: 16:10
 */
class DashboardController extends Controller
{
    private $wishRepo, $talentRepo, $wishLimit, $talentLimit;

    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/dashboard");
        $this->wishRepo = new WishRepository();
        $this->talentRepo = new TalentRepository();
        $this->wishLimit = $this->wishRepo->wishLimit;
        $this->talentLimit = $this->talentRepo->TALENT_MINIMUM;
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
        $wishAmount = $this->wishLimit - $this->getWishAmount();
        $talentAmount = $this->talentLimit - $this->getTalentAmount();
        $wishCheck = false;

        if ($wishAmount <= $this->wishLimit) {
            $wishCheck = true;
        }

        $this->render("dashboard.tpl", ["title" => $_SESSION["user"]->displayName,
            "wishes" => $this->getMyWishes(),
            "talents" => $this->getMyTalents(),
            "wishCheck" => $wishCheck,
            "errorString" => $this->generateErrorSentence($wishAmount, $talentAmount)]);
        exit(0);
    }

    public function showProfile()
    {
        $this->render("dashboard.tpl", ["title" => $_SESSION["user"]->displayName,
            "wishes" => $this->getMyWishes(),
            "talents" => $this->getMyTalents(),
            "wishCheck" => false
        ]);
        exit(0);
    }


    private function generateErrorSentence($wishAmount, $talentAmount)
    {
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


        if ($talentAmount <= $this->talentLimit && $talentAmount > 0) {

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
        return $this->wishRepo->getMyWishes();
    }

    private function getMyTalents()
    {
        return $this->talentRepo->getAddedTalents();
    }

    private function getWishAmount()
    {
        return $this->wishRepo->getWishAmount($_SESSION["user"]->email);
    }

    private function getTalentAmount()
    {
        return count($this->talentRepo->getAddedTalents());
    }

    private function checkAmounts()
    {
        if (!empty($_SESSION['user'])) {
            $wishAmount = $this->getWishAmount();
            $talentAmount = $this->getTalentAmount();

            //3 wishes and 3 talents are mandatory
            if ($wishAmount >= $this->wishLimit && $talentAmount >= $this->talentLimit) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}