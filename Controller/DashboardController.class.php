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
        $this->talent_limit = 3;
    }

    public function run(){
        guaranteeLogin("/dashboard");
        $this->guaranteeProfile();
    }

    public function guaranteeProfile(){
        if($this->checkAmounts()){
        } else {
            render("Dashboard.tpl", ["title" => $_SESSION["user"]->displayName , "wishes" => $this->getMyWishes() , "talents" => $this->getMyTalents()]);
        }
    }

    private function getMyWishes(){
        return $this->wishRepo->getMyWishes();
    }

    private function getMyTalents(){
        return $this->talentRepo->getUserTalents();
    }

    private function checkAmounts(){
        if(!empty($_SESSION['user'])) {
            $wishAmount = $this->wishRepo->getWishAmount($_SESSION["user"]->email);
            $talentAmount = $this->talentRepo->checkNumberOfTalentsFromUser();

            //3 wishes and 3 talents are mandatory
            if($wishAmount = $this->wish_limit && $talentAmount >= $this->talent_limit){
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}