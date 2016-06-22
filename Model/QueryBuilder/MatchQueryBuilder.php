<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 29/05/2016
 * Time: 22:44
 */
class MatchQueryBuilder extends QueryBuilder
{
    /**
     * @param null $wishId
     * @param null $username
     * @return array|bool
     *
     * provide $wishId for matches of that wish
     * provide $username for completed matches by that username
     *
     * do not provide both since this will result in an error
     * Neither provide none cause this will cause error as well
     */
    public function getMatches($wishId = null , $username = null, $wishUser = false){

        $param = array();

        if(($wishId != null && $username != null) || ($wishId == null && $username == null)){
            return null;
        }

        $query = "SELECT *, `matches`.IsActive as matchIsActive FROM `matches` ";

        if($username != null){
            $query .= "JOIN `wish` ON `matches`.wish_Id = `wish`.Id ";
            $query .= "JOIN `wishContent` ON `wish`.Id = `wishContent`.wish_Id ";
        }

        if($wishUser){
            $query .= "JOIN `user` ON `wish`.User = `user`.Email ";
        } else {
            $query .= "JOIN `user` ON `matches`.user_Email = `user`.Email ";
        }

        if($wishId != null){
            $query .= "WHERE `matches`.wish_Id = ? ";
            $param[] = $wishId;
        }

        if($username != null){
            $query .= "WHERE `matches`.user_Email = ? AND `wish`.Status = 'Vervuld' ";
            $param[] = $username;
        }

        $query .= "AND user_Email IS NOT NULL AND NOT EXISTS
                  ( SELECT NULL FROM blockedUsers AS b WHERE b.user_Email = `matches`.user_Email AND b.IsBlocked = 1
                  AND b.Id = (SELECT Id FROM blockedUsers as c WHERE c.user_Email = `matches`.user_Email
                  ORDER BY DateBlocked DESC LIMIT 1))";

        return $this->executeQuery($query , $param);
    }

    public function addMatch($wishId , $username){
        $query = "INSERT INTO `matches` (`wish_Id`, `user_Email`, `IsActive`, `IsSelected`) VALUES (?, ?, 1, 0);";
        $res = $this->executeQuery($query , array($wishId , $username));

        if($res === false) {
            $query = "UPDATE `matches` SET `IsActive` = 1 WHERE `wish_Id` = ? AND `user_Email` = ?";
            $res = $this->executeQuery($query , array($wishId , $username));
        }

        return $res;
    }

    public function checkForUser($username , $wishId){
        $query = "SELECT * FROM `matches` WHERE `matches`.user_Email = ? AND `matches`.wish_Id = ? AND `matches`.IsActive = 1";
        return $this->executeQuery($query , array($username, $wishId));
    }

    public function clearSelected($wishId){
        $query = "UPDATE `matches` SET IsSelected = 0 WHERE `matches`.wish_Id = ?";
        $this->executeQuery($query , array($wishId));
    }

    public function checkOwnWish($username, $wishId){
        $query = "SELECT * FROM `wish` WHERE `wish`.User = ? AND `wish`.Id = ?";
        return $this->executeQuery($query , array($username , $wishId));
    }

    public function selectMatch($wishId , $username){
        $query = "UPDATE `matches` SET IsSelected = 1 WHERE wish_Id = ? AND user_Email = ?; ";

        $this->executeQuery($query , array($wishId , $username));

        $query = "UPDATE `wish` SET Status = 'Match gevonden' WHERE `wish`.Id = ?;";

        $this->executeQuery($query , array($wishId));
    }
}