<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:44
 */
class Talent
{
    public $user, $talent;

    public function __construct($user , $talent) {
        $this -> user = $user;
        $this -> talent = $talent;
    }
}