<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository {

    private $email;
    private $WISH_LIMIT = 3;

    public function getWishes() {
        $result = Database::query
        ("SELECT
              w.Status,
              w.Id,
              w.User,
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
          ORDER BY max_date DESC");

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
                $result[$i]["Id"],
                $result[$i]["max_date"]
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
        $wishId = Database::query_safe($wishIdQuery, $wishIdArray);

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

        $this->addTalentToWish($tag, $id);
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


    // check if user has less then 3 wishes
    public function canAddWish($email) {

        $this->email = $email;

        $query = "select count(*) as counter from `wish` where `user` = ? and `status` != ? and 'status' != ?";
        $array = array($email, "Vervuld", "Geweigerd");

        $result = Database::query_safe($query, $array);
        $amountWishes = $result[0]["counter"];

        $wishLimit = $this->WISH_LIMIT;
        if ($amountWishes >= $wishLimit) return false;
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
        $query = "SELECT `Name` FROM `talent` WHERE `isRejected`=? ORDER BY `Name` ASC";
        $array = array(1);
        return Database::query_safe($query,$array);
    }

    public function getWishTalent($wishId) {
        $query = "SELECT `talent_id` as talent FROM `talent_has_wish` WHERE `wish_id`=?";
        $array = array($wishId);
        $result = Database::query_safe($query, $array);
        $talentID = array($result[0]["talent"]);

        $query = "SELECT `Name` FROM `talent` WHERE `Id`=?";
        $result = Database::query_safe($query, $talentID);
        return $result[0]["Name"];
    }

    public function getNewestWishContent($id) {
        $query = "SELECT `wish_Id`, `Date` FROM `wishContent` WHERE `wish_Id` = ? ORDER BY `Date` desc limit 1";
        $array = array($id);
        $result = Database::query_safe($query, $array);

        return $result;
    }


}