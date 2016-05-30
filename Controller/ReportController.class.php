<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 12:30
 */
class ReportController extends Controller
{

    private $reportRepository, $wishRepository;


    public function __construct()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        $this->reportRepository = new ReportRepository();
        $this->wishRepository = new WishRepository();
    }

    public function run()
    {
        $this->redirect("/wishes");
    }

    /** creates a report  */
    public function report()
    {
        if (!empty($_POST["wish_id"])) {
            $id = $_POST["wish_id"];
            $reporter = $_SESSION["user"]->email;
            $reported = $this->wishRepository->getWish($id)->user->email;
            $status = "aangevraagd";
            $message = $_POST["report_message"];
            $report = new Report($reporter, $reported, $status, $id, $message);
            $this->reportRepository->add($report);
        }
        $this->redirect("/wishes");
    }


}