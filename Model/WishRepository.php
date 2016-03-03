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
          wish.User, wish.Date,
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
        // TODO: get current user by method -> getUserID

        $currentUser = $this->getUserID($email);

        $amountWishes = 0;

        // TODO: count total wishes of current user

        if ($amountWishes >= 3) return false;
        return true;
    }

    // get ID of current user
    public function getUserID($email)
    {
        // TODO: querry to get userid by email
        // return USER ID;
    }


}