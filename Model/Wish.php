<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */
class Wish
{

    public $id, $user, $title, $completed, $content, $accepted, $date, $status;

    public function __construct($id, $user , $title, $completed, $content, $accepted, $date, $status) {
        $this -> id = $id;
        $this -> user = $user;
        $this -> title = $title;
        $this -> completed = $completed;
        $this -> content = $content;
        $this -> accepted = $accepted;
        $this -> date = $date;
        $this -> status = $status;

    }

}



