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
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,wc.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Aangemaakt' OR wc.moderator_username is null");
                break;
            case 'changed':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,wc.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status != 'Geweigerd' AND wc.moderator_username is null");
                break;
            case 'open':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,w.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Gepubliseerd'");
                break;
            case 'matched':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,w.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Match gevonden'");
                break;
            case 'current':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,w.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Wordt vervuld'");
                break;
            case 'done':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,w.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Vervuld'");
                break;
            case 'denied':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,w.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Geweigerd'");
                break;
            case 'deleted':
                $result = Database::query("select u.DisplayName as user, wc.wish_Id as wishid, w.Status as status,w.Date as date,wc.Title as content ,wc.country as country ,wc.City as city from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE status = 'Verwijderd'");
                break;
        }

        return $result;
    }

    public function AdminAcceptWish($id)
    {
        Database::query_safe("UPDATE wishContent SET `IsAccepted`=1  WHERE Wish_id =?",array($id));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE Wish_id =?",array($id));
        Database::query_safe("UPDATE wish SET `Status`='Gepubliseerd'  WHERE id=?",array($id));


    }

    public function AdminRefuseWish($id)
    {

        Database::query_safe("UPDATE wishContent SET `IsAccepted`=1  WHERE Wish_id=?",array($id));
        Database::query_safe("UPDATE wishContent SET `moderator_username`='Admin'  WHERE Wish_id=?",array($id));
        Database::query_safe("UPDATE wish SET `Status`='Geweigerd'  WHERE id=?",array($id));
    }

    public function getWishOwner($id)
    {
        $result = Database::query_safe("select u.Email as User from wishContent wc INNER JOIN wish w on w.id = wc.wish_Id INNER JOIN user u on w.user = u.email WHERE wc.wish_id =?",array($id));


        return $result;
    }
}