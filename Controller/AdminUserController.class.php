<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 14:51
 */
class AdminuserController extends Controller
{

    private $reportRepository, $userRepository;

    public function __construct()
    {
        (new AdminController())->guaranteeAdmin("/Wishes");
        $this->reportRepository = new ReportRepository();
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        $this->renderPage("users");
//        $this->unhandled();
    }


    public function renderPage($currentPage)
    {
        $unhandled = $this->unhandled();
        $handled = $this->handled();
        $allUsers = $this->userRepository->getAllUsers();
//        print_r($allUsers);
//        exit();

        $this->render("adminUser.tpl", ["title" => "Gebruikers overzicht",
            "handled" => $handled,
            "unhandled" => $unhandled,
            "users" => $allUsers,
            "currentPage" => $currentPage
        ]);
    }


    public function unhandled()
    {
        $this->setCurrent("unhandled");
        $report = $this->reportRepository->getRequested();

        if (count($report) > 0) {
            foreach ($report as $item) {
                $user = $item->getReported();
                $temp = new UserRepository();
                if ($temp->isBlocked($user->getEmail())) {
                    $user->blocked = true;
                }
            }
        }
        return $report;
//        $this->render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function handled()
    {
        $this->setCurrent("handled");
        $report = $this->reportRepository->getHandled();
        return $report;

//        $report = $this->reportRepository->get("handled");
//        $this->render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function check()
    {
        $this->unhandled();
    }
    
    public function blockUser(){
        if(isset($_GET["email"])){
            $this->userRepository->blockUser($_GET["email"]);
        }

        $this->redirect("/AdminUser");
//        $this->back();
    }

    public function unblockUser(){
        if (isset($_GET["email"])){
            $this->userRepository->unblockUser($_GET["email"]);
        }

        $this->redirect("/AdminUser");
//        $this->back();

    }

    public function block()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->block($id);

            $reported = $this->reportRepository->getId($id);
            $reported = $reported[0];
            $reported = $reported->getReported();
            $displayName = $reported->displayName;
            $email = $this->userRepository->getUser($displayName)->email;
            $this->userRepository->blockUser($email);
        }
        $this->back();
    }

    public function delete()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->delete($id);
        }
        $this->back();
    }

    public function back()
    {
        $this->run();
        switch ($this->getCurrent()) {
            case "handled":
                $this->handled();
                break;
            case "unhandled":
                $this->unhandled();
                break;
        }
    }

    public function setCurrent($page)
    {
        $_SESSION["current"] = $page;
    }

    public function getCurrent()
    {
        return $_SESSION["current"];
    }


}