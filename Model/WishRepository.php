<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    public function getWishes()
    {
        $result = Database::query
        ("SELECT
          wish.Status,
          wish.Id,
          wish.User,
          wish.Date,
          wish.CompletionDate,
          wishContent.Content,
          wishContent.Title,
          wishContent.Country,
          wishContent.City,
          wishContent.IsAccepted,
          wishContent.moderator_Username
          FROM `wish` JOIN wishContent on wish.Id = wishContent.wish_Id");

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {
            $completed = false;

            if ($result[$i]["Status"] == "Vervuld") {
                $completed = true;
            }

            $returnArray[$i] = new Wish(
                $result[$i]["User"],
                $result[$i]["Title"],
                $result[$i]["Country"],
                $result[$i]["City"],
                $completed,
                $result[$i]["Content"],
                $result[$i]["IsAccepted"]
            );
        }

        return $returnArray;
    }

    // add wish to database
    public function addWish($newWish)
    {

        $wish = $newWish["title"];
        $description = $newWish["description"];
        $tag = $newWish["tag"];

        // TODO: query to add wish to database

    }

    // check if user has less then 3 wishes
    public function canAddWish($email)
    {

        $currentUser = $this->getUserID($email);

        $result = Database::query_safe
        ("select count(*) as counter from `wish` where `user` = ? and `status` != ? and 'status' != ?",
            array($email, "Vervuld", "Geweigerd"));
        $amountWishes = $result[0]["counter"];

        if ($amountWishes >= 3) return false;
        return true;
    }

    public function getRequestedWishes($wishPage)
    {

        switch ($wishPage) {
            case 'requested':
                $result = Database::query("SELECT
              wc.wish_Id as wishid,
              u.DisplayName as display,
              w.status as status,
              w.User as user,
              wc.Content as content,
              wc.Title as title,
              wc.Country as country,
              wc.City as city,
              wc.IsAccepted as accepted,
              wc.moderator_Username as modname,
              wcMax.max_date as mdate
          FROM wish AS w
          JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
              FROM wishContent
              GROUP BY wish_Id) AS wcMax
              ON w.Id = wcMax.wish_Id
          JOIN wishContent AS wc on wcMax.wish_Id = wc.wish_Id AND wc.Date = wcMax.max_date
          join user as u on w.user = u.Email

WHERE w.status = 'Aangemaakt'
AND u.IsActive =1
AND wc.moderator_username is null
          ORDER BY max_date asc");
                break;
            case 'changed':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status != 'Geweigerd' AND wc.moderator_username is null AND u.IsActive =1 ORDER BY mdate asc");
                break;
            case 'open':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Gepubliseerd' AND wc.moderator_username is not null AND wc.isaccepted = 1 AND u.IsActive =1 ORDER BY mdate asc");
                break;
            case 'matched':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Match gevonden'  AND wc.moderator_username is not null AND wc.isaccepted = 1 AND u.IsActive =1 ORDER BY mdate asc");
                break;
            case 'current':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Wordt vervuld'  AND wc.moderator_username is not null AND wc.isaccepted = 1 AND u.IsActive =1 ORDER BY mdate asc");
                break;
            case 'done':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Vervuld' AND wc.moderator_username is not null AND wc.isaccepted = 1 AND u.IsActive =1 ORDER BY mdate asc");
                break;
            case 'denied':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Geweigerd' AND wc.moderator_username is not null AND wc.isaccepted = 0 AND u.IsActive =1 ORDER BY mdate asc");
                break;
            case 'deleted':
                $result = Database::query("select u.DisplayName as display,w.User as user, wc.wish_Id as wishid, w.Status as status,wc.Date as mdate,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Verwijderd' AND u.IsActive =1 ORDER BY mdate asc");
                break;
        }

        return $result;
    }

    public function AdminAcceptWish($id,$mdate)
    {
        Database::query_safe("UPDATE wishContent SET `IsAccepted`=1  WHERE Date =?",array($mdate));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE Date =?",array($mdate));
        Database::query_safe("UPDATE wish SET `Status`='Gepubliseerd'  WHERE id=?",array($id));


    }

    public function AdminRefuseWish($id,$mdate)
    {

        Database::query_safe("UPDATE wishContent SET `IsAccepted`=0  WHERE Date=?",array($mdate));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE Date=?",array($mdate));
        Database::query_safe("UPDATE wish SET `Status`='Geweigerd'  WHERE id=?",array($id));
    }

    public function AdminDeleteWish($id,$mdate)
    {

        Database::query_safe("UPDATE wishContent SET `IsAccepted`=0  WHERE wish_id=?",array($id));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE wish_id=?",array($id));
        Database::query_safe("UPDATE wish SET `Status`='Vewijderd'  WHERE id=?",array($id));
    }

    public function AdminRedrawWish($id,$mdate)
    {

        Database::query_safe("UPDATE wishContent SET `IsAccepted`=0  WHERE Date =?",array($mdate));
        Database::query_safe("UPDATE wishContent SET `moderator_username`= null  WHERE Date =?",array($mdate));
        Database::query_safe("UPDATE wish SET `Status`='Aangemaakt'  WHERE id=?",array($id));
    }

    public function getWishOwner($id)
    {
        $result = Database::query_safe("select u.Email as User from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE wc.wish_id =?",array($id));


        return $result;
    }
}