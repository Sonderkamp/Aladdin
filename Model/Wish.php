<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */

class Wish{

    public $user, $title, $completed, $content, $accepted, $date, $status;

    function __construct($user , $title, $completed, $content, $accepted, $date, $status) {
        $this -> user = $user;
        $this -> title = $title;
        $this -> completed = $completed;
        $this -> content = $content;
        $this -> accepted = $accepted;
        $this -> date = $date;
        $this -> status = $status;
    }

}



