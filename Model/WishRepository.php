<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    private $talentRepository, $email, $maxContentLength = 50 , $WishQueryBuilder;
    public $WISH_LIMIT = 3 , $MINIMUM_TALENTS = 3;

    public function __construct()
    {
        $this->WishQueryBuilder = new WishQueryBuilder();
        $this->talentRepository = new TalentRepository();
    }

    //move to controller
    private function checkWishContent($string)
    {
        if (strlen($string) > $this->maxContentLength) {
            $returnString = substr($string, 0, $this->maxContentLength);
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
        if(!empty($queryResult)) {
            $returnArray = array();

            foreach($queryResult as $item){

                $user = new User();
                $user->email = $item["Email"];
                $user->name = $item["Name"];
                $user->displayName = $item["DisplayName"];
                $user->surname = $item["Surname"];
                $user->address = $item["Address"];
                $user->postalcode = $item["Postalcode"];
                $user->country = $item["Country"];
                $user->city = $item["City"];
                $user->dob = $item["Dob"];
                $user->gender = $item["Gender"];
                $user->handicap = $item["Handicap"];

                $wish = new Wish();

                $wish->id = $item["wish_Id"];
                $wish->title = $item["Title"];
                $wish->content = $item["Content"];
                $wish->accepted = $item["IsAccepted"];
                $wish->contentDate = $item["Date"];
                $wish->status = $item["Status"];
                $wish->user = $user;

                $returnArray[] = $wish;
            }
            return $returnArray;
        }
        return false;
    }


    /**
     * @return array of wishes where user == current user and status != "Verwijderd"
     */
    public function getMyWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes
        ($_SESSION["user"]->email , [0 => "Aangemaakt",
            1 => "Gepubliceerd",
            2 => "Geweigerd",
            3 => "Match gevonden" ,
            4 => "Vervuld",
            5 => "Wordt vervuld"]));
    }

    /**
     * @return array of wishes where status == "vervuld" or "wordt vervuld"
     */
    public function getCurrentCompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes([0 => "Vervuld", 1 => "Wordt vervuld"]));
    }

    public function getMyCompletedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes($_SESSION["user"]->email , [0 => "Vervuld", 1 => "Wordt vervuld"]));
    }

    /**
     * @return array of wishes where status != "vervuld"
     */
    public function getIncompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Gepubliceerd", 1 => "Match gevonden" ]));
    }

    /**
     * @param $key
     * @return array
     * Searches if title or content SOUNDSLIKE $key
     */
    public function searchMyWishes($key)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes($_SESSION["user"]->email , null , $key));
    }

    /**
     * add wish to database
     * @param $newWish
     */
    public function addWish($newWish)
    {
        $email = $_SESSION["user"]->email;
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

        $array = $this->talentRepository->getTalents();
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

    public function deleteAllWishTalents($wishid)
    {
        $query = "DELETE from `talent_has_wish` WHERE `wish_Id` = ?";
        $value = array($wishid);

        Database::query_safe($query, $value);
    }

    public function addTalentToWish($talent, $wishId)
    {
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

        $amountWishes = $this->getWishAmount($email);

        $wishLimit = $this->WISH_LIMIT;

        // count talents
//        $myTalents = $this->talentRepository->getUserTalents();
//        $amountOfTalents = 0;
//        foreach($myTalents as $item){
//            if($item instanceof Talent){
//                if($item->is_rejected == 1){
//                    $amountOfTalents++;
//                }
//            }
//        }

//        if ($amountWishes >= $wishLimit || $amountOfTalents < $this->MINIMUM_TALENTS)
//            return false;
//        return true;

        if($amountWishes >= $wishLimit){
            return false;
        } else {
            return true;
        }
    }
    //deprecated use count($mywishesarray) instead
    public function getWishAmount($email){
        $query = "select count(*) as counter from `wish` where `user` = ? and `status` != ? and `status` != ? and `status` != ?";
        $array = array($email, "Vervuld", "Geweigerd" , "Verwijderd");
        $result = Database::query_safe($query, $array);

        return $result[0]["counter"];
    }

    public function getRequestedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Aangemaakt" , 1 => "Gepubliceerd"] , null, true));
    }

    public function getPublishedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Gepubliceerd"] , null , false));
    }

    public function getMatchedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Match gevonden"] , null , true));
    }

    public function getCurrentWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Wordt vervuld"] , null , true));
    }

    public function getCompletedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Vervuld"] , null , true));
    }

    public function getDeniedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Geweigerd"] , null , true));
    }

    public function getDeletedWishes(){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Verwijderd"] , null , true));
    }

    public function acceptWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id , 1 , $_SESSION["admin"]->username  , "Gepubliceerd");
    }

    public function refuseWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id , 0 , $_SESSION["admin"]->username , "Geweigerd");
    }

    public function deleteWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id , 0 , $_SESSION["admin"]->username , "Verwijderd");
    }

    public function revertWishAction($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id , 0 , null , "Aangemaakt");
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
              w.status as status,
              w.User as user,
              wc.Content as content,
              wc.Title as title,
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
              ORDER BY max_date asc", array($user));

        return $result;
    }

    public function getWish($id)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getSingleWish($id, null));
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

    public function getWishTalent($wishId)
    {
        $query = "SELECT `talent_id` as talent FROM `talent_has_wish` WHERE `wish_id`=?";
        $array = array($wishId);
        $result = Database::query_safe($query, $array);

        $talentIDArray = array();
        foreach ($result as $item) {
            $talentIDArray[] = $item["talent"];
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

    public function getAllWishesByEmail($email)
    {
        $query = "SELECT * FROM `wish` WHERE `user` = ?";
        $array = array($email);
        $result = Database::query_safe($query, $array);


        $allWishId = array();
        foreach ($result as $item) {
            $allWishId[] = $item["Id"];
        }

        return $allWishId;
    }


    public function getAllWishesWithTag($tag)
    {
        $intArray = array();
        $id = "(";
        foreach ($tag as $item) {
            if ($item instanceof Talent) {
                $id .= $item->getId() . ',';
            }
        }
        $value = substr($id, 0, -1);
        $value .= ')';

        $rest = substr($value, -2, 1);

        if($rest == ","){
            $value = substr($id, 0, -2);
            $value .= ')';
        }


        $sql = "SELECT wish_Id FROM `talent_has_wish` where talent_id in $value";
        $result = Database::query($sql);

        if (!empty($result)) {
            $string = "(";
            foreach ($result as $item) {
                $string .= $item["wish_Id"] . ",";
            }
            $value = substr($string, 0, -1);
            $value .= ')';


            $myWishes = $this->getMyWishes();
            $myWishId = "(";
            foreach ($myWishes as $item) {
                if ($item instanceof Wish) {
                    $myWishId .= $item->id . ",";
                }
            }

            $value2 = substr($myWishId, 0, -1);
            $value2 .= ')';


            $result = Database::query
            ("SELECT *
              FROM wish AS w
                JOIN (SELECT wish_Id, MAX(wishContent.Date) AS max_date
                FROM wishContent
                WHERE IsAccepted = 1 
                  AND moderator_username is not null
                GROUP BY wish_Id) AS wcMax
                  ON w.Id = wcMax.wish_Id
                JOIN wishContent AS wc 
                  ON wcMax.wish_Id = wc.wish_Id
                WHERE wc.wish_Id in $value 
                  AND wc.wish_Id NOT IN $value2
                  AND (w.Status = 'Gepubliceerd' OR w.status='Match gevonden') 
                  AND wc.Date = wcMax.max_date
              ORDER BY max_date DESC");


           return $this->getReturnArray($result);
        }
    }

    //soon to be deprecated
    public function getUserOfWish($wishID){
        $sql = "select * from wish where Id = ?";
        $parameters = array($wishID);
        $result = Database::query_safe($sql,$parameters);
        return $result[0]["User"];
    }
}