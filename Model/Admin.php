<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-3-2016
 * Time: 00:05
 */
class Admin
{

    public $username, $creationDate;

    public function __construct($username, $creationDate)
    {
        $this->username = $username;
        $this->creationDate = $creationDate;
    }
}