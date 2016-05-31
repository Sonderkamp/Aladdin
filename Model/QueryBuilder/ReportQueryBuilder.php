<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 13-05-16
 * Time: 11:46
 */
class ReportQueryBuilder
{

    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /** Add report to database
     * @param $report = Report object
     */
    public function add(Report $report)
    {
        $sql = "INSERT INTO `reportedusers` 
                (`user_Reporter`,`user_Reported`, `reportStatus_status`, `wish_Id`, `Message`) 
                VALUES (?,?,?,?,?)";
        $parameters = array(
            $report->getReporter(),
            $report->getReported(),
            $report->getStatus(),
            $report->getWishID(),
            $report->getMessage()
        );

        Database::query_safe($sql, $parameters);
    }


    /** Get report
     * @param $requested , set to get all reports where status = 'aangevraagd'
     * @param $id , set to get report with id
     * @param $handled , set to get all handled reports
     * @return return result
     */
    public function get($requested = null, $id = null, $handled = null)
    {
        $query = "SELECT * FROM reportedusers";
        $parameters = array();

        if ($requested != null) {
            $query .= " WHERE reportStatus_Status = ?";
            $parameters[] = "aangevraagd";
        } else if ($id != null) {
            $query .= " WHERE Id = ?";
            $parameters[] = $id;
        } else if ($handled != null) {
            $query .= " WHERE reportStatus_Status != ? ORDER BY CreationDate DESC";
            $parameters[] = "aangevraagd";
        }

        return Database::query_safe($query, $parameters);
    }

    /** Get report of user
     * @param $email = reports of user with email
     * @return result
     */
    public function getReportedUsers($email)
    {
        // TODO: control for duplicate with getMyReports
        $sql = "SELECT * FROM `reportedusers` WHERE `user_Reporter` = ?";
        $parameters = array($email);

        return Database::query_safe($sql, $parameters);
    }

    /** Get report of user
     * @param $email = reports of user with email
     * @return result
     */
    public function getMyReports($email)
    {
        $sql = "SELECT * FROM `reportedusers` WHERE `user_Reported` = ?";
        $parameters = array($email);

        return Database::query_safe($sql, $parameters);
    }


    /** Returns array with rerport objects
     * @param $result, result of the database query 
     * @return array with report objects */
    public function getReportArray($result)
    {
        if (count($result) <= 0) return;
        // TODO: [MEVLUT] check of reporter en reported samen in zelfde query kunnen
        $reports = array();
        foreach ($result as $item) {
            $id = $item["Id"];
            $message = $item["Message"];
            $date = $item["CreationDate"];
            $reporter = $item["user_Reporter"];
            $reporter = $this->userRepository->getUser($reporter);
            $reported = $item["user_Reported"];
            $reported = $this->userRepository->getUser($reported);
            $status = $item["reportStatus_Status"];
            $wishID = $item["wish_Id"];

            $report = new Report($reporter, $reported, $status, $wishID, $message, $date, $id);
            $reports[] = $report;
        }

        return $reports;
    }


    /** Set reportstatus
     * @param $status = new status for the report
     * @param $id = the id of the report which status needs to be changed
     */
    public function setStatus($status, $id)
    {
        if ($id > 0) {
            $sql = "UPDATE `aladdin_db2`.`reportedusers` 
                    SET `reportStatus_Status` = ? 
                    WHERE `reportedusers`.`Id` = ?";
            $parameters = array($status, $id);

            Database::query_safe($sql, $parameters);
        }
    }


}