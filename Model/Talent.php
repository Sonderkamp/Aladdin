<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:44
 */
class Talent
{
    public $id, $name, $creation_date, $acceptance_date, $is_rejected, $moderator_username, $user_email;

    public function __construct($id, $name, $creation_date, $acceptance_date, $is_rejected, $moderator_username, $user_email) {
        $this -> id = $id;
        $this -> name = $name;
        $this -> creation_date = $creation_date;
        $this -> acceptance_date = $acceptance_date;
        $this -> is_rejected = $is_rejected;
        $this -> moderator_username = $moderator_username;
        $this -> user_email = $user_email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }
}