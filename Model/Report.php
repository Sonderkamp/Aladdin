<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 15:02
 */
class Report
{

    public $reporter, $reported, $status, $wishID, $message, $moderator, $date, $id, $messageID;

    public function __construct($reporter, $reported, $status, $wishID = null, $message = null, $date = null, $id = null)
    {
        $this->reporter = $reporter;
        $this->reported = $reported;
        $this->status = $status;
        $this->wishID = $wishID;

        if (isset($message)) $this->message = $message;
        if (isset($date)) $this->date = $date;
        if (isset($id)) $this->id = $id;
    }


}