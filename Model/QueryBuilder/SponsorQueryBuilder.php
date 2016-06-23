<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 04-06-16
 * Time: 16:43
 */
class SponsorQueryBuilder
{

    public function getAllSponsors($keyword = null)
    {
        $sql = "SELECT * FROM `sponsor` as s";

        if ($keyword != null) {
            $sql .= " WHERE s.Name LIKE ? 
                  OR s.WebsiteLink LIKE ?
                  OR s.user_Email LIKE ?
                  OR s.Description LIKE ?";
            $keyword = preg_replace('/\s+/', '', "%" . $keyword . "%");
            $parameters = array($keyword, $keyword, $keyword, $keyword);
        }

        if (isset($parameters)) {
            return Database::query_safe($sql, $parameters);
        } else {
            return Database::query($sql);
        }
    }


    public function addSponsor(Sponsor $sponsor)
    {
        if ($sponsor == null) return;
        $sql = "INSERT INTO `sponsor`";
        $sql .= " (`Name`, `Image`, `Description`, `WebsiteLink`, `user_Email`)";
        $sql .= " VALUES (?,?,?,?,?)";
        $parameters = array($sponsor->name, $sponsor->image, $sponsor->description, $sponsor->url, $sponsor->userMail);
        Database::query_safe($sql, $parameters);
    }

    public function updateSponsor(Sponsor $sponsor)
    {
        if ($sponsor == null) return;
        if ($sponsor->image == null) {
            $sql = "UPDATE `sponsor` SET `Name` = ?,`Description`= ?,`WebsiteLink`= ?,`user_Email` = ? WHERE Id = ?";
            if ($sponsor->userMail == null) {
                $parameters = array($sponsor->name, $sponsor->description, $sponsor->url, null, $sponsor->id);
            } else {
                $parameters = array($sponsor->name, $sponsor->description, $sponsor->url, $sponsor->userMail, $sponsor->id);
            }
        } else {
            $sql = "UPDATE `sponsor` SET `Name` = ?,`Image`= ?,`Description`= ?,`WebsiteLink`= ?,`user_Email` = ? WHERE Id = ?";
            if ($sponsor->userMail == null) {
                $parameters = array($sponsor->name, $sponsor->image, $sponsor->description, $sponsor->url, null, $sponsor->id);
            } else {
                $parameters = array($sponsor->name, $sponsor->image, $sponsor->description, $sponsor->url, $sponsor->userMail, $sponsor->id);
            }
        }

        Database::query_safe($sql, $parameters);
    }

    public function deleteSponsor(Sponsor $sponsor)
    {
        if ($sponsor == null) return;
        $sql = "DELETE FROM `sponsor` WHERE `Id` = ?";
        Database::query_safe($sql, array($sponsor->id));
    }

}