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
        $this->unhandled();
    }

    public function unhandled()
    {
        $this->setCurrent("unhandled");
        $report = $this->reportRepository->getRequested();

        if (count($report) > 0) {
            foreach ($report as $item) {
//                if ($item instanceof Report) {
                    $user = $item->getReported();
//                    if ($user instanceof User) {
                        $temp = new UserRepository();
                        if ($temp->isBlocked($user->getEmail())) {
                            $user->blocked = true;
//                            $user->setBlocked(true);
                        }
//                    };
//                }
            }
        }
        $this->render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function handled()
    {
        $this->setCurrent("handled");
        $report = $this->reportRepository->getHandled();
//        $report = $this->reportRepository->get("handled");
        $this->render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function check()
    {
        $this->unhandled();
    }

    public function block()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->block($id);

            $reported = $this->reportRepository->getId($id);
            $reported = $reported[0];
//            if ($reported instanceof Report) {
                $reported = $reported->getReported();
//                if ($reported instanceof User) {
                    $displayName = $reported->displayName;
//                    getDisplayName();
                    $email = $this->userRepository->getUser($displayName)->email;
//                    getEmail();
                    $this->userRepository->blockUser($email);
//                }
//            }
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