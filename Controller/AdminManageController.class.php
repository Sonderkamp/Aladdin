<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 29-5-2016
 * Time: 14:51
 */
class AdminManageController extends Controller
{
    private $adminRepo;

    public function __construct()
    {
        (new AdminController())->guaranteeAdmin("/AdminManage");
        $this->adminRepo = new AdminRepository();
    }

    public function run()
    {
        $this->checkSession($addError, $addUsername, $editError, $oldUsername);

        $this->render("Admin/adminManage.tpl",
            ["title" => "Moderators beheren",
                "currentAdmin" => $this->adminRepo->getCurrentAdmin(),
                "admins" => $this->adminRepo->getAdmins(),
                "addError" => $addError,
                "editError" => $editError,
                "addUsername" => $addUsername,
                "oldUsername" => $oldUsername]);
        exit();
    }

    public function addAdmin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->adminRepo->addAdmin();

            if ($result !== true) {
                $_SESSION["addError"] = $result;

                if (!empty($_POST["username"])) {
                    $_SESSION["addUsername"] = htmlspecialchars($_POST["username"]);
                }
            }
        }

        $this->redirect("/AdminManage");
    }

    public function editAdmin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->adminRepo->changePassword();

            if ($result !== true) {
                $_SESSION["editError"] = $result;

                if (!empty($_POST["username"])) {
                    $_SESSION["editUsername"] = htmlspecialchars($_POST["username"]);
                }
                if (!empty($_POST["oldUsername"])) {
                    $_SESSION["oldUsername"] = htmlspecialchars($_POST["oldUsername"]);
                }
            }
        }

        $this->redirect("/AdminManage");
    }

    public function blockAdmin()
    {
        $this->adminRepo->blockAdmin();

        $this->redirect("/AdminManage");
    }

    public function unblockAdmin()
    {
        $this->adminRepo->unblockAdmin();

        $this->redirect("/AdminManage");
    }

    private function checkSession(&$addError, &$addUsername, &$editError, &$oldUsername)
    {
        if (!empty($_SESSION["addError"])) {
            $addError = $_SESSION["addError"];
            $_SESSION["addError"] = null;

            if (!empty($_SESSION["addUsername"])) {
                $addUsername = $_SESSION["addUsername"];
                $_SESSION["addUsername"] = null;
            } else {
                $addUsername = "";
            }
        } else {
            $addError = "";
            $addUsername = "";
        }

        if (!empty($_SESSION["editError"])) {
            $editError = $_SESSION["editError"];
            $_SESSION["editError"] = null;

            if (!empty($_SESSION["oldUsername"])) {
                $oldUsername = $_SESSION["oldUsername"];
                $_SESSION["oldUsername"] = null;
            } else {
                $oldUsername = "";
            }
        } else {
            $editError = "";
            $oldUsername = "";
        }
    }
}