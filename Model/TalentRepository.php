<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 6-3-2016
 * Time: 16:35
 */
class TalentRepository
{
    public function getAcceptedWishes()
    {
        $result = Database::query
        ("SELECT *
          FROM `talent`
          WHERE `AcceptanceDate` IS NOT NULL
          AND `IsRejected` IS NOT FALSE
          AND `IsRejected` IS NOT NULL
          AND `moderator_Username` IS NOT NULL
          ORDER BY `talent`.`Name` ASC");

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {

            $returnArray[$i] = new Talent(
                $result[$i]["Id"],
                $result[$i]["Name"],
                $result[$i]["CreationDate"],
                $result[$i]["AcceptanceDate"],
                $result[$i]["IsRejected"],
                $result[$i]["moderator_Username"],
                $result[$i]["user_Email"]
            );
        }

        return $returnArray;
    }

    public function getTalentsWithoutAdded()
    {
        $result = Database::query_safe
        ("SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`
          FROM `talent`
          LEFT JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id`
          WHERE `talent`.`Id` NOT IN
              (SELECT `talent_Id`
              FROM `talent_has_user`
              WHERE `talent_has_user`.`user_Email` = ?)
          AND `talent`.`AcceptanceDate` IS NOT NULL
          AND `talent`.`IsRejected` IS NOT FALSE
          AND `talent`.`IsRejected` IS NOT NULL
          AND `talent`.`moderator_Username` IS NOT NULL
          ORDER BY `talent`.`Name` ASC",
            array($_SESSION["user"]->email));

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {

            $returnArray[$i] = new Talent(
                $result[$i]["Id"],
                $result[$i]["Name"],
                $result[$i]["CreationDate"],
                $result[$i]["AcceptanceDate"],
                $result[$i]["IsRejected"],
                $result[$i]["moderator_Username"],
                $result[$i]["user_Email"]
            );
        }

        return $returnArray;
    }

    public function getUserTalents()
    {
        //SELECT talent.Id, talent.Name, talent.CreationDate, talent.AcceptanceDate, talent.IsRejected, talent.moderator_Username, talent.user_Email FROM talent JOIN talent_has_user ON talent.Id = talent_has_user.talent_Id JOIN user ON talent_has_user.user_Email = user.Email WHERE user.Email = "joost@kolkhuistanke.nl"
        $result = Database::query_safe
        ("SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`
          FROM `talent`
          JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id`
          JOIN `user` ON `talent_has_user`.`user_Email` = `user`.`Email`
          WHERE `user`.`Email` = ?
          ORDER BY `talent`.`Name` ASC",
        array($_SESSION["user"]->email));

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {

            $returnArray[$i] = new Talent(
                $result[$i]["Id"],
                $result[$i]["Name"],
                $result[$i]["CreationDate"],
                $result[$i]["AcceptanceDate"],
                $result[$i]["IsRejected"],
                $result[$i]["moderator_Username"],
                $result[$i]["user_Email"]
            );
        }

        return $returnArray;
    }

    public function deleteTalentFromUser($id)
    {
        Database::query_safe
        ("DELETE FROM `talent_has_user`
          WHERE `talent_has_user`.`talent_Id` = ?
          AND `talent_has_user`.`user_Email` = ?",
            array($id,$_SESSION["user"]->email));
    }

    public function addTalentToUser($id)
    {
        Database::query_safe
        ("INSERT INTO `talent_has_user` (`talent_Id`, `user_Email`)
          VALUES (?, ?)",
            array($id,$_SESSION["user"]->email));
    }

    public function checkNumberOfTalents()
    {
        $result = Database::query_safe
        ("SELECT COUNT(`user_Email`) AS `Number_of_talents`
          FROM `talent_has_user`
          WHERE `user_Email` = ?",
            array($_SESSION["user"]->email));
        return $result[0]["Number_of_talents"];
    }
}