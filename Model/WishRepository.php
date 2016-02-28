<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{
    public $wishes;

    function __construct($wishes) {
        $this -> wishes = $wishes;
    }
}