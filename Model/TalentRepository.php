<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 6-3-2016
 * Time: 16:35
 */
class TalentRepository
{
    public function getAllTalentsName()
    {
        $result = Database::query
        ("SELECT `talent`.`Name`
          FROM `talent`");

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {
            $returnArray[$i] = $result[$i]["Name"];
        }

        return $returnArray;
    }

    public function getAllTalents($value)
    {
        $result = null;
        if ($value === false) {
            $result = Database::query
            ("SELECT *
          FROM `talent`
          ORDER BY `talent`.`Name` ASC");
        } else {
            $value -= 1;
            $value *= 10;
            $result = Database::query
            ("SELECT *
          FROM `talent`
          ORDER BY `talent`.`Name` ASC
          LIMIT $value,10");


        }
        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {

            $talent = new Talent(
                $result[$i]["Id"],
                $result[$i]["Name"],
                $result[$i]["CreationDate"],
                $result[$i]["AcceptanceDate"],
                $result[$i]["IsRejected"],
                $result[$i]["moderator_Username"],
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );

            if (!empty($talent->synonym_of)) {
                $talent->synonym_name = $this->getTalentById($talent->synonym_of)->name;
            }

            $returnArray[$i] = $talent;
        }

        return $returnArray;
    }

    public function getAcceptedTalents()
    {
        $result = Database::query
        ("SELECT *
          FROM `talent`
          WHERE `AcceptanceDate` IS NOT NULL
          AND `IsRejected` = 1
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
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
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
          `talent`.`user_Email`,
          `talent`.`synonym_of`
          FROM `talent`
          WHERE `talent`.`Id` NOT IN
              (SELECT `talent_Id`
              FROM `talent_has_user`
              WHERE `talent_has_user`.`user_Email` = ?)
          AND `talent`.`AcceptanceDate` IS NOT NULL
          AND `talent`.`IsRejected` = 1
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
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );
        }

        return $returnArray;
    }

    public function getSelectionTalents($value)
    {
        $value -= 1;
        $value *= 10;
        $result = Database::query_safe
        ("SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`,
          `talent`.`synonym_of`
          FROM `talent`
          WHERE `talent`.`Id` NOT IN
              (SELECT `talent_Id`
              FROM `talent_has_user`
              WHERE `talent_has_user`.`user_Email` = ?)
          AND `talent`.`AcceptanceDate` IS NOT NULL
          AND `talent`.`IsRejected` = 1
          AND `talent`.`IsRejected` IS NOT NULL
          AND `talent`.`moderator_Username` IS NOT NULL
          ORDER BY `talent`.`Name` ASC
          LIMIT $value,10",
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
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );
        }

        return $returnArray;
    }

    public function getTalentById($id)
    {
        $result = Database::query_safe
        ("SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`,
          `talent`.`synonym_of`
          FROM `talent`
          LEFT JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id`
          WHERE `talent`.`Id` = ?
          LIMIT 1",
            array($id));

        $talent = new Talent(
            $result[0]["Id"],
            $result[0]["Name"],
            $result[0]["CreationDate"],
            $result[0]["AcceptanceDate"],
            $result[0]["IsRejected"],
            $result[0]["moderator_Username"],
            $result[0]["user_Email"],
            $result[0]["synonym_of"]
        );

        return $talent;
    }

    public function getSelectionUserTalents($value)
    {
        $value -= 1;
        $value *= 10;
        $result = Database::query_safe
        ("SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`,
          `talent`.`synonym_of`
          FROM `talent`
          JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id`
          JOIN `user` ON `talent_has_user`.`user_Email` = `user`.`Email`
          WHERE `user`.`Email` = ?
          AND `talent`.`IsRejected` = 1
          ORDER BY `talent`.`Name` ASC
          LIMIT $value,10",
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
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );
        }

        return $returnArray;
    }

    public function getUserTalents()
    {
        $result = Database::query_safe
        ("SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`,
          `talent`.`synonym_of`
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
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );
        }

        return $returnArray;
    }

