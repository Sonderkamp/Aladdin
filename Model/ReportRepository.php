<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 15:03
 */
class ReportRepository
{
    private $reportQueryBuilder, $userRepository;

    private $BLOCK_STATUS = "bevestigd";
    private $DELETE_STATUS = "verwijderd";

    public function __construct()
    {
        $this->reportQueryBuilder = new ReportQueryBuilder();
        $this->userRepository = new UserRepository();
    }

    /** add report
     * @param $report Report object*/
    public function add(Report $report)
    {
        $this->reportQueryBuilder->add($report);
    }

    /** returns all reports
     * @return array with report objects */
    public function getAll()
    {
        $reports = $this->reportQueryBuilder->get();
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    /** returns all requested reports
     * @return array with report objects */
    public function getRequested()
    {
        $reports = $this->reportQueryBuilder->get(true);
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    /** returns a report
     * @param $id = id of report
     * @return Report object*/
    public function getId($id)
    {
        $report = $this->reportQueryBuilder->get(null, $id);
        return $this->reportQueryBuilder->getReportArray($report);
    }

    /** returns all handled reports
     * @return array with report objects */
    public function getHandled()
    {
        $reports = $this->reportQueryBuilder->get(null, null, true);
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    /** set status of report to blocked
     * @param $id = id of the report */
    public function block($id)
    {
        $this->reportQueryBuilder->setStatus($this->BLOCK_STATUS, $id);
    }

    /** set status of report to deleted
     * @param $id = id of the report */
    public function delete($id)
    {
        $this->reportQueryBuilder->setStatus($this->DELETE_STATUS, $id);
    }

    /** get all reports which user has requested
     * @param $email = email of user | optional
     * @return array with report objects */
    public function getReportedUsers($email = null){
        if($email == null){
            $email = $this->userRepository->getCurrentUser()->email;
        }
        // TODO: control for duplicate with getMyReports
        $reported = $this->reportQueryBuilder->getReportedUsers($email);
        return $this->reportQueryBuilder->getReportArray($reported);
    }

    /** get all reports which user has requested
     * @param $email = email of user | optional
     * @return array with report objects */
    public function getMyReports($email = null){
        if($email == null){
            $email = $this->userRepository->getCurrentUser()->email; 
        }
        $reported = $this->reportQueryBuilder->getMyReports($email);
        return $this->reportQueryBuilder->getReportArray($reported);
    }
    
}