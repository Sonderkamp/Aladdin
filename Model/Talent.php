<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:44
 */
class Talent
{
    public $id, $name, $creation_date, $acceptance_date, $is_rejected, $moderator_username, $user_email, $synonyms;

    public function __construct($id, $name, $creation_date, $acceptance_date, $is_rejected, $moderator_username, $user_email) {
        $this->id = $id;
        $this->name = $name;
        $this->creation_date = $creation_date;
        $this->acceptance_date = $acceptance_date;
        $this->is_rejected = $is_rejected;
        $this->moderator_username = $moderator_username;
        $this->user_email = $user_email;
        $this->synonyms = array();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addSynonym($id,$name) {

        array_push($this->synonyms, array(("id") => $id,("name") => $name));

//        $this->synonyms = usort($this->synonyms, function ($syno1, $syno2) {
//            return strcmp($syno1['id'], $syno2['name']);
//        });
    }

    public function removeSynonym($id) {

        $key = array_search($id, array_column($this->synonyms, 'id'));

        unset($this->synonyms[$key]);

        $this->synonyms = array_values($this->synonyms);
    }
}