<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    private $talentRepository, $WishQueryBuilder;
    public $wishLimit = 3;

    public function __construct()
    {
        $this->WishQueryBuilder = new WishQueryBuilder();
        $this->talentRepository = new TalentRepository();
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

                $userParams = array("Email" , "Name" , "DisplayName" , "Surname" , "Address" ,
                    "Postalcode", "Country" , "City" , "Dob" , "Gender" , "Handicap");
                $userCheck = true;

                foreach($userParams as $param){
                    if(!isset($item[$param])){
                        $userCheck = false;
                        break;
                    }
                }

                $wish = new Wish();

                $wish->id = $item["wish_Id"];
                $wish->title = $item["Title"];
                $wish->content = $item["Content"];
                $wish->accepted = $item["IsAccepted"];
                $wish->contentDate = $item["Date"];
                $wish->status = $item["Status"];

                if($userCheck) {
                    $user = new User();
                    $user->email = $item[$userParams[0]];
                    $user->name = $item[$userParams[1]];
                    $user->displayName = $item[$userParams[2]];
                    $user->surname = $item[$userParams[3]];
                    $user->address = $item[$userParams[4]];
                    $user->postalcode = $item[$userParams[5]];
                    $user->country = $item[$userParams[6]];
                    $user->city = $item[$userParams[7]];
                    $user->dob = $item[$userParams[8]];
                    $user->gender = $item[$userParams[9]];
                    $user->handicap = $item[$userParams[10]];
                    $wish->user = $user;
                }

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
        $amountWishes = $this->getWishAmount($email);

        if($amountWishes >= $this->wishLimit){
            return false;
        } else {
            return true;
        }
    }

    public function getWishAmount($email){
        return count($this->getWishesByUser($email));
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

    public function getWish($id)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getSingleWish($id, null))[0];
    }

    //move to talent repo
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

    public function getWishesByUser($user){
        return $this->getReturnArray($this->WishQueryBuilder->getWishes
        ($user , [0 => "Aangemaakt",
            1 => "Gepubliceerd",
            2 => "Geweigerd",
            3 => "Match gevonden" ,
            5 => "Wordt vervuld"]));
    }


    //Rewrite potential?
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
}