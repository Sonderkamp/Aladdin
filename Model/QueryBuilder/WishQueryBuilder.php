<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 13-May-16
 * Time: 11:24
 */
class WishQueryBuilder
{


    /**
     * @param $query
     * @param null $params
     * @return array|bool
     *
     * if params is not empty will execute safe query. Otherwise regular query
     */
    private function executeQuery($query, array $params){
        if(!empty($params)){
            return Database::query_safe($query , $params);
        } else {
            return Database::query($query);
        }
    }

    /**
     * @param null $user
     * @param array|null $status
     * @param null $searchKey
     * @return array|bool
     *
     * Used in:
     * completed wishes
     * my completed wishes
     * incompleted wishes
     * my wishes
     * search my wishes
     * search completed wishes
     * search incompleted wishes
     *
     * All gets from admin
     *
     */
    public function getWishes($user = null, array $status = null, $searchKey = null, $admin = false)
    {
        $query = "SELECT *
                  FROM `wish`
                  LEFT JOIN `wishContent`
                  ON `wish`.Id = `wishContent`.wish_Id
                  JOIN `user` ON `wish`.User = `user`.Email
                  WHERE `wish`.User IS NOT NULL AND
                  NOT EXISTS(SELECT NULL FROM blockedusers AS b WHERE b.user_Email = `wish`.User AND b.IsBlocked = 1) AND ";

        //Used in queries by User
        if($user != null){
            $query .= "User = ? AND ";
        }


        //Used in queries by status
        if($status != null){
            $query .= "(";
            foreach($status as $item){
                $query .= "(Status = '" . $item . "'";
                if($item == "Aangemaakt"){
                    $query .= ")";
                } else{
                    $query .= " AND `wishContent`.`IsAccepted` = ";
                    if($admin){
                        $query .= "0)";
                    } else {
                        $query .= "1)";
                    }
                }
                $query .= " OR ";

            }
            $query = substr_replace($query, '', -3);
            $query .= ")";
        }

        //Used in searching wish
        if($searchKey != null){
            if($status != null){
                $query .= " AND ";
            }

            $query .= "wishContent.Content
                        SOUNDS LIKE ?
                        OR wishContent.Title
                        SOUNDS LIKE ? ";
        }

        if($admin && $status == null){
            $query = substr_replace($query, '', -3);
        }

        $query .= "GROUP BY `wish`.Id";

        //acquire params if any
        $params = array();

        if($user != null){
            $params[] = $user;
        }

        if($searchKey != null){
            $params[] = $searchKey;
        }

        return $this->executeQuery($query , $params);
    }

    public function getSingleWish($wishId, $admin = false)
    {
        $query = "SELECT * FROM `wish` LEFT JOIN `wishContent`
                        ON `wish`.Id = `wishContent`.wish_Id";
        $query .= "WHERE `wish`.Id = ? AND `wishContent`.`IsAccepted` = ";

        if($admin){
            $query .= "0";
        } else {
            $query .= "1";
        }

        $query .= "AND `wish`.Id = ? GROUP BY `wish`.Id LIMIT 1";

        return $this->executeQuery($query , array($wishId));
    }

    public function executeAdminAction($wishId, $IsAccepted, $modName, $status)
    {
        $wishContentDate = $this->getSingleWish($wishId , true)[0]["Date"];

        $query  = "UPDATE `wishContent` SET IsAccepted = ? WHERE `wishContent`.Date = ?;";
        $query .= "UPDATE `wishContent` SET moderator_username = ? WHERE `wishContent`.Date = ?;";
        $query .= "UPDATE `wish` SET Status = ? WHERE id = ?;";

        $this->executeQuery($query ,
            array(0 => $IsAccepted,
            1 => $wishContentDate,
            2 => $modName,
            3 => $wishContentDate ,
            4 => $status ,
            5 => $wishId));
    }
}