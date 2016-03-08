<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository {

    private $email;

    public function getWishes() {
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
          FROM `wish` JOIN wishContent on wish.Id = wishContent.wish_Id ");

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
                $result[$i]["IsAccepted"],
                $result[$i]["Id"]
            );
        }

        return $returnArray;
    }

    // add wish to database
    public function addWish($newWish) {
        $email = $this->getEmail();
        $status = "Aangemaakt";

        $query = "INSERT INTO `wish` (`Status`,`User`) VALUES (?,?)";
        $array = array($status, $email);
        Database::query_safe($query, $array);

        $wishIdQuery = "SELECT `Id` as lastwish FROM `wish` WHERE `User`=? ORDER BY `Date` DESC";
        $wishIdArray = array($email);
        $wishId = Database::query_safe($wishIdQuery,$wishIdArray);

        $id = $wishId[0]["lastwish"];

        $this->wishContentQuery($newWish, $id);
    }

    // add wishContent to database & connect with wish
    public function wishContentQuery($content, $id) {
        $wish = $content["title"];
        $description = $content["description"];
        $tag = $content["tag"];
        $country = $content["country"];
        $city = $content["city"];

        $query = "INSERT INTO `wishContent` (`Content`, `Title`, `wish_Id`,`Country`, `City`)
            VALUES (?,?,?,?,?)";
        $array = array($description, $wish, $id, $country, $city);

        Database::query_safe($query, $array);
    }


    // check if user has less then 3 wishes
    public function canAddWish($email) {
        $this->email = $email;

        $query = "select count(*) as counter from `wish` where `user` = ? and `status` != ? and 'status' != ?";
        $array = array($email, "Vervuld", "Geweigerd");

        $result = Database::query_safe($query, $array);
        $amountWishes = $result[0]["counter"];

        if ($amountWishes >= 3)
            return false;

        return true;
    }

    public function getSelectedWish($id) {
        $query = "select * from `wishContent` where `wish_Id` = ? ORDER BY `date` DESC limit 1";
        $array = array($id);
        $wish = Database::query_safe($query, $array);
        return $wish;
    }

    public function getEmail() {
        return $_SESSION["user"]->email;
    }

    public function getAllTalents() {
        $query = "SELECT `Name` FROM `talent` ORDER BY `Name` ASC";
        $query = Database::query($query);

        return $query;
    }

}