<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 04-06-16
 * Time: 16:43
 */
class SponsorQueryBuilder
{
    
    public function getAllSponsors()
    {
        return Database::query($this->selectAll());
    }


    public function addSponsor(Sponsor $sponsor)
    {
        $sql = "INSERT INTO `aladdin_db2`.`sponsor`";
        if(isset($sponsor->userMail)){
            $sql .= " (`Name`, `Image`, `Description`, `WebsiteLink`, `user_Email`)";
            $sql .= " VALUES (?,?,?,?,?)";
            $parameters = array($sponsor->name, $sponsor->image, $sponsor->description, $sponsor->url, $sponsor->userMail);
        } else {
            $sql .= " (`Name`, `Image`, `Description`, `WebsiteLink`)";
            $sql .= " VALUES (?,?,?,?)";
            $parameters = array($sponsor->name, $sponsor->image, $sponsor->description, $sponsor->url);
        }


        Database::query_safe($sql, $parameters);
    }

    public function selectAll(){
        return "SELECT * FROM `sponsor`";
    }


}