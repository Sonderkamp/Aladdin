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

        $this->redirect($this->reportRepository->tryAdd());
        exit();
    }


}