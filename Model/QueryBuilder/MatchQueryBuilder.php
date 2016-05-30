<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 29/05/2016
 * Time: 22:44
 */
class MatchQueryBuilder extends QueryBuilder
{
    public function getMatches($wishId){
        $query = "SELECT * FROM `matches`
                  JOIN `user` ON `matches`.user_Email = `user`.Email
                  WHERE `matches`.wish_Id = ? AND user_Email IS NOT NULL AND NOT EXISTS
                  ( SELECT NULL FROM blockedusers AS b WHERE b.user_Email = `matches`.user_Email AND b.IsBlocked = 1
                  AND b.Id = (SELECT Id FROM blockedusers as c WHERE c.user_Email = `matches`.user_Email
                  ORDER BY DateBlocked DESC LIMIT 1))";

        return $this->executeQuery($query , array($wishId));
    }

    public function addMatch($wishId , $username){
        $query = "INSERT INTO `matches` (`wish_Id`, `user_Email`, `IsActive`, `IsSelected`) VALUES (?, ?, 1, 0);";
        return $this->executeQuery($query , array($wishId , $username));
    }

    public function checkForUser($username){
        $query = "SELECT * FROM `matches` WHERE ? = $username";
        return $this->executeQuery($query , array($username));
    }
}