<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 12-5-2016
 * Time: 12:39
 */
class TalentQueryBuilder
{
    // Create
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
                array(htmlentities(ucfirst(strtolower(trim($name))), ENT_QUOTES), $_SESSION["user"]->email));
        }
    }

    public function addTalentToUser($id, $user = null)
    {

        if ($user == null) {
            $user = $_SESSION["user"]->email;
        }

        Database::query_safe
        ("INSERT INTO `talent_has_user` (`talent_Id`, `user_Email`)
          VALUES (?, ?)",
            array($id, $user));
    }

    public function addSynonym($talentId, $synonymId)
    {

        $talent = $this->getTalents(null, null, null, $talentId)[0];
        $synonym = $this->getTalents(null, null, null, $synonymId)[0];


        if ($talent["IsRejected"] === 1 && $synonym["IsRejected"] === 1) {

            Database::query_safe
            ("INSERT INTO `synonym`(`talent_Id`, `synonym_Id`) VALUES (?,?)",
                array($talentId, $synonymId));

            Database::query_safe
            ("INSERT INTO `synonym`(`talent_Id`, `synonym_Id`) VALUES (?,?)",
                array($synonymId, $talentId));
        }
    }

    // Read
    public function getTalents($limit = null, $accepted = null, $notAdded = null, $id = null, $currentUser = null, $userRequested = null, $allRequested = null, $user = null, $nameOnly = null, $search = null)
    {

        if ($nameOnly != null) {

            $query = "SELECT `talent`.`Name` FROM `talent`";
        } else {

            $query = "SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`
          FROM `talent`";
        }
        $suffix = " ORDER BY `talent`.`Name` ASC";

        if ($limit != null) {

            $limit -= 1;
            $limit *= 10;
            $suffix .= " LIMIT " . $limit . ",10";
        }

        if ($accepted != null) {

            $query .= " WHERE `AcceptanceDate` IS NOT NULL AND `IsRejected` = 1 AND `IsRejected` IS NOT NULL AND `moderator_Username` IS NOT NULL";

            $where = "on";
        } else if ($notAdded != null) {

            $query .= " WHERE `talent`.`Id` NOT IN (SELECT `talent_Id` FROM `talent_has_user` WHERE `talent_has_user`.`user_Email` = ?) AND `talent`.`AcceptanceDate` IS NOT NULL AND `talent`.`IsRejected` = 1 AND `talent`.`IsRejected` IS NOT NULL AND `talent`.`moderator_Username` IS NOT NULL";

            $where = "on";
            $parameters = array($_SESSION["user"]->email);
        } else if ($id != null) {

            $query .= " WHERE `talent`.`Id` = ?";
            $suffix = " LIMIT 1";

            $where = "on";
            $parameters = array($id);
        } else if ($currentUser != null) {

            $query .= " JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id` JOIN `user` ON `talent_has_user`.`user_Email` = `user`.`Email` WHERE `user`.`Email` = ? AND `talent`.`IsRejected` = 1";

            $where = "on";
            $parameters = array($_SESSION["user"]->email);
        } else if ($userRequested != null) {

            $query .= " WHERE `talent`.`AcceptanceDate` IS NULL AND `talent`.`user_Email` = ? AND `talent`.`IsRejected` IS NULL AND `talent`.`moderator_Username` IS NULL";

            $where = "on";
            $parameters = array($_SESSION["user"]->email);
        } else if ($allRequested != null) {

            $query .= " WHERE `talent`.`AcceptanceDate` IS NULL AND `talent`.`IsRejected` IS NULL AND `talent`.`moderator_Username` IS NULL";

            $where = "on";
        } else if ($user != null) {

            $query .= " INNER JOIN `talent_has_user` AS `tu` ON `t`.`Id` = `tu`.`talent_Id` WHERE `tu`.`user_Email` = ?";

            $where = "on";
            $parameters = array($user);
        }

        if ($search != null) {

            if (Isset($where)) {

                $query .= " AND `talent`.`name` LIKE ?";

                array_push($parameters, "%" . $search . "%");
            } else {

                $query .= " WHERE `talent`.`name` LIKE ?";

                $parameters = array("%" . $search . "%");
            }
        }

        if (isset($parameters)) {

            $result = Database::query_safe($query . $suffix, $parameters);
        } else {

            $result = Database::query($query . $suffix);
        }

        return $result;
    }

    public function getSynonyms($talentId = null)
    {

        $query = "SELECT * FROM `synonym`";

        if ($talentId != null) {
            $query .= " WHERE `talent_Id` = ?";
            $result = Database::query_safe($query, array($talentId));
        } else {
            $result = Database::query($query);
        }

        return $result;
    }


    /** parameter is a list of talent objects */
    public function getSynonymsOfTalents($talent)
    {
        $talentList = $this->getAllID($talent);
        $talents = $this->getSQLString($talentList);
        $allTalents = $this->getMatchTalents($talents);

        $sql = "SELECT * FROM talent WHERE Id IN $allTalents";
        $result = Database::query($sql);

        return $result;
    }

    public function getAllID($talents){
        $talentList[] = array();

        foreach ($talents as $item) {
            if ($item instanceof Talent) {
                $talentList[] = $item->getId();
            }
        }

        return $talentList;
    }


    public function getMatchTalents($talents){
        $id = array();
        $total = 0;
        $skip = true;
        $loopCounter = 0;
        $maxLoops = 5;

        while (true) {
            if($loopCounter >= $maxLoops){
                break;
            }
            $result = Database::query("SELECT * FROM synonym WHERE talent_Id IN $talents");

            if (count($result) > 0) {
                foreach ($result as $item) {
                    if (!in_array($item["synonym_Id"], $id)) {
                        $id[] = $item["synonym_Id"];
                    }
                }

                $talents = $this->getSQLString($id);

                if (!$skip) {
                    $temp = count($id);
                    if ($temp == $total) {
                        break;
                    }
                } else {
                    $skip = false;
                }

                $total = count($id);
            } else {
                break;
            }
            $loopCounter++;
        }

        return $talents;
    }


    // Update
    public function updateTalent($name, $isRejected, $id)
    {
        if (!preg_match('/[^a-z\s]/i', $name)) {

            if ($isRejected == 1) {

                Database::query_safe("
                  UPDATE `talent`
                  SET `Name`=?,`IsRejected`=?,`moderator_Username`=?,`AcceptanceDate`=CURRENT_TIMESTAMP
                  WHERE `Id`=?",
                    Array($name, $isRejected, $_SESSION["admin"]->username, $id));
            } else {

                Database::query_safe("
                  UPDATE `talent`
                  SET `Name`=?,`IsRejected`=?,`moderator_Username`=?,`AcceptanceDate`=NULL
                  WHERE `Id`=?",
                    Array($name, $isRejected, $_SESSION["admin"]->username, $id));

                Database::query_safe("DELETE FROM `synonym` WHERE `talent_Id` = ?",
                    array($id));

                Database::query_safe("DELETE FROM `synonym` WHERE `synonym_Id` = ?",
                    array($id));
            }
        }
    }

    // Delete
    public function deleteTalentFromUser($id)
    {

        Database::query_safe("
          DELETE FROM `talent_has_user`
          WHERE `talent_has_user`.`talent_Id` = ?
          AND `talent_has_user`.`user_Email` = ?",
            array($id, $_SESSION["user"]->email));
    }

    public function deleteSynonym($talentId, $synonymId)
    {

        Database::query_safe("DELETE FROM `synonym` WHERE `talent_Id` = ? AND `synonym_Id` = ?",
            array($talentId, $synonymId));

        Database::query_safe("DELETE FROM `synonym` WHERE `talent_Id` = ? AND `synonym_Id` = ?",
            array($synonymId, $talentId));
    }

    public function getWishTalents(Wish $wish)
    {
        $query = "SELECT `talent_id` as talent FROM `talent_has_wish` WHERE `wish_id`=?";
        $array = array($wish->id);
        $result = Database::query_safe($query, $array);

        $talentIDArray = array();
        foreach ($result as $item) {
            $talentIDArray[] = $item["talent"];
        }

        $SQLString = $this->getSQLString($talentIDArray);

        $sql = "SELECT * FROM `talent` WHERE `Id` IN $SQLString";
        return Database::query($sql);

//        $names = Database::query($sql);

//        $returnArray = array();
//        for ($i = 0; $i < count($names); $i++) {
//            $returnArray[] = $names[$i]["Name"];
//        }
//        return $returnArray;
    }

    // Helping methods

    // zorgt ervoor dat je een query kunt maken met een IN, zoals: where IN $list
    // bij het aanroepen van inCreator() geef je een lijst mee met bijvoorbeeld Id's
    // deze return dan tussen () de waardes gevolgd door een komma
    public function getSQLString($array)
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