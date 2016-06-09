<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 13-May-16
 * Time: 11:24
 */
class WishQueryBuilder extends QueryBuilder
{

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
    public function getWishes($user = null, array $status = null, $searchKey = null, $admin = null, $allowBlock = false, $myWishesID = null, $matchWishesID = null)
    {

        $query = "SELECT *
                  FROM `wish`
                  LEFT JOIN `wishContent`
                  ON `wish`.Id = `wishContent`.wish_Id
                  JOIN `user` ON `wish`.User = `user`.Email
                  WHERE `wish`.User IS NOT NULL AND ";

        if (!$allowBlock) {
            $query .= "NOT EXISTS(SELECT NULL FROM blockedusers AS b WHERE b.user_Email = `wish`.User AND b.IsBlocked = 1 AND
                   b.Id = (SELECT Id FROM blockedusers as c WHERE c.user_Email = `wish`.User ORDER BY DateBlocked DESC LIMIT 1)) AND ";
        }


        if ($admin) {
            $query .= "`wishContent`.moderator_Username IS NULL AND ";
        }

        if (isset($myWishesID, $matchWishesID)) {
            $query .= "`wishContent`.wish_Id in $matchWishesID
                    AND `wishContent`.wish_Id NOT IN $myWishesID AND ";
        }

        //Used in queries by User
        if ($user != null) {
            $query .= "User = ? AND ";
        }

        $query .= " `wishContent`.Date = (SELECT max(Date) FROM `wishContent` WHERE wish_id = `wish`.Id AND ";

        //Used in queries by status
        if ($status != null) {
            $query .= "(";
            foreach ($status as $item) {
                $query .= "(Status = '" . $item . "'";
                if ($item == "Aangemaakt") {
                    $query .= ")";
                } else {
                    if (isset($admin)) {
                        $query .= " AND `wishContent`.`IsAccepted` = ";
                        if ($admin) {
                            $query .= "0";
                        } else {
                            $query .= "1";
                        }
                    }
                    $query .= ")";
                }
                $query .= " OR ";

            }
            $query = substr_replace($query, '', -3);
            $query .= "))";
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




        $query .= " GROUP BY `wish`.Id";

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

    /** get one wish
     * @param $wishId = the id of the wish you want
     * @param $admin, set if only wants the accepted wishes
     * @return result of the query */
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

        $query1 = "UPDATE `wishContent` SET IsAccepted = ? WHERE `wishContent`.Date = ? AND `wishContent`.wish_Id = ?;";
        $query2 = "UPDATE `wishContent` SET moderator_username = ? WHERE `wishContent`.Date = ? AND `wishContent`.wish_Id = ?;";
        $query3 = "UPDATE `wish` SET Status = ? WHERE id = ?;";

        $this->executeQuery($query1, array($IsAccepted, $wishContentDate, $wishId));
        $this->executeQuery($query2, array($modName, $wishContentDate, $wishId));
        $this->executeQuery($query3, array($status, $wishId));

    }


    /** returns the last wish from the user given in the param
     * @param $user = email of the user
     * @return result of the query */
    public function getLatestWish($user)
    {
        $sql = "SELECT * FROM `wish` where `User` = ? ORDER BY `Date` DESC LIMIT 1";
        return $this->executeQuery($sql, array($user));
    }


    /** add wish to database
     * @param $email = email of the user
     * @return result of the query */
    public function addWish($email)
    {
        $status = "Aangemaakt";

        $query = "INSERT INTO `wish` (`Status`,`User`) VALUES (?,?)";
        $array = array($status, $email);
        $this->executeQuery($query, $array);
    }

    /** add content of the wish to the database
     * @param $wish = Wish object */
    public function addWishContent(Wish $wish)
    {
        $query = "INSERT INTO `wishContent` (`Content`, `Title`, `wish_Id`)
            VALUES (?,?,?)";
        $array = array($wish->content, $wish->title, $wish->id);

        $this->executeQuery($query, $array);
    }

    /** change status of a wish
     * @param $wishId = id of the wish
     * @param $status = status to change the wish to */
    public function editWishStatus($wishId, $status)
    {
        $query = "UPDATE `wish` SET Status = ? WHERE id = ?;";
        $this->executeQuery($query, array($status, $wishId));
    }

