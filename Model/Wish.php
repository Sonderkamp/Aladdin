<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 26-Feb-16
 * Time: 13:17
 */
class Wish
{

    public $user, $title, $country, $city, $completed, $content, $accepted, $id;

    function __construct($user, $title, $country, $city, $completed, $content, $accepted, $id)
    {
        $this->user = $user;
        $this->title = $title;
        $this->country = $country;
        $this->city = $city;
        $this->completed = $completed;
        $this->content = $content;
        $this->accepted = $accepted;
        $this->id = $id;

    }

}



