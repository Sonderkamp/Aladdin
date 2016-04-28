<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/04/2016
 * Time: 16:10
 */
class DashboardController
{
    private $wishRepo, $talentRepo, $wish_limit, $talent_limit;

    public function __construct(){
        $this->wishRepo = new WishRepository();
        $this->talentRepo = new TalentRepository();
        $this->wish_limit = $this->wishRepo->WISH_LIMIT;
        $this->talent_limit = $this->talentRepo->TALENT_MINIMUM;
    }

    public function run(){
        guaranteeLogin("/dashboard");
        $this->guaranteeProfile();
    }

    public function guaranteeProfile(){
        if($this->checkAmounts()){
        } else {
            $wishAmount = $this->wish_limit - $this->getWishAmount();
            $talentAmount = $this->talent_limit - $this->getTalentAmount();

            render("Dashboard.tpl", ["title" => $_SESSION["user"]->displayName ,
                "wishes" => $this->getMyWishes() ,
                "talents" => $this->getMyTalents() ,
                "wishAmount" => $wishAmount ,
                "talentAmount" => $talentAmount ,
                "errorString" => $this->generateErrorSentence($wishAmount , $talentAmount)]);
            exit(0);
        }
    }

    private function generateErrorSentence($wishAmount , $talentAmount){
        $prefix = "<strong>Pas op!</strong> U heeft uw profiel nog niet voltooid. Vul alstublieft nog ";
        $str = "";
        if($wishAmount < $this->wish_limit){
            $str .= $wishAmount;
            if($wishAmount > 1){
                $str .= " wensen in";
            } else {
                $str .= " wens in";
            }
        }

        
        if($talentAmount < $this->talent_limit){

            if($str != "")
            {
                $str .= " en vul ";
            }

            $str .= $talentAmount;
            if($talentAmount > 1){
                $str .= " talenten in";
            } else {
                $str .= " talent in";
            }
        }

        return $prefix . $str;
    }

    private function getMyWishes(){
        return $this->wishRepo->getMyWishes();
    }

    private function getMyTalents(){
        return $this->talentRepo->getUserTalents();
    }

    private function getWishAmount(){
        return $this->wishRepo->getWishAmount($_SESSION["user"]->email);
    }

    private function getTalentAmount(){
        return $this->talentRepo->checkNumberOfTalentsFromUser();
    }

    private function checkAmounts(){
        if(!empty($_SESSION['user'])) {
            $wishAmount = $this->getWishAmount();
            $talentAmount = $this->getTalentAmount();

            //3 wishes and 3 talents are mandatory
            if($wishAmount >= $this->wish_limit && $talentAmount >= $this->talent_limit){
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}