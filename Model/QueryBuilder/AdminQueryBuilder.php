<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 29-5-2016
 * Time: 13:36
 */
class AdminQueryBuilder
{

    public function getAdmin($username = null) {
        $query = "SELECT * FROM `moderator`";

        if($username != null) {
            $query .= " WHERE `Username` = ? LIMIT 1";
            $param = array($username);
        }

        if(isset($param)) {
            return Database::query_safe($query,$param);
        } else {
            $query .= " ORDER BY `Username` ASC";
            return Database::query($query);
        }
    }
}