<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 14:51
 */
class AdminuserController extends Controller
{

    private $reportRepository, $userRepository, $wishRepository, $talentRepository;

    public function __construct()
    {
        (new AdminController())->guaranteeAdmin("/Wishes");
        $this->reportRepository = new ReportRepository();
        $this->userRepository = new UserRepository();
        $this->wishRepository = new WishRepository();
        $this->talentRepository = new TalentRepository();
    }

    public function run()
    {
        $this->renderPrepare("users");
    }


    /** loads page if clicked on the Administrator -> Gebruikersbeehr
     * @param the page to redirect to
     */
    public function renderPrepare($currentPage)
    {
        $users = $this->getUsers();
        $unhandled = $this->unhandled();
        $handled = $this->handled();

        if (count($users) === 0) {
            $error = "Er zijn geen gebruikers gevonden";
            $users = $this->userRepository->getAllUsers();
            $this->renderPage($users, $unhandled, $handled, $currentPage, $error);
        } else {
            $this->renderPage($users, $unhandled, $handled, $currentPage);
        }
    }

    /** Renders to adminUser.tpl by checking if there is an error
     * @param $users -> all users
     * @param $unhandled -> all unhandled reports
     * @param $handled -> all handled reports
     * @param $currentPage -> sets the current page
     * @param $error -> shows the error thats given
     */
    public function renderPage($users, $unhandled, $handled, $currentPage, $error = null)
    {
        if (isset($error)) {
            $this->render("adminUser.tpl", ["title" => "Gebruikers overzicht",
                "handled" => $handled,
                "unhandled" => $unhandled,
                "users" => $users,
                "currentPage" => $currentPage,
                "error" => $error
            ]);
        } else {
            $this->render("adminUser.tpl", ["title" => "Gebruikers overzicht",
                "handled" => $handled,
                "unhandled" => $unhandled,
                "users" => $users,
                "currentPage" => $currentPage
            ]);
        }
    }

    public function getUsers()
    {
        $users = null;
        if (isset($_SESSION["search"])) {
            $users = $_SESSION["search"];
            if (count($users) === 1) {
                $users = array($_SESSION["search"]);
            }
            unset($_SESSION["search"]);
        } else {
            $users = $this->userRepository->getAllUsers();
        }

        return $users;
    }


    /** Get all unhandled reports */
    public function unhandled()
    {
        $this->setCurrent("unhandled");
        $report = $this->reportRepository->getRequested();

        if (count($report) > 0) {
            foreach ($report as $item) {
                $user = $item->reported;
                $temp = new UserRepository();
                if ($temp->isBlocked($user->email)) {
                    $user->blocked = true;
                }
            }
        }
        return $report;
    }

    /** Get all handled reports */
    public function handled()
    {
        $this->setCurrent("handled");
        $report = $this->reportRepository->getHandled();
        return $report;
    }

    public function check()
    {
        $this->unhandled();
    }

    public function blockUser()
    {
        if (isset($_GET["email"])) {
            $this->userRepository->blockUser($_GET["email"]);
        }

        $this->redirect("/AdminUser");
    }

    public function unblockUser()
    {
        if (isset($_GET["email"])) {
            $this->userRepository->unblockUser($_GET["email"]);
        }

        $this->redirect("/AdminUser");
    }

    /** search the users thats given in the searchfield: Admin Gebruikersbeheer */
    public function search()
    {
        if (isset($_GET["search"])) {
            $temp = $_GET["search"];
            $search = str_replace(' ', '', $temp);
            if (strlen($search) > 0) {
                $result = $this->userRepository->searchUsers($search);
                if (count($result) > 0) {
                    $_SESSION["search"] = $result;
                } else {
                    $_SESSION["search"] = array();
                }
            }
        }
        $this->redirect("/AdminUser");
    }

    /** Renders to profile page of selected user */
    public function showProfile()
    {
        if (isset($_GET["email"])) {
            $_GET["user"] = $_GET["email"];
            (new ProfileoverviewController())->viewProfile();
        }
    }


    // kijken of in die blockUser kan verwerken
    public function block()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->block($id);

            $reported = $this->reportRepository->getId($id);
            $reported = $reported[0];
            $reported = $reported->reported;
            $displayName = $reported->displayName;
            $email = $this->userRepository->getUser($displayName)->email;
            $this->userRepository->blockUser($email);
        }

        $this->redirect("/AdminUser");
//        $this->back();
    }

    /** delete report */
    public function delete()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->delete($id);
        }

        $this->redirect("/AdminUser");
//        $this->back();
    }

//    public function back()
//    {
//        $this->run();
//        switch ($this->getCurrent()) {
//            case "handled":
//                $this->handled();
//                break;
//            case "unhandled":
//                $this->unhandled();
//                break;
//        }
//    }

    public function setCurrent($page)
    {
        $_SESSION["current"] = $page;
    }

    public function getCurrent()
    {
        return $_SESSION["current"];
    }


}
