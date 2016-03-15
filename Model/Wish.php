<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */
class Wish {


    public $id, $userEmail, $userDisplayName, $userCity, $title, $completed, $content, $accepted, $newestDate, $status, $contentDate;


    /**
     * Wish constructor.
     * @param $id
     * @param $userEmail
     * @param $userDisplayName
     * @param $userCity
     * @param $title
     * @param $completed
     * @param $content
     * @param $accepted
     * @param $newestDate
     * @param $status
     * @param $contentDate
     */
    public function __construct($id, $user, $userDisplayName, $userCity, $title, $completed, $content, $accepted, $newestDate, $contentDate, $status) {
        $this->id = $id;
        $this->userEmail = $user;
        $this->userDisplayName = $userDisplayName;
        $this->userCity = $userCity;
        $this->title = $title;
        $this->completed = $completed;
        $this->content = $content;
        $this->accepted = $accepted;
        $this->newestDate = $newestDate;
        $this->contentDate = $contentDate;
        $this->status = $status;

    }

}



