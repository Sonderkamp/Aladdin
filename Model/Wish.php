<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */

class Wish{

    public $user, $name, $country, $city, $completed;

    function __construct($user , $name, $country, $city, $completed) {
        $this -> user = $user;
        $this -> name = $name;
        $this -> country = $country;
        $this -> city = $city;
        $this -> completed = $completed;
    }

}



