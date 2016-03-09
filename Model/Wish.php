<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */

class Wish{

    public $Id, $user, $title, $completed, $content, $accepted, $date, $status;

    function __construct($Id, $user , $title, $completed, $content, $accepted, $date, $status) {
        $this -> Id = $Id;
        $this -> user = $user;
        $this -> title = $title;
        $this -> completed = $completed;
        $this -> content = $content;
        $this -> accepted = $accepted;
        $this -> date = $date;
        $this -> status = $status;
    }

}