    public function getRequestedTalents($value)
    {
        $value -= 1;
        $value *= 10;
        $result = Database::query_safe
        ("SELECT  `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`,
          `talent`.`synonym_of`
          FROM `talent`
          WHERE `talent`.`AcceptanceDate` IS NULL
          AND `talent`.`user_Email` = ?
          AND `talent`.`IsRejected` IS NULL
          AND `talent`.`moderator_Username` IS NULL
          ORDER BY `talent`.`Name` ASC
          LIMIT $value,10",
            Array($_SESSION["user"]->email));

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {

            $returnArray[$i] = new Talent(
                $result[$i]["Id"],
                $result[$i]["Name"],
                $result[$i]["CreationDate"],
                $result[$i]["AcceptanceDate"],
                $result[$i]["IsRejected"],
                $result[$i]["moderator_Username"],
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );
        }

        return $returnArray;
    }

    public function getAllRequestedTalents()
    {
        $result = Database::query
        ("SELECT  `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`,
          `talent`.`synonym_of`
          FROM `talent`
          WHERE `talent`.`AcceptanceDate` IS NULL
          AND `talent`.`IsRejected` IS NULL
          AND `talent`.`moderator_Username` IS NULL
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
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
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
            array($id, $_SESSION["user"]->email));
    }

    public function addTalentToUser($id)
    {
        Database::query_safe
        ("INSERT INTO `talent_has_user` (`talent_Id`, `user_Email`)
          VALUES (?, ?)",
            array($id, $_SESSION["user"]->email));
    }

    public function addTalentToUser2($id, $user)
    {
        Database::query_safe
        ("INSERT INTO `talent_has_user` (`talent_Id`, `user_Email`)
          VALUES (?, ?)",
            array($id, $user));
    }

