<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */
class Wish {


    public $id, $user, $title, $completed, $content, $accepted, $newestDate, $status, $contentDate;

    /**
     * Wish constructor.
     * @param $id
     * @param $user
     * @param $title
     * @param $completed
     * @param $content
     * @param $accepted
     * @param $newestDate
     * @param $status
     * @param $contentDate
     */
    public function __construct($id, $user, $title, $completed, $content, $accepted, $newestDate, $contentDate , $status) {
        $this->id = $id;
        $this->user = $user;
        $this->title = $title;
        $this->completed = $completed;
        $this->content = $content;
        $this->accepted = $accepted;
        $this->newestDate = $newestDate;
        $this->contentDate = $contentDate;
        $this->status = $status;

    }

}



