<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 6-3-2016
 * Time: 16:35
 */
class TalentRepository
{

    public $TALENT_MINIMUM = 3;

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
                array(htmlentities(ucfirst(strtolower(trim($name))),ENT_QUOTES), $_SESSION["user"]->email));
        }
    }

    public function addTalentToUser($id, $user = null)
    {

        if($user == null) {
            $user = $_SESSION["user"]->email;
        }

        Database::query_safe
        ("INSERT INTO `talent_has_user` (`talent_Id`, `user_Email`)
          VALUES (?, ?)",
            array($id, $user));
    }

    // Read
    public function getTalents($limit = null, $accepted = null, $not_added = null, $id = null, $current_user = null, $user_requested = null, $all_requested = null, $user = null) {

        $query = "SELECT `talent`.`Id`,
          `talent`.`Name`,
          `talent`.`CreationDate`,
          `talent`.`AcceptanceDate`,
          `talent`.`IsRejected`,
          `talent`.`moderator_Username`,
          `talent`.`user_Email`
          FROM `talent`";
        $suffix = " ORDER BY `talent`.`Name` ASC";

        if($limit != null) {

            $limit -= 1;
            $limit *= 10;
            $suffix .= " LIMIT ".$limit.",10";
        }

        if($accepted != null) {

            $query .= " WHERE `AcceptanceDate` IS NOT NULL AND `IsRejected` = 1 AND `IsRejected` IS NOT NULL AND `moderator_Username` IS NOT NULL";

            $result = Database::query($query.$suffix);

            return $this->createReturnArray($result);
        } else if($not_added != null) {

            $query .= " WHERE `talent`.`Id` NOT IN (SELECT `talent_Id` FROM `talent_has_user` WHERE `talent_has_user`.`user_Email` = ?) AND `talent`.`AcceptanceDate` IS NOT NULL AND `talent`.`IsRejected` = 1 AND `talent`.`IsRejected` IS NOT NULL AND `talent`.`moderator_Username` IS NOT NULL";

            $parameters = array($_SESSION["user"]->email);
        } else if($id != null) {

            $query .= " WHERE `talent`.`Id` = ?";
            $suffix = " LIMIT 1";

            $result = Database::query_safe($query.$suffix, array($id));

            return $this->createSingleTalent($result);
        } else if($current_user != null) {

            $query .= " JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id` JOIN `user` ON `talent_has_user`.`user_Email` = `user`.`Email` WHERE `user`.`Email` = ? AND `talent`.`IsRejected` = 1";

            $parameters = array($_SESSION["user"]->email);
        } else if($user_requested != null) {

            $query .= " WHERE `talent`.`AcceptanceDate` IS NULL AND `talent`.`user_Email` = ? AND `talent`.`IsRejected` IS NULL AND `talent`.`moderator_Username` IS NULL";

            $parameters = array($_SESSION["user"]->email);
        } else if($all_requested != null) {

            $query .= " WHERE `talent`.`AcceptanceDate` IS NULL AND `talent`.`IsRejected` IS NULL AND `talent`.`moderator_Username` IS NULL";

            $result = Database::query($query.$suffix);

            return $this->createReturnArray($result);
        } else if($user != null) {

            $query .= " INNER JOIN `talent_has_user` AS `tu` ON `t`.`Id` = `tu`.`talent_Id` WHERE `tu`.`user_Email` = ?";

            $parameters = array($user);
        }

        if(isset($parameters)) {

            $result = Database::query_safe($query.$suffix, $parameters);

            return $this->createReturnArray($result);
        } else {

            $result = Database::query($query.$suffix);

            return $this->createReturnArray($result);
        }
    }

    public function getNumberOfTalents($user = null, $user_accepted = null, $user_requested = null) {

        $query = "SELECT COUNT(`talent`.`Id`) AS `Number_of_talents` FROM `talent`";

        if($user != null) {

            $query .= " JOIN `talent_has_user` ON `talent`.`Id` = `talent_has_user`.`talent_Id` JOIN `user` ON `talent_has_user`.`user_Email` = `user`.`Email` WHERE `user`.`Email` = ? AND `talent`.`IsRejected` = 1";
        } else if($user_accepted != null) {

            $query .= " WHERE `talent`.`Id` NOT IN (SELECT `talent_Id` FROM `talent_has_user` WHERE `talent_has_user`.`user_Email` = ?) AND `talent`.`AcceptanceDate` IS NOT NULL AND `talent`.`IsRejected` = 1 AND `talent`.`IsRejected` IS NOT NULL AND `talent`.`moderator_Username` IS NOT NULL";
        } else if($user_requested != null) {

            $query .= " WHERE `talent`.`AcceptanceDate` IS NULL AND `talent`.`user_Email` = ? AND `talent`.`IsRejected` IS NULL AND `talent`.`moderator_Username` IS NULL";
        } else {

            $result = Database::query($query);

            return $result[0]["Number_of_talents"];
        }

        $result = Database::query_safe($query,array($_SESSION["user"]->email));

        return $result[0]["Number_of_talents"];
    }

    public function getSynonyms($talent_id = null) {

        $query = "SELECT * FROM `synonym`";

        if($talent_id != null) {

            $query .= " WHERE `talent_Id` = ?";

            $result = Database::query_safe($query,array($talent_id));

            return $this->createReturnArray($result,true);
        }

        return Database::query($query);
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

    // Helping methods
    public function createReturnArray($result, $synonym = null){

        $returnArray = array();

        if($synonym != null) {

            for ($i = 0; $i < count($result); $i++) {

                $returnArray[$i] = $result[$i]["synonym_Id"];
            }
        } else {

            for ($i = 0; $i < count($result); $i++) {

                $returnArray[$i] = new Talent(
                    $result[$i]["Id"],
                    $result[$i]["Name"],
                    $result[$i]["CreationDate"],
                    $result[$i]["AcceptanceDate"],
                    $result[$i]["IsRejected"],
                    $result[$i]["moderator_Username"],
                    $result[$i]["user_Email"],
                    $this->getSynonyms($result[$i]["Id"])
                );
            }
        }

        return $returnArray;
    }

    public function createSingleTalent($result) {

        $talent = new Talent(
            $result[0]["Id"],
            $result[0]["Name"],
            $result[0]["CreationDate"],
            $result[0]["AcceptanceDate"],
            $result[0]["IsRejected"],
            $result[0]["moderator_Username"],
            $result[0]["user_Email"],
            $this->getSynonyms($result[0]["Id"])
        );

        return $talent;
    }

//    public function getSynonymsOfTalents($talent)
//    {
//        $synoymID = array();
//
//        foreach ($talent as $item) {
//            if ($item instanceof Talent) {
//                $synoymID[] = $item->synonym_of;
//            }
//        }
//
//        $talents = $this->inCreator($synoymID);
//        $result = Database::query("select * from talent where Id IN $talents");
//
//        return $this->createReturnArray($result);
//    }

    // zorgt ervoor dat je een query kunt maken met een IN, zoals: where IN $list
    // bij het aanroepen van inCreator() geef je een lijst mee met bijvoorbeeld Id's
    // deze return dan tussen () de waardes gevolgd door een komma
//    public function inCreator($array)
//    {
//        $string = "(";
//        foreach ($array as $item) {
//            if ($item != null) {
//                $string .= $item . ",";
//            }
//        }
//        $value = substr($string, 0, -1);
//        $value .= ')';
//
//        return $value;
//    }
}