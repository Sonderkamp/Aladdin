<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 21-04-16
 * Time: 15:02
 */
class Report
{

    private $reporter,$reported,$status,$wishID,$message,$moderator, $date, $id;

    public function __construct($reporter, $reported, $status, $wishID, $date = null, $id = null)
    {
        $this->reporter = $reporter;
        $this->reported = $reported;
        $this->status = $status;
        $this->wishID = $wishID;

        if(isset($date)){
            $this->date = $date;
        }
        
        if(isset($id)){
            $this->id = $id;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getModerator()
    {
        return $this->moderator;
    }

    public function getReported()
    {
        return $this->reported;
    }

    public function getReporter()
    {
        return $this->reporter;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getWishID()
    {
        return $this->wishID;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getId()
    {
        return $this->id;
    }



}