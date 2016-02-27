<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */

class Wish{

    public $user, $name, $country, $city;

    function __construct($user , $name, $country, $city) {
        $this -> user = $user;
        $this -> name = $name;
        $this -> country = $country;
        $this -> city = $city;
    }

}



