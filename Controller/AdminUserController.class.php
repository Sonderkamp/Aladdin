<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 14:51
 */
class AdminUserController
{

    private $reportRepository, $userRepository;

    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        guaranteeAdmin("/Wishes");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "home":
                case "unhandled":
                    $this->unhandledReports();
                    break;
                case "handled":
                    $this->handledReports();
                    break;
                case "check":
                    $this->check();
                    break;
                case "block":
                    $this->block();
                    break;
                case "delete":
                    $this->delete();
                    break;
                default:
                    apologize("404 not found");
                    break;
            }
        } else {
            $this->unhandledReports();
        }
    }

    public function unhandledReports()
    {
        $this->setCurrent("unhandled");
        $report = $this->reportRepository->getRequested();
//        $report = $this->reportRepository->get("new");

        if (count($report) > 0) {
            foreach ($report as $item) {
                if ($item instanceof Report) {
                    $user = $item->getReported();
                    if ($user instanceof User) {
                        $temp = new UserRepository();
                        if($temp->isBlocked($user->getEmail())){
                            $user->setBlocked(true);
                        }
                    };
                }
            }
        }
        render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function handledReports()
    {
        $this->setCurrent("handled");
        $report = $this->reportRepository->getHandled();
//        $report = $this->reportRepository->get("handled");
        render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function check()
    {
        $this->unhandledReports();
    }

    public function block()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->block($id);

            $reported = $this->reportRepository->getId($id);
            $reported = $reported[0];
            if ($reported instanceof Report) {
                $reported = $reported->getReported();
                if ($reported instanceof User) {
                    $displayName = $reported->getDisplayName();
                    $email = $this->userRepository->getUser($displayName)->getEmail();
                    $this->userRepository->blockUser($email);
                }
            }
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
        switch ($this->getCurrent()) {
            case "handled":
                $this->handledReports();
                break;
            case "unhandled":
                $this->unhandledReports();
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