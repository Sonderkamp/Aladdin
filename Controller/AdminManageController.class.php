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
        $this->adminRepo = new AdminRepository();


    }

    public function run()
    {
        if (!empty($_SESSION["adminError"])) {
            $adminError = $_SESSION["adminError"];
            $_SESSION["adminError"] = null;
        } else {
            $adminError = "";
        }

        $this->render("Admin/adminManage.tpl",
            ["title" => "Moderators beheren",
                "admins" => $this->adminRepo->getAdmins(),
                "adminError" => $adminError]);
        exit();
    }

    public function addAdmin()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->adminRepo->addAdmin();

            if ($result !== true) {
                $_SESSION["adminError"] = $result;
            }
        }

        $this->redirect("/AdminManage");
    }
}