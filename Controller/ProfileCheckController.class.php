<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 9-3-2016
 * Time: 20:10
 */
class ProfileCheckController
{


    public function run()
    {
        guaranteeLogin("/ProfileCheck");
        if (isset($_GET["user"]) && !isset($_GET["action"])) {
            $this->renderProfilePage($_GET["user"]);
        } elseif (isset($_GET["action"]) || isset($_GET["user"]))
        {
            if($_GET["action"] =='block')
            {

                $this->block($_GET["user"]);
                $this->renderProfilePage($_GET["user"]);
            }
            else if($_GET["action"] =='unblock')
            {

                $this->unblock($_GET["user"]);
                $this->renderProfilePage($_GET["user"]);
            }
        }
        else
        {
            apologize("404 not found, This user does not exist");
        }
    }

    private function getUserCheck($user)
    {
        $usermodel = new User();
        return $usermodel->getUser($user);


    }

    private function block($user)
    {
        $usermodel = new User();
        $usermodel->blockUser($user);

    }

    private function unblock($user)
    {
        $usermodel = new User();
        $usermodel->unblockUser($user);

    }

    private function getLastBlockStatus($user)
{
    $usermodel = new User();
    return $usermodel->getLastBlockStatus($user);
}

    private function getWishes($user)
    {
        $wishmodel = new WishRepository();
        return $wishmodel->getUserWishes($user);
    }

    private function getTalents($user)
    {
        $wishmodel = new TalentRepository();
        return $wishmodel->getTalents(null,null,null,null,null,null,null,$user);
    }

    private function getBlocks($user)
    {
        $usermodel = new User();
        return $usermodel->getAllBlocks($user);
    }


    private function renderProfilePage($user)
    {

        render("profilecheck.tpl", ["title" => "Profiel", "cuser" => $this->getUserCheck($user), "blockstatus" => $this->getLastBlockStatus($user),"wishes" => $this->getWishes($user),"talents" => $this->getTalents($user),"blocks" => $this->getBlocks($user)]);
        exit();
    }


}