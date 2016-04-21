<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 15:03
 */
class ReportRepository
{

    public function add(Report $report)
    {
        $sql = "INSERT INTO `reportedusers` (`user_Reporter`,`user_Reported`, `reportStatus_status`, `moderator_Username`, `wish_Id`, `message_Id`) 
                VALUES (?,?,?,?,?,?)";

        $parameters = array($report->getReporter(), $report->getReported(),
            $report->getStatus(), $report->getModerator(), $report->getWishID(), $report->getMessage());

        Database::query_safe($sql, $parameters);
    }

    public function get($type, $id = null)
    {
        switch ($type) {
            case "all":
                $query = "SELECT * FROM reportedusers";
                break;
            case "new":
                $status = "aangevraagd";
                $query = "SELECT * FROM reportedusers WHERE reportStatus_Status = ?";
                $parameters = array($status);
                break;
            case "single":
                if (isset($id)) {
                    $query = "SELECT * FROM reportedusers WHERE Id = $id";
                }
                break;
            case "handled":
                $status = "aangevraagd";
                $query = "SELECT * FROM reportedusers WHERE reportStatus_Status != ? ORDER BY CreationDate DESC";
                $parameters = array($status);
                break;

        }

        if (isset($parameters)) {
            $result = Database::query_safe($query, $parameters);
        } else {
            $result = Database::query($query);
        }

        return $this->create($result);

//        if ($type === "all") {
//            $query = "SELECT * FROM reportedusers";
//        }
//
//        if ($type === "new") {
//            $status = "aangevraagd";
//            $query = "SELECT * FROM reportedusers WHERE reportStatus_Status = ?";
//            $parameters = array($status);
//        }
//
//        if ($type === "single") {
//            if (isset($id)) {
//                $query = "SELECT * FROM reportedusers WHERE Id = $id";
//            }
//        }
//
//        if ($type === "handled") {
//            $status = "aangevraagd";
//            $query = "SELECT * FROM reportedusers WHERE reportStatus_Status != ? ORDER BY CreationDate DESC";
//            $parameters = array($status);
//        }

//        $result = Database::querys($query);

    }


    public function block($id)
    {
        if ($id > 0) {
            $sql = "UPDATE `aladdin_db2`.`reportedusers` SET `reportStatus_Status` = 'bevestigd' WHERE `reportedusers`.`Id` = ?";
            $parameters = array($id);

            Database::query_safe($sql, $parameters);
        }
    }

    public function delete($id)
    {
        if ($id > 0) {
            $sql = "UPDATE `aladdin_db2`.`reportedusers` SET `reportStatus_Status` = 'verwijderd' WHERE `reportedusers`.`Id` = ?";
            $parameters = array($id);

            Database::query_safe($sql, $parameters);
        }
    }

    public function create($result)
    {
        if (count($result) <= 0) {
            return;
        }

        $reports = array();
        foreach ($result as $item) {
            $id = $item["Id"];
            $message = $item["Message"];
            $date = $item["CreationDate"];
            $reporter = $item["user_Reporter"];
            $reported = $item["user_Reported"];
            $status = $item["reportStatus_Status"];
            $moderator = $item["moderator_Username"];
            $wishID = $item["wish_Id"];
            $messageID = $item["message_Id"];

            $report = new Report($reporter, $reported, $status, $wishID, $date, $id);
            $reports[] = $report;
        }


        return $reports;
    }


}