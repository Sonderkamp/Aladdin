<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:44
 */
class Talent
{
    public $id, $name, $creation_date, $acceptance_date, $is_rejected, $moderator_username, $user_email, $synonym_of, $synonym_name;

    public function __construct($id, $name, $creation_date, $acceptance_date, $is_rejected, $moderator_username, $user_email, $synonym_of) {
        $this->id = $id;
        $this->name = $name;
        $this->creation_date = $creation_date;
        $this->acceptance_date = $acceptance_date;
        $this->is_rejected = $is_rejected;
        $this->moderator_username = $moderator_username;
        $this->user_email = $user_email;
        $this->synonym_of = $synonym_of;
    }
    
    public function setSynonymName($name){
        $this->synonym_name = $name;
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