    /** delete all talents which are linked to a wish
     * @param $wish = Wish object*/
    public function deleteWishTalents(Wish $wish)
    {
        $query = "DELETE from `talent_has_wish` WHERE `wish_Id` = ?";
        $value = array($wish->id);

        $this->executeQuery($query, $value);
    }

    /** delete wish content which is linked to a wish
     * @param $wish = Wish object*/
    public function deleteWishContent(Wish $wish)
    {
        $query = "DELETE FROM `wishcontent` WHERE `wish_Id` = ?";
        $value = array($wish->id);

        $this->executeQuery($query, $value);
    }
    
    /** binds talent to wish 
     * @param $talentName = name of the talent to bind to a wish
     * @param $wish = Wish object */
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

    /** get all wish id's which are linked by talent_id's 
     * @param $talents = array of talents
     * @return list with wish id's */
    public function wishIDByTalents($talents)
    {
        $talentList = array();
        foreach ($talents as $item) {
            $talentList[] = $item->id;
        }

        $tags = $this->getSQLString($talentList);

        $sql = "SELECT wish_Id FROM `talent_has_wish` where talent_id in $tags";
        $result = $this->executeQuery($sql, array());

        $list = array();
        foreach ($result as $item) {
            $list[] = $item["wish_Id"];
        }

        return $list;
    }

    /** get's wishes which can be a match  
     * @param $talents = array with talent id's 
     * @param $myWishes = list with wishes of user 
     * @return list with wishes */
    public function getPossibleMatches($talents, $myWishes)
    {
        $talentList = $this->getSQLString($talents);
        $published = "gepubliseerd";
        $temp = array();
        foreach ($myWishes as $item) {
            $temp[] = $item->id;
        }

        $wishList = $this->getSQLString($temp);
        return $this->getWishes(null, array($published, "Match gevonden"), null, false, false, $wishList, $talentList);
    }


    /** creates a sql string with comma's 
     * @param $array an array with numbers 
     * @return an sql string like: (1,2,3,4) so that it can be used in an sql query 
     * like select * from .. where in getSQLString(..) */
    public function getSQLString($array)
    {
        $string = '(';
        foreach ($array as $item) {
            $string .= $item . ',';
        }
        $value = substr($string, 0, -1);
        $value .= ')';

        return $value;
    }


    public function getComments($wishID)
    {

        setlocale(LC_TIME, 'Dutch');
        $array = Database::query_safe("SELECT `wishmessage`.`Message`, `wishmessage`.`Image`,  `wishmessage`.`CreationDate`, `wishmessage`.`user_Email`, `wishmessage`.`wish_Id`, `user`.`DisplayName` FROM `wishmessage` join `user` on `email` = `user_Email`
WHERE `wish_Id` = ? ORDER BY `wishmessage`.`CreationDate` ASC ", array($wishID));

        $comments = array();
        foreach ($array as $row) {
            $comment = new Comment();
            $comment->message = $row["Message"];
            $comment->image = $row["Image"];
            $comment->creationDate = strftime("%#d %B %Y", strtotime($row["CreationDate"]));
            $comment->userEmail = $row["user_Email"];
            $comment->wishId = $row["wish_Id"];
            $comment->displayName = $row["DisplayName"];

            $comments[] = $comment;
        }
        return $comments;

    }

    public function addComment($comment, $wishID, $user, $img = null)
    {
        Database::query_safe("INSERT INTO `wishmessage` (`Message`, `Image`, `CreationDate`, `user_Email`, `wish_Id`) VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?);", array($comment, $img, $user->email, $wishID));
    }

    public function lastCommentMinutes($wishID, $user)
    {
        $array = Database::query_safe("SELECT `wishmessage`.`CreationDate` FROM `wishmessage` WHERE `wish_Id` = ? AND user_Email = ? order by `wishmessage`.`CreationDate` DESC LIMIT 1", array($wishID, $user->email));

        if(count($array) < 1 || empty($array[0]["CreationDate"]))
        {
            return -1;
        }
        else
        {
            return ( time() - strtotime($array[0]["CreationDate"])) / 60;
        }
    }


    public function getMatchByFulfiller($wishId, $user) {
        return Database::query_safe("SELECT * FROM `matches` WHERE `IsSelected` = 1 AND `user_Email` = ? AND `wish_Id` = ?",array($user,$wishId));
    }
}