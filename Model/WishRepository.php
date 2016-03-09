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
          wishContent.IsAccepted,
          wishContent.moderator_Username
          FROM `wish` JOIN wishContent ON wish.Id = wishContent.wish_Id");

        $returnArray = array();

        for ($i = 0; $i < count($result); $i++) {
            $completed = false;

            if ($result[$i]["Status"] == "Vervuld") {
                $completed = true;
            }

            $returnArray[$i] = new Wish(
                $result[$i]["Id"],
                $result[$i]["User"],
                $result[$i]["Title"],
                $completed,
                $result[$i]["Content"],
                $result[$i]["IsAccepted"],
                $result[$i]["Date"],
                $result[$i]["Status"]
            );
        }

        return $returnArray;
    }

    // add wish to database
    public function addWish($newWish, $edit) {
        $wish = $newWish["title"];
        $description = $newWish["description"];
        $tag = $newWish["tag"];

        $date = date('Y-m-d H:i:s');
        $email = $this->getEmail();

        $status = "Aangemaakt";

        // IF EDIT NIEUWE WISHCONTENT
//        if ($edit) {
//            $status = "";
//        }

        $query1 = "INSERT INTO `wish` (`Status`,`User`,`Date`) VALUES (?,?,?)";
        $array1 = array($status, $email, $date);
        Database::query_safe($query1, $array1);

        $wishId = Database::query_safe("
            SELECT `Id` as lastwish FROM `wish` WHERE `User`=? ORDER BY `Date` DESC ", array($email));
        $id = $wishId[0]["lastwish"];

        // TODO: Delete ISACCEPTED, Moderator.username, Date.
        $query = "INSERT INTO `wishContent` (`Date`,`Content`, `Title`, `IsAccepted`,
                  `moderator_Username`, `wish_Id`,`Country`, `City`)
            VALUES (?,?,?,?,?,?,?,?)";

        $array = array($date, $description, $wish, 2, "Admin", $id, $country, $city);
        Database::query_safe($query, $array);

    }

    // check if user has less then 3 wishes
    public function canAddWish($email) {
        $this->email = $email;
        $result = Database::query_safe
        ("select count(*) as counter from `wish` where `user` = ? and `status` != ? and 'status' != ?",
            array($email, "Vervuld", "Geweigerd"));
        $amountWishes = $result[0]["counter"];

        if ($amountWishes >= 3)
            return false;

        return true;
    }

    public function getWish($id){

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


        if($result != null){

            $completed = false;
            if($result[0]["Status"] == "Vervuld"){
                $completed = true;
            }

            $selectedWish = new Wish(
                $result[0]["Id"],
                $result[0]["User"],
                $result[0]["Title"],
                $completed,
                $result[0]["Content"],
                $result[0]["IsAccepted"],
                $result[0]["Date"],
                $result[0]["Status"]
            );

            return $selectedWish;
        } else {
            apologize("404 wens kan niet worden gevonden");
        }

    }

    public function getSelectedWish($id) {
        $wish = Database::query_safe
        ("select * from `wishContent` where `wish_Id` = ?", array($id));

        return $wish;
    }

    public function getEmail() {
        return $_SESSION["user"]->email;
    }

    // public function getAllTalents() {
    //     $query = Database::query("SELECT `Name` FROM `talent` ORDER BY `Name` ASC");

    //     return $query;
    // }

}