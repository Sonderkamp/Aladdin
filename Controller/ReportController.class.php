<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 12:30
 */
class ReportController
{

    private $reportRepository, $wishRepository;


    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
        $this->wishRepository = new WishRepository();
    }

    public function run()
    {
        guaranteeLogin("/Wishes");
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "report":
                    $this->report();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else {
            redirect("/wishes");
        }
    }

    public function report()
    {
        if (!empty($_POST["wish_id"])) {
            $id = $_POST["wish_id"];
            $reporter = $_SESSION["user"]->email;
            $reported = $this->wishRepository->getUserOfWish($id);
            $status = "aangevraagd";
            $message = $_POST["report_message"];
            $report = new Report($reporter, $reported, $status, $id, $message);
            $this->reportRepository->add($report);
        }
        redirect("/wishes");
    }


}