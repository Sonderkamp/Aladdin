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

    /** try to add report
     */
    public function tryAdd()
    {
        $status = "aangevraagd";

        if (!empty($_POST["wish_id"]) && isset($_POST["report_message"])) {

            $id = $_POST["wish_id"];
            $reporter = (new UserRepository())->getCurrentUser();
            if ($reporter === false) {
                return "/wishes";
            }

            $reported = (new WishRepository())->getWish($id)->user->email;


            $message = $_POST["report_message"];
            $report = new Report($reporter->email, $reported, $status, $id, $message);
            $this->reportQueryBuilder->add($report);
            return "/wishes";

        } else if (!empty($_POST["message_id"]) && isset($_POST["report_message"])) {
            $id = $_POST["message_id"];

            $reporter = (new UserRepository())->getCurrentUser();

            if ($reporter === false) {
                return "/Inbox/action=message/message=" . $_POST["message_id"];
            }

            $message = (new messageRepository())->getMessage($id, $reporter->email);

            $reported = (new UserRepository())->getUser($message->sender);

            if ($reported->email === $reporter->email) {
                return "/Inbox/action=message/message=" . $_POST["message_id"];
            }
            $message = $_POST["report_message"];
            $report = new Report($reporter->email, $reported->email, $status, null, $message);
            $report->messageID = $id;
            $this->reportQueryBuilder->add($report);

            return "/Inbox/action=message/message=" . $_POST["message_id"];
        }


    }

    /** returns all reports
     * @return array with report objects
     */
    public function getAll()
    {
        $reports = $this->reportQueryBuilder->get();
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    /** returns all requested reports
     * @return array with report objects
     */
    public function getRequested()
    {
        $reports = $this->reportQueryBuilder->get(true);
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    /** returns a report
     * @param $id = id of report
     * @return Report object
     */
    public function getId($id)
    {
        $report = $this->reportQueryBuilder->get(null, $id);
        return $this->reportQueryBuilder->getReportArray($report);
    }

    /** returns all handled reports
     * @return array with report objects
     */
    public function getHandled()
    {
        $reports = $this->reportQueryBuilder->get(null, null, true);
        return $this->reportQueryBuilder->getReportArray($reports);
    }

    /** set status of report to blocked
     * @param $id = id of the report
     */
    public function block($id)
    {
        $this->reportQueryBuilder->setStatus($this->BLOCK_STATUS, $id);
    }

    /** set status of report to deleted
     * @param $id = id of the report
     */
    public function delete($id)
    {
        $this->reportQueryBuilder->setStatus($this->DELETE_STATUS, $id);
    }

    /** get all reports which user has requested
     * @param $email = email of user | optional
     * @return array with report objects
     */
    public function getReportedUsers($email = null)
    {
        if ($email == null) {
            $email = $this->userRepository->getCurrentUser()->email;
        }

        $reported = $this->reportQueryBuilder->getReportedUsers($email);
        return $this->reportQueryBuilder->getReportArray($reported);
    }

    /** get all reports which user has requested
     * @param $email = email of user | optional
     * @return array with report objects
     */
    public function getMyReports($email = null)
    {
        if ($email == null) {
            $email = $this->userRepository->getCurrentUser()->email;
        }
        $reported = $this->reportQueryBuilder->getMyReports($email);
        return $this->reportQueryBuilder->getReportArray($reported);
    }

}