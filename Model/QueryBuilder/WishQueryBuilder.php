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
    private function executeQuery($query, $params){
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
     */
    public function getWishes($user = null, array $status = null, $searchKey = null, $admin = false)
    {
        $query = "SELECT * FROM `wish` LEFT JOIN `wishContent`
                        ON `wish`.Id = `wishContent`.wish_Id
                        WHERE `wish`.User IS NOT NULL AND
                        NOT EXISTS(SELECT NULL FROM blockedusers AS b WHERE b.user_Email = `wish`.User AND b.IsBlocked = 1) AND";

        if($user != null){
            $query .= "User = ? AND ";
        }

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

        if($searchKey != null){
            if($status != null){
                $query .= " AND ";
            }

            $query .= "wishContent.Content
                        SOUNDS LIKE ?
                        OR wishContent.Title
                        SOUNDS LIKE ? ";
        }

        if($admin){
            $query = substr_replace($query, '', -3);
        }

        $query .= "GROUP BY `wish`.Id";

        //acquire params if any
        $params = array();

        if($user != null){
            $params[] = $user;
        }

        if($searchKey != null){
            $params[] = $user;
        }

        return $this->executeQuery($query , $params);
    }

    public function getManagementWishes(){
        $query = "SELECT * FROM `wish` as w LEFT JOIN `wishContent`
                        ON `wish`.Id = `wishContent`.wish_Id
                        WHERE NOT w.User IS NULL AND
                        NOT EXISTS(SELECT NULL FROM blockedusers AS b WHERE b.user_Email = w.User AND b.IsBlocked = 1)";

    }
}