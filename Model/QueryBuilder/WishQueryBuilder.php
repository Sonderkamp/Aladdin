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
    private function executeQuery($query, array $params)
    {
        if (!empty($params)) {
            return Database::query_safe($query, $params);
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
        if ($user != null) {
            $query .= "User = ? AND ";
        }


        //Used in queries by status
        if ($status != null) {
            $query .= "(";
            foreach ($status as $item) {
                $query .= "(Status = '" . $item . "'";
                if ($item == "Aangemaakt") {
                    $query .= ")";
                } else {
                    $query .= " AND `wishContent`.`IsAccepted` = ";
                    if ($admin) {
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
        if ($searchKey != null) {
            if ($status != null) {
                $query .= " AND ";
            }

            $query .= "wishContent.Content
                        SOUNDS LIKE ?
                        OR wishContent.Title
                        SOUNDS LIKE ? ";
        }

        if ($admin && $status == null) {
            $query = substr_replace($query, '', -3);
        }

        $query .= "GROUP BY `wish`.Id";

        //acquire params if any
        $params = array();

        if ($user != null) {
            $params[] = $user;
        }

        if ($searchKey != null) {
            $params[] = $searchKey;
        }

        return $this->executeQuery($query, $params);
    }

    public function getSingleWish($wishId, $admin = null)
    {
        $query = "SELECT * FROM `wish` LEFT JOIN `wishContent`
                        ON `wish`.Id = `wishContent`.wish_Id
                        JOIN `user` ON `wish`.User = `user`.Email ";
        $query .= "WHERE `wish`.Id = ? ";

        if ($admin !== null) {
            if ($admin) {
                $query .= "AND `wishContent`.IsAccepted = 0";
            } else if (!$admin) {
                $query .= "AND `wishContent`.IsAccepted = 1";
            }
        }

        $query .= " GROUP BY `wish`.Id LIMIT 1";

        return $this->executeQuery($query, array($wishId));
    }

    public function executeAdminAction($wishId, $IsAccepted, $modName, $status)
    {
        $wishContentDate = $this->getSingleWish($wishId, true)[0]["Date"];

        $query1 = "UPDATE `wishContent` SET IsAccepted = ? WHERE `wishContent`.Date = ?;";
        $query2 = "UPDATE `wishContent` SET moderator_username = ? WHERE `wishContent`.Date = ?;";
        $query3 = "UPDATE `wish` SET Status = ? WHERE id = ?;";

        $this->executeQuery($query1, array($IsAccepted, $wishContentDate));
        $this->executeQuery($query2, array($modName, $wishContentDate));
        $this->executeQuery($query3, array($status, $wishId));

    }


    public function getLatestWish($user = null)
    {
        $sql = "SELECT * FROM `wish` where `User` = ? ORDER BY `Date` DESC LIMIT 1";

        if ($user == null) {
            return $this->executeQuery($sql, array($_SESSION["user"]->email));
        } else {
            return $this->executeQuery($sql, array($user));
        }
    }


    public function addWish($email = null)
    {
        if ($email == null) {
            $email = $_SESSION["user"]->email;
        }

        $status = "Aangemaakt";

        $query = "INSERT INTO `wish` (`Status`,`User`) VALUES (?,?)";
        $array = array($status, $email);
        $this->executeQuery($query, $array);
    }


    public function addWishContent(Wish $wish)
    {
        $query = "INSERT INTO `wishContent` (`Content`, `Title`, `wish_Id`)
            VALUES (?,?,?)";
        $array = array($wish->content, $wish->title, $wish->id);
        $this->executeQuery($query, $array);
    }

    public function deleteWishTalents(Wish $wish)
    {
        $query = "DELETE from `talent_has_wish` WHERE `wish_Id` = ?";
        $value = array($wish->id);

        $this->executeQuery($query, $value);
    }


    public function bindToTalent($talentName, Wish $wish)
    {
        $query = "SELECT `Id` as talentId FROM `talent` WHERE `Name`=?";
        $array = array($talentName);
        $result = Database::query_safe($query, $array);

        $id = $result[0]["talentId"];

        $query2 = "INSERT INTO `talent_has_wish` (`talent_Id`, `wish_Id`) VALUES (?,?)";
        $array2 = array($id, $wish->id);
        Database::query_safe($query2, $array2);
    }

    public function wishesByTag($tag){

    }

}