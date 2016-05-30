<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 9-3-2016
 * Time: 20:10
 */
class ProfileoverviewController extends Controller
{

    public function __construct()
    {

        (new AdminController())->guaranteeAdmin("/ProfileCheck");
    }

    public function run()
    {
        $this->apologize("404 not found.");
    }

    public function block()
    {

        $userRepo = new UserRepository();
        $user = $userRepo->getUser($_GET["user"]);
        if ($user === false) {
            $this->apologize("Gebruiker bestaat niet.");
        }
        $userRepo->blockUser($user->email, $_GET["reason"]);
        $this->redirect("/profileoverview/action=viewProfile/user=" . $user->email);
    }

    public function deblock()
    {
        $userRepo = new UserRepository();
        $user = $userRepo->getUser($_GET["user"]);
        if ($user === false) {
            $this->apologize("Gebruiker bestaat niet.");
        }
        $userRepo->unblockUser($user->email, $_GET["reason"]);
        $this->redirect("/profileoverview/action=viewProfile/user=" . $user->email);
    }

    public function viewProfile()
    {

        $userRepo = new UserRepository();
        $wishRepo = new WishRepository();
        $talentRepo = new TalentRepository();
        $reportRepo = new ReportRepository();

        $user = $userRepo->getUser($_GET["user"]);

        if ($user === false) {
            $this->apologize("Gebruiker bestaat niet.");
        }
        $blocks = $userRepo->getAllBlocks($user->email);
        $blockStatus = $userRepo->isBlocked($user->email);
        $wishes = $wishRepo->getWishesByUser($user->email);

        // quikfix, als user geen wensen heeft krijg ik error in de profilecheck.tpl
        // met count($wishes) krijg ik dan ook als resultaat 1, ook al heeft deze persoon geen wensen.
        if($wishes[0] === null){
            $wishes = null;
        }

        $talents = $talentRepo->getAddedTalents($user->email);
        $reports = $reportRepo->getMyReports($user->email);
        $reports2 = $reportRepo->getReportedUsers($user->email);

        $this->render("ProfileCheck.tpl", ["title" => "Profiel", "curUser" => $user, "blockstatus" => $blockStatus, "wishes" => $wishes, "talents" => $talents, "blocks" => $blocks, "reports" => $reports, "reports2" => $reports2]);
        exit();
    }


}