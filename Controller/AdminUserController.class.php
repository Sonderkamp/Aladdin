<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 14:51
 */
class AdminUserController
{

    private $reportRepository, $current;

    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
    }

    public function run()
    {
        guaranteeLogin("/Wishes");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "home":
                case "unhandled":
                    $this->home();
                    break;
                case "handled":
                    $this->handledReports();
                    break;
                case "block":
                    $this->block();
                    break;
                case "delete":
                    $this->delete();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            $this->home();
        }
    }

    public function home()
    {
        $current = "home";
        $report = $this->reportRepository->get("new");
        render("adminUser.tpl", ["reports" => $report, "current" => $current]);
    }

    public function handledReports()
    {
        $current = "handled";
        $report = $this->reportRepository->get("handled");
        render("adminUser.tpl", ["reports" => $report, "current" => $current]);
    }

    public function block()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->block($id);
        }
        $this->home();
    }

    public function delete()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->delete($id);
        }
        $this->home();
    }

}