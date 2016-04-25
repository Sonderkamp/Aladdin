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
        $report = $this->reportRepository->get("new");
        render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function handledReports()
    {
        $this->setCurrent("handled");
        $report = $this->reportRepository->get("handled");
        render("adminUser.tpl", ["reports" => $report, "current" => $this->getCurrent()]);
    }

    public function check(){
        $this->unhandledReports();
    }

    public function block()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->reportRepository->block($id);
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