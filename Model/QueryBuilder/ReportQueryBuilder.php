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

    /* ADD */
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


    /* GET */
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

    public function getReportedUsers($email = null)
    {
        if ($email == null) $email = $_SESSION["user"]->email;

        $sql = "SELECT * FROM `reportedusers` WHERE `user_Reporter` = ?";
        $parameters = array($email);

        return Database::query_safe($sql, $parameters);
    }

    public function getMyReports($email = null)
    {
        if ($email == null) $email = $_SESSION["user"]->email;

        $sql = "SELECT * FROM `reportedusers` WHERE `user_Reported` = ?";
        $parameters = array($email);

        return Database::query_safe($sql, $parameters);
    }

    public function getReportArray($result)
    {
        if (count($result) <= 0) return;

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


    /* SET */
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