<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-3-2016
 * Time: 00:05
 */
class Admin
{

    public $username, $creationDate, $isActive;

    public function __construct($username, $creationDate, $isActive)
    {
        $this->username = $username;
        $this->creationDate = $creationDate;
        $this->isActive = $isActive;
    }
}