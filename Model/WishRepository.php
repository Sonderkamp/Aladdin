<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    public function getWishes(){
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

        for($i = 0; $i < count($result); $i++){
            $completed = false;

            if($result[$i]["Status"] == "Vervuld"){
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

    public function getYourWishes(){

    }
}