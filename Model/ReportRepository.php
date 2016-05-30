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

    public function add(Report $report)
    {
        $this->reportQueryBuilder->add($report);
    }

    public function getAll()
    {
        $reports = $this->reportQueryBuilder->get();
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    public function getRequested()
    {
        $reports = $this->reportQueryBuilder->get(true);
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    public function getId($id)
    {
        $report = $this->reportQueryBuilder->get(null, $id);
        return $this->reportQueryBuilder->getReportArray($report);
    }

    public function getHandled()
    {
        $reports = $this->reportQueryBuilder->get(null, null, true);
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    public function block($id)
    {
        $this->reportQueryBuilder->setStatus($this->BLOCK_STATUS, $id);
    }

    public function delete($id)
    {
        $this->reportQueryBuilder->setStatus($this->DELETE_STATUS, $id);
    }

    // TODO: control for duplicate with getMyReports
    public function getReportedUsers($email = null){
        if($email == null){
            $email = $this->userRepository->getCurrentUser()->email;
        }
        
        $reported = $this->reportQueryBuilder->getReportedUsers($email);
        return $this->reportQueryBuilder->getReportArray($reported);
    }

    public function getMyReports($email = null){
        if($email == null){
            $email = $this->userRepository->getCurrentUser()->email; 
        }
        $reported = $this->reportQueryBuilder->getMyReports($email);
        return $this->reportQueryBuilder->getReportArray($reported);
    }
    
}