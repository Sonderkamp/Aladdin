<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 29-5-2016
 * Time: 13:36
 */
class AdminQueryBuilder
{
    public function addAdmin($username, $password)
    {
        Database::query_safe("INSERT INTO `moderator`(`Username`, `Password`) VALUES (?,?)",
            array($username, $password));
    }

    public function getAdmin($username = null)
    {
        $query = "SELECT * FROM `moderator`";

        if ($username != null) {
            $query .= " WHERE `Username` = ? LIMIT 1";
            $param = array($username);
        }

        if (isset($param)) {
            return Database::query_safe($query, $param);
        } else {
            $query .= " ORDER BY `Username` ASC";
            return Database::query($query);
        }
    }

    public function changePassword($password, $oldUsername) {
            Database::query_safe("UPDATE `moderator` SET `Password`=? WHERE `Username` = ?",
                array($password, $oldUsername));
    }
    
    public function blockAdmin($username) {
        Database::query_safe("UPDATE `moderator` SET `IsActive`=0 WHERE `Username` = ?",
            array($username));
    }

    public function unblockAdmin($username) {
        Database::query_safe("UPDATE `moderator` SET `IsActive`=1 WHERE `Username` = ?",
            array($username));
    }
}