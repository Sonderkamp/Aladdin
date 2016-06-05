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
        return Database::query("SELECT * FROM `sponsor`");
    }


    public function addSponsor(Sponsor $sponsor)
    {
        $sql = "INSERT INTO `aladdin_db2`.`sponsor`";
        $sql .= " (`Name`, `Image`, `Description`, `WebsiteLink`, `user_Email`)";
        $sql .= " VALUES (?,?,?,?,?)";
        $parameters = array($sponsor->name, $sponsor->image, $sponsor->description, $sponsor->url, $sponsor->userMail);
        Database::query_safe($sql, $parameters);
    }

    public function deleteSponsor(Sponsor $sponsor){
        $sql = "DELETE FROM `aladdin_db2`.`sponsor` WHERE `Id` = ?";
        Database::query_safe($sql, array($sponsor->id));
    }
    
}