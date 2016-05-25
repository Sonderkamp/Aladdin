<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    private $talentRepository, $userRepository, $WishQueryBuilder;
    public $wishLimit = 3;

    public function __construct()
    {
        $this->WishQueryBuilder = new WishQueryBuilder();
        $this->talentRepository = new TalentRepository();
        $this->userRepository   = new UserRepository();
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
        if (!empty($queryResult)) {
            $returnArray = array();

            foreach ($queryResult as $item) {

                $userParams = array("Email", "Name", "DisplayName", "Surname", "Address",
                    "Postalcode", "Country", "City", "Dob", "Gender", "Handicap");
                $userCheck = true;

                foreach ($userParams as $param) {
                    if (!isset($item[$param])) {
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

                if ($userCheck) {
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
        ($this->userRepository->getCurrentUser()->email, [0 => "Aangemaakt",
            1 => "Gepubliceerd",
            2 => "Geweigerd",
            3 => "Match gevonden",
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

    public function getMyCompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes($this->userRepository->getCurrentUser()->email, [0 => "Vervuld", 1 => "Wordt vervuld"]));
    }

    /**
     * @return array of wishes where status != "vervuld"
     */
    public function getIncompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Gepubliceerd", 1 => "Match gevonden"]));
    }

    /**
     * @param $key
     * @return array
     * Searches if title or content SOUNDSLIKE $key
     */
    public function searchMyWishes($key)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes($_SESSION["user"]->email, null, $key));
    }


    public function addWish(Wish $wish)
    {
        $this->WishQueryBuilder->addWish();

        $temp = $this->WishQueryBuilder->getLatestWish();
        $wish->id = $temp[0]["Id"];

        $this->addWishContent($wish);
        $this->editWishTalents($wish);
    }

    private function addWishContent(Wish $wish)
    {
        $this->WishQueryBuilder->addWishContent($wish);
    }

    public function editWishContent(Wish $wish)
    {
        $this->WishQueryBuilder->addWishContent($wish);
        $this->editWishTalents($wish);
    }

    public function editWishTalents(Wish $wish)
    {
        $talents = $this->talentRepository->getAddedTalents();

        $tags = array();
        foreach ($talents as $talent) {
            $tags[] = $talent->name;
        }

        if (is_array($wish->tags)) {
            $this->deleteWishTalents($wish);
            foreach ($wish->tags as $item) {
                if (!in_array($item, $tags)) {
                    $this->talentRepository->addTalent($item);
                }
                $this->bindToTalent($item, $wish);

//                if (in_array($item, $tags)) {
//                    $this->bindToTalent($item, $wish);
//                } else {
//                    $this->talentRepository->addTalent($item);
//                    $this->bindToTalent($item, $wish);
//                }
            }
        }
    }

    public function deleteWishTalents(Wish $wish)
    {
        $this->WishQueryBuilder->deleteWishTalents($wish);
    }

    public function bindToTalent($talentName, Wish $wish)
    {
        $this->WishQueryBuilder->bindToTalent($talentName, $wish);
    }


    /**
     * check if user has less then 3 wishes
     * @param $email
     * @return bool
     */
    public function canAddWish($email)
    {
        // kortere notatie voor de if/else :)
        return $this->getWishAmount($email) < $this->wishLimit;
    }

    public function getWishAmount($email)
    {
        return count($this->getWishesByUser($email));
    }

    public function getRequestedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Aangemaakt", 1 => "Gepubliceerd"], null, true));
    }

    public function getPublishedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Gepubliceerd"], null, null));
    }

    public function getMatchedWishes()
    {
        $userTalents = $this->talentRepository->getAddedTalents();
        $synonyms = $this->talentRepository->getSynonymsOfTalents($userTalents);
        $allTalents = array_merge($userTalents, $synonyms);
        return $this->wishesByTalents($allTalents);
//        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Match gevonden"], null, true));
    }

    public function getCurrentWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Wordt vervuld"], null, null));
    }

    public function getCompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Vervuld"], null, null));
    }

    public function getDeniedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Geweigerd"], null, null));
    }

    public function getDeletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Verwijderd"], null, null));
    }

    public function acceptWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 1, $_SESSION["admin"]->username, "Gepubliceerd");
    }

    public function refuseWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 0, $_SESSION["admin"]->username, "Geweigerd");
    }

    public function deleteWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 0, $_SESSION["admin"]->username, "Verwijderd");
    }

    public function revertWishAction($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 0, null, "Aangemaakt");
    }

    public function getWish($id)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getSingleWish($id, null))[0];
    }


    public function getWishesByUser($user)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes
        ($user, [0 => "Aangemaakt",
            1 => "Gepubliceerd",
            2 => "Geweigerd",
            3 => "Match gevonden",
            5 => "Wordt vervuld"] , null, null, true));
    }

    public function wishesByTalents($talents)
    {
        $temp = $this->WishQueryBuilder->wishIDByTalents($talents);
        return $this->getReturnArray($this->WishQueryBuilder->getWishes_($temp, $this->getMyWishes()));
    }


    public function sendEditMail($id, $titel, $content, $tags)
    {
        $mail = new Email();
        $mail->fromName = "Alladin";
        $mail->subject = "Wens is gewijzigd";
        $mail->message = $this->createMessage($titel, $content, $tags);
        $mail->to = $this->userRepository->getCurrentUser()->email;
        $mail->sendMail();

        $newmail = new messageRepository();
        $msgID = $newmail->sendMessage("Admin", $mail->to, $mail->subject, $mail->message);
        $newmail->setLink($id, "Wens", $msgID);
    }
    
    public function createMessage($titel,$content,$tags){
        $head = "Beste, \n\n email test";
        $msg = "Uw wensweiziging is ingediend, uw wens zal na goedkeuring zichtbaar zijn voor anderen, we houden u hiervan nog op de hoogte.\n\n";
        $wish = "Uw nieuwe wens is als volgt: \n";
        $wishName = "Naam van de wens: \t\t" . $titel . " \n";
        $wishDescription = "Beschrijving van de wens: \t" . $content . "\n";
        $allTagsForMail = implode(' #', $tags);
        $wishTags = "Uw tags zijn: \t\t\t\t#" . $allTagsForMail . "\n\n";
        $end = "Vriendelijke groeten, \n\n Alladin";

        $message = $head . $msg . $wish . $wishName . $wishDescription . $wishTags . $end;
        return $message;
    }


}