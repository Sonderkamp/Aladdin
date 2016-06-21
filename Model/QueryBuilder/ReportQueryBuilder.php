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
        if (!empty($report->wishID)) {

            $string = "`wish_Id`";
            $var = $report->wishID;

        } else {
            $string = "`message_Id`";
            $var = $report->messageID;
        }

        $sql = "INSERT INTO `reportedUsers`
                (`user_Reporter`,`user_Reported`, `reportStatus_status`, " . $string . ", `Message`)
                VALUES (?,?,?,?,?)";

        $parameters = array(
            $report->reporter,
            $report->reported,
            $report->status,
            $var,
            $report->message);

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
        $query = "SELECT * FROM reportedUsers";
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
        $sql = "SELECT * FROM `reportedUsers` WHERE `user_Reporter` = ?";
        $parameters = array($email);

        return Database::query_safe($sql, $parameters);
    }

    /** Get report of user
     * @param $email = reports of user with email
     * @return result
     */
    public function getMyReports($email)
    {
        $sql = "SELECT * FROM `reportedUsers` WHERE `user_Reported` = ?";
        $parameters = array($email);

        return Database::query_safe($sql, $parameters);
    }


    /** Returns array with rerport objects
     * @param $result , result of the database query
     * @return array with report objects
     */
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
            $messageID = $item["message_Id"];

            $report = new Report($reporter, $reported, $status, $wishID, $message, $date, $id);
            $report->messageID = $messageID;
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
            $sql = "UPDATE `reportedUsers`
                    SET `reportStatus_Status` = ? 
                    WHERE `reportedUsers`.`Id` = ?";
            $parameters = array($status, $id);

            Database::query_safe($sql, $parameters);
        }
    }


}