    public function addTalent($name)
    {
        if (!Empty(trim($name)) || trim($name) != "" || preg_match('/[^a-z\s]/i', $name)) {
            Database::query_safe
            ("INSERT INTO `talent` (`Name`,
                                    `CreationDate`,
                                    `AcceptanceDate`,
                                    `IsRejected`,
                                    `moderator_Username`,
                                    `user_Email`)
          VALUES (?, CURRENT_TIMESTAMP, NULL, NULL, NULL, ?)",
                array(ucfirst(strtolower(trim($name))), $_SESSION["user"]->email));
        }
    }

    public function checkNumberOfTalentsFromUser()
    {
        $result = Database::query_safe
        ("SELECT COUNT(`user_Email`) AS `Number_of_talents`
          FROM `talent_has_user`
          WHERE `user_Email` = ?",
            array($_SESSION["user"]->email));
        return $result[0]["Number_of_talents"];
    }

    public function checkNumberOfAllTalents()
    {
        $result = Database::query
        ("SELECT COUNT(`talent`.`Id`) AS `Number_of_talents`
          FROM `talent`");
        return $result[0]["Number_of_talents"];
    }

    public function checkNumberOfTalents()
    {
        $result = Database::query_safe
        ("SELECT COUNT(`talent`.`Id`) AS `Number_of_talents`
          FROM `talent`
          WHERE `talent`.`Id` NOT IN
              (SELECT `talent_Id`
              FROM `talent_has_user`
              WHERE `talent_has_user`.`user_Email` = ?)
          AND `talent`.`AcceptanceDate` IS NOT NULL
          AND `talent`.`IsRejected` = 1
          AND `talent`.`IsRejected` IS NOT NULL
          AND `talent`.`moderator_Username` IS NOT NULL
          ORDER BY `talent`.`Name` ASC",
            array($_SESSION["user"]->email));
        return $result[0]["Number_of_talents"];
    }

    public function checkNumberOfRequestedTalents()
    {
        $result = Database::query_safe
        ("SELECT  COUNT(`talent`.`Id`) AS `Number_of_talents`
          FROM `talent`
          WHERE `talent`.`AcceptanceDate` IS NULL
          AND `talent`.`user_Email` = ?
          AND `talent`.`IsRejected` IS NULL
          AND `talent`.`moderator_Username` IS NULL
          ORDER BY `talent`.`Name` ASC",
            Array($_SESSION["user"]->email));
        return $result[0]["Number_of_talents"];
    }

    public function updateTalent($name, $isRejected, $synonym, $id)
    {
        if (!preg_match('/[^a-z\s]/i', $name)) {
            $reject = $this->getTalentById($id)->is_rejected;
            if ($reject == $isRejected) {
                Database::query_safe
                ("UPDATE `talent` 
                  SET `Name`=?,`synonym_of`=? 
                  WHERE `Id`=?",
                    Array($name, $synonym, $id));
            } else {
                if ($isRejected == 1) {
                    Database::query_safe
                    ("UPDATE `talent` 
                      SET `Name`=?,`IsRejected`=?,`moderator_Username`=?,`AcceptanceDate`=CURRENT_TIMESTAMP,`synonym_of`=? 
                      WHERE `Id`=?",
                        Array($name, $isRejected, $_SESSION["admin"]->username, $synonym, $id));
                } else {
                    Database::query_safe
                    ("UPDATE `talent` 
                      SET `Name`=?,`IsRejected`=?,`moderator_Username`=?,`AcceptanceDate`=NULL,`synonym_of`=? 
                      WHERE `Id`=?",
                        Array($name, $isRejected, $_SESSION["admin"]->username, $synonym, $id));
                }
            }
        }
    }

    public function rejectTalent($id)
    {
        Database::query_safe
        ("UPDATE `talent`
          SET `IsRejected`=?,
          `moderator_Username`=?
          WHERE Id = ?",
            Array(0, $_SESSION["admin"]->username, $id));
    }

    public function acceptTalent($id)
    {
        Database::query_safe
        ("UPDATE `talent`
          SET `AcceptanceDate`=CURRENT_TIMESTAMP,
          `IsRejected`=?,
          `moderator_Username`=?
          WHERE `Id`=?
        ",
            Array(1, $_SESSION["admin"]->username, $id));
    }

    public function getSelectedUserTalents($user)
    {
        $result = Database::query_safe
        ("select Name as name
          from talent as t
          inner join talent_has_user as tu on t.Id = tu.talent_Id
          where tu.user_Email = ?", array($user));

        return $result;
    }

    public function getSynonymsOfTalents($talent)
    {
        $synoymID = array();
        
        foreach ($talent as $item) {
            if ($item instanceof Talent) {
                $synoymID[] = $item->synonym_of;
            }
        }

        $talents = $this->inCreator($synoymID);
        $result = Database::query("select * from talent where Id IN $talents");
        return $this->createTalentObject($result);
    }

    public function createTalentObject($result){
        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {

            $returnArray[$i] = new Talent(
                $result[$i]["Id"],
                $result[$i]["Name"],
                $result[$i]["CreationDate"],
                $result[$i]["AcceptanceDate"],
                $result[$i]["IsRejected"],
                $result[$i]["moderator_Username"],
                $result[$i]["user_Email"],
                $result[$i]["synonym_of"]
            );
        }

        return $returnArray;
    }

    // zorgt ervoor dat je een query kunt maken met een IN, zoals: where IN $list
    // bij het aanroepen van inCreator() geef je een lijst mee met bijvoorbeeld Id's
    // deze return dan tussen () de waardes gevolgd door een komma
    public function inCreator($array)
    {
        $string = "(";
        foreach ($array as $item) {
            if ($item != null) {
                $string .= $item . ",";
            }
        }
        $value = substr($string, 0, -1);
        $value .= ')';

        return $value;
    }

}