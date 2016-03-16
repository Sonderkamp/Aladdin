<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    private $talentRepository,
            $email,
            $maxContentLength = 50,
            $WISH_LIMIT = 100;

    public function __construct() {
        $this->talentRepository = new TalentRepository();
    }

    private function checkWishContent($string){
        if(strlen($string) > $this->maxContentLength){
            $returnString = substr($string , 0, $this->maxContentLength);
            $returnString = $returnString . '...';
            return $returnString;
        }
        return $string;
    }

    /**
     * Creates array of wish objects with the params given in the $queryResult.
     * And adds the user elements to the given objects
     * It is used to prevent duplicate code.
     * @param $queryResult
     * @return array
     */
    private function getReturnArray($queryResult)
    {

        $returnArray = array();

        for ($i = 0; $i < count($queryResult); $i++) {

            if (!isset($queryResult[$i]["max_date"])) {
                $queryResult[$i]["max_date"] = null;
            }

            $queryResult[$i]["Content"] = $this->checkWishContent($queryResult[$i]["Content"]);

            $newUser = $this->getUser($queryResult[$i]["User"]);
            $completed = false;
            if ($queryResult[$i]["Status"] == "Vervuld") {
                $completed = true;
            }

            $returnArray[$i] = new Wish(
                $queryResult[$i]["Id"],
                $newUser,
                $queryResult[$i]["Title"],
                $completed,
                $queryResult[$i]["Content"],
                $queryResult[$i]["IsAccepted"],
                $queryResult[$i]["max_date"],
                $queryResult[$i]["Date"],
                $queryResult[$i]["Status"]
            );
        }

        return $returnArray;
    }

    private function getUser($email) {
        $result = Database::query_safe("SELECT * FROM user WHERE user.Email = ?", array($email));

        $newUser = new User(
            $email,
            $result[0]["Admin"],
            $result[0]["Name"],
            $result[0]["Surname"],
            null,
            $result[0]["Address"],
            $result[0]["Handicap"],
            $result[0]["Postalcode"],
            $result[0]["Country"],
            $result[0]["City"],
            $result[0]["Dob"],
            $result[0]["Gender"],
            $result[0]["DisplayName"],
            $result[0]["Initials"]
        );

        return $newUser;
    }


    /**
     * @return array of wishes where user == current user
     */
    public function getMyWishes()
    {

        $user = $this->getEmail();

        $result = Database::query_safe
        ("SELECT
              w.Status,
              w.Id,
              w.User,
              w.Date,
              w.CompletionDate,
              wc.Content,
              wc.Title,
              wc.Country,
              wc.City,
              wc.IsAccepted,
              wc.moderator_Username,
              wcMax.max_date
          FROM wish AS w
          JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
              FROM wishContent
              GROUP BY wish_Id) AS wcMax
              ON w.Id = wcMax.wish_Id
          JOIN wishContent AS wc on wcMax.wish_Id = wc.wish_Id AND wc.Date = wcMax.max_date
          WHERE w.User = ?
          ORDER BY max_date DESC"
            , array($user));

        return $this->getReturnArray($result);
    }

    /**
     * @return array of wishes where status == "vervuld"
     */
    public function getCompletedWishes()
    {

        $status = "Vervuld";

        $result = Database::query_safe
        ("SELECT
              w.Status,
              w.Id,
              w.User,
              w.Date,
              w.CompletionDate,
              wc.Content,
              wc.Title,
              wc.Country,
              wc.City,
              wc.IsAccepted,
              wc.moderator_Username,
              wcMax.max_date
          FROM wish AS w
          JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
              FROM wishContent
              GROUP BY wish_Id) AS wcMax
              ON w.Id = wcMax.wish_Id
          JOIN wishContent AS wc on wcMax.wish_Id = wc.wish_Id AND wc.Date = wcMax.max_date
          WHERE w.Status = ?
          ORDER BY max_date DESC"
            , array($status));

        return $this->getReturnArray($result);
    }

    /**
     * @return array of wishes where status != "vervuld"
     */
    public function getIncompletedWishes()
    {
        $status = "Vervuld";

        $result = Database::query_safe
        ("SELECT
              w.Status,
              w.Id,
              w.User,
              w.Date,
              w.CompletionDate,
              wc.Content,
              wc.Title,
              wc.Country,
              wc.City,
              wc.IsAccepted,
              wc.moderator_Username,
              wcMax.max_date
          FROM wish AS w
          JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
              FROM wishContent
              GROUP BY wish_Id) AS wcMax
              ON w.Id = wcMax.wish_Id
          JOIN wishContent AS wc on wcMax.wish_Id = wc.wish_Id AND wc.Date = wcMax.max_date
          WHERE w.Status != ?
          ORDER BY max_date DESC"
            , array($status));

        return $this->getReturnArray($result);
    }

    public function searchWish($key)
    {
        $result = Database::query_safe("SELECT *
        FROM wish
        JOIN wishContent
        ON wish.Id = wishContent.wish_Id
        WHERE wishContent.Content
        SOUNDS LIKE ?
        OR wishContent.Title
        SOUNDS LIKE ?", array($key, $key));

        return $this->getReturnArray($result);
    }

    /**
     * add wish to database
     * @param $newWish
     */
    public function addWish($newWish)
    {
        $email = $this->getEmail();
        $status = "Aangemaakt";

        $query = "INSERT INTO `wish` (`Status`,`User`) VALUES (?,?)";
        $array = array($status, $email);
        Database::query_safe($query, $array);

        $wishIdQuery = "SELECT `Id` as lastwish FROM `wish` WHERE `User`=? ORDER BY `Date` DESC";
        $wishIdArray = array($email);
        $wishId = Database::query_safe($wishIdQuery, $wishIdArray);

        $id = $wishId[0]["lastwish"];


        $this->wishContentQuery($newWish, $id);
    }


    /**
     * add wishContent to database & connect with wish
     * @param $content
     * @param $id
     */
    public function wishContentQuery($content, $id)
    {
        $wish = $content["title"];
        $description = $content["description"];
        $tag = $content["tag"];

        $query = "INSERT INTO `wishContent` (`Content`, `Title`, `wish_Id`)
            VALUES (?,?,?)";
        $array = array($description, $wish, $id);
        Database::query_safe($query, $array);

        $array = $this->talentRepository->getAllTalents(false);
        $allTags = array();
        foreach ($array as $value) {
            $allTags[] = $value->name;
        }

        if (is_array($tag)) {
            $this->deleteAllWishTalents($id);
            foreach ($tag as $item) {
                if (in_array($item, $allTags)) {
                    $this->addTalentToWish($item, $id);
                } else {
                    $this->talentRepository->addTalent($item);
                    $this->addTalentToWish($item, $id);
                }
            }
        }
    }

    public function deleteAllWishTalents($wishid) {
        $query = "DELETE from `talent_has_wish` WHERE `wish_Id` = ?";
        $value = array($wishid);

        Database::query_safe($query, $value);
    }

    public function addTalentToWish($talent, $wishId) {
        $query = "SELECT `Id` as talentId FROM `talent` WHERE `Name`=?";
        $array = array($talent);
        $result = Database::query_safe($query, $array);

        $id = $result[0]["talentId"];

        $query2 = "INSERT INTO `talent_has_wish` (`talent_Id`, `wish_Id`) VALUES (?,?)";
        $array2 = array($id, $wishId);
        Database::query_safe($query2, $array2);
    }

    /**
     * check if user has less then 3 wishes
     * @param $email
     * @return bool
     */
    public function canAddWish($email)
    {

        $this->email = $email;

        $query = "select count(*) as counter from `wish` where `user` = ? and `status` != ? and 'status' != ?";
        $array = array($email, "Vervuld", "Geweigerd");

        $result = Database::query_safe($query, $array);
        $amountWishes = $result[0]["counter"];

        $wishLimit = $this->WISH_LIMIT;
        if ($amountWishes >= $wishLimit)
            return false;
        return true;
    }


//    public function getWish($id) {
//    /**
//     * Gets wish using param
//     * @param $id
//     * @return Wish
//     */

    public function getRequestedWishes($wishPage) {

        switch ($wishPage) {
            case 'requested':
                // changeuser_email froms tring to $user have to get $user form somewhere
                $result = Database::query("
              SELECT
              wc.wish_Id as wishid,
              u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
              w.status as status,
              w.User as user,
              wc.Content as content,
              wc.Title as title,
              wc.Country as country,
              wc.City as city,
              wc.IsAccepted as accepted,
              wc.moderator_Username as modname,
              wcMax.max_date as mdate,
              isblock.IsBlocked as isblocked
          FROM wish AS w
          JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
              FROM wishContent
              GROUP BY wish_Id) AS wcMax
              ON w.Id = wcMax.wish_Id
          JOIN wishContent AS wc on wcMax.wish_Id = wc.wish_Id AND wc.Date = wcMax.max_date
          join user as u on w.user = u.Email
          left JOIN (select IsBlocked,ab.user_email
from adminBlock as ab,(
SELECT User_Email,max(ab.Block_Id)  as blockid , MAX(ab.BlockDate) AS abmax_date
              FROM adminBlock as ab
              GROUP BY User_Email
    ) as test
where ab.user_Email = test.User_Email
AND ab.BlockDate = test.abmax_date
AND ab.Block_Id = test.blockid) AS isblock
              ON u.Email = isblock.User_Email
              where (isBlocked = 0 OR isBlocked is null)
              AND status = 'Aangemaakt'
              AND u.IsActive = 1
              AND wc.IsAccepted = 0
              AND wc.moderator_username is null
              ORDER BY max_date asc");
                break;
            case 'changed':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
              w.Status as status,
              wc.Date as mdate,
              wc.Title as title ,
              wc.Content as content ,
              wc.country as country ,
              wc.City as city,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status != 'Geweigerd'
            AND wc.moderator_username is null
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
            case 'open':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
             w.Status as status,
            wc.Date as mdate,
            wc.Title as title ,
            wc.Content as content ,
            wc.country as country ,
            wc.City as city,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status = 'Gepubliseerd'
            AND wc.moderator_username is not null
            AND wc.isaccepted = 1
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
            case 'matched':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
             w.Status as status,
            wc.Date as mdate,
            wc.Title as title ,
            wc.Content as content ,
            wc.country as country ,
            wc.City as city,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status = 'Match gevonden'
            AND wc.moderator_username is not null
            AND wc.isaccepted = 1
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
            case 'current':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
             w.Status as status,
            wc.Date as mdate,
            wc.Title as title ,
            wc.Content as content ,
            wc.country as country ,
            wc.City as city,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status = 'Wordt vervuld'
            AND wc.moderator_username is not null
            AND wc.isaccepted = 1
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
            case 'done':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
             w.Status as status,
            wc.Date as mdate,
            wc.Title as title ,
            wc.Content as content ,
            wc.country as country ,
            wc.City as city,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status = 'Vervuld'
            AND wc.moderator_username is not null
            AND wc.isaccepted = 1
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
            case 'denied':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
             w.Status as status,
            wc.Date as mdate,
            wc.Title as title ,
            wc.Content as content ,
            wc.country as country ,
            wc.City as city,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status = 'Geweigerd'
            AND wc.moderator_username is not null
            AND wc.isaccepted = 0
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
            case 'deleted':
                $result = Database::query("
            select u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
            w.User as user,
             wc.wish_Id as wishid,
             w.Status as status,
            wc.Date as mdate,
            wc.Title as title ,
            wc.Content as content ,
            wc.country as country ,
            wc.City as city ,
              wc.Date as mdate
            from wishContent wc
            INNER JOIN wish w on w.id = wc.wish_Id
            INNER JOIN user u on w.user = u.email
            WHERE status = 'Verwijderd'
            AND u.IsActive =1
            ORDER BY mdate asc");
                break;
        }

        return $result;
    }

    public function AdminAcceptWish($id, $mdate)
    {
        Database::query_safe("UPDATE wishContent SET `IsAccepted`=1  WHERE Date =?", array($mdate));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE Date =?", array($mdate));
        Database::query_safe("UPDATE wish SET `Status`='Gepubliseerd'  WHERE id=?", array($id));


    }

    public function AdminRefuseWish($id, $mdate)
    {
        Database::query_safe("UPDATE wishContent SET `IsAccepted`=0  WHERE Date=?", array($mdate));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE Date=?", array($mdate));
        Database::query_safe("UPDATE wish SET `Status`='Geweigerd'  WHERE id=?", array($id));
    }

    public function AdminDeleteWish($id, $mdate)
    {
        Database::query_safe("UPDATE wishContent SET `IsAccepted`=0  WHERE wish_id=?", array($id));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE wish_id=?", array($id));
        Database::query_safe("UPDATE wish SET `Status`='Verwijderd'  WHERE id=?", array($id));
    }

    public function AdminRedrawWish($id, $mdate)
    {
        Database::query_safe("UPDATE wishContent SET `IsAccepted`=0  WHERE Date =?", array($mdate));
        Database::query_safe("UPDATE wishContent SET `moderator_username`= null  WHERE Date =?", array($mdate));
        Database::query_safe("UPDATE wish SET `Status`='Aangemaakt'  WHERE id=?", array($id));
    }


    public function getWishOwner($id)
    {
        $result = Database::query_safe("select u.Email as User from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE wc.wish_id =?", array($id));


        return $result;
    }

    public function getUserWishes($user)
    {
        $result = Database::query_safe("
              SELECT
              wc.wish_Id as wishid,
              u.DisplayName as display,
              u.Address as address,
              u.Postalcode as postalcode,
              u.Country as country,
              u.City as city,
              w.status as status,
              w.User as user,
              wc.Content as content,
              wc.Title as title,
              wc.Country as country,
              wc.City as city,
              wc.IsAccepted as accepted,
              wc.moderator_Username as modname,
              wcMax.max_date as mdate,
              isblock.IsBlocked as isblocked
          FROM wish AS w
          JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
              FROM wishContent
              GROUP BY wish_Id) AS wcMax
              ON w.Id = wcMax.wish_Id
          JOIN wishContent AS wc on wcMax.wish_Id = wc.wish_Id AND wc.Date = wcMax.max_date
          join user as u on w.user = u.Email
          left JOIN (select IsBlocked,ab.user_email
from adminBlock as ab,(
SELECT User_Email,max(ab.Block_Id)  as blockid , MAX(ab.BlockDate) AS abmax_date
              FROM adminBlock as ab
              GROUP BY User_Email
    ) as test
where ab.user_Email = test.User_Email
AND ab.BlockDate = test.abmax_date
AND ab.Block_Id = test.blockid) AS isblock
              ON u.Email = isblock.User_Email
              where w.User =?
              ORDER BY max_date asc",array($user));

        return $result;
    }

    public function getWish($id)
    {

        $result = Database::query_safe
        ("SELECT
          wish.Id,
          wish.Status,
          wish.User,
          wish.Date,
          wishContent.Content,
          wishContent.Title,
          wishContent.IsAccepted
          FROM `wish` JOIN wishContent ON wish.Id = wishContent.wish_Id WHERE wish.Id = ?", array($id));


        if ($result != null) {

            $selectedWish = $this->getReturnArray($result)[0];

            return $selectedWish;

        } else {
            apologize("404 wens kan niet worden gevonden");
        }

    }

    /**
     * Zou wel eens deprecated kunnen zijn -> even navragen bij mevlüt
     * @param $id
     * @return array|bool
     */
    public function getSelectedWish($id)
    {
        $query = "select * from `wishContent` where `wish_Id` = ? ORDER BY `date` DESC limit 1";
        $array = array($id);
        $wish = Database::query_safe($query, $array);
        return $wish;
    }

    public function getEmail()
    {
        return $_SESSION["user"]->email;
    }

    public function getAllTalents()
    {
        $query = "SELECT `Name` FROM `talent` WHERE `isRejected`=? ORDER BY `Name` ASC";
        $array = array(1);
        return Database::query_safe($query, $array);
    }

    public function getWishTalent($wishId)
    {
        $query = "SELECT `talent_id` as talent FROM `talent_has_wish` WHERE `wish_id`=?";
        $array = array($wishId);
        $result = Database::query_safe($query, $array);

        $talentIDArray = array();
        foreach ($result as $item) {
            $talentIDArray[] = $item["talent_Id"];
        }

        $string = '(';
        foreach ($talentIDArray as $item) {
            $string .= $item . ',';
        }
        $value = substr($string, 0, -1);
        $value .= ')';

        $query1 = "SELECT `Name` FROM `talent` WHERE `Id` IN $value";
        $result1 = Database::query($query1);

        $size = count($result1);
        $returnArray = array();
        for ($i = 0; $i < $size; $i++) {
            $returnArray[] = $result1[$i]["Name"];
        }

        return $returnArray;
    }

    public function getNewestWishContent($id)
    {
        $query = "SELECT `wish_Id`, `Date` FROM `wishContent` WHERE `wish_Id` = ? ORDER BY `Date` desc limit 1";
        $array = array($id);
        $result = Database::query_safe($query, $array);

        return $result;
    }


}