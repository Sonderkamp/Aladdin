<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 29/05/2016
 * Time: 22:44
 */
class MatchRepository
{

    private $matchQueryBuilder, $messageRepo, $userRepo;

    public function __construct()
    {
        $this->matchQueryBuilder = new MatchQueryBuilder();
        $this->messageRepo = new messageRepository();
        $this->userRepo = new UserRepository();
    }

    private function getReturnArray($queryResult)
    {
        if (!empty($queryResult)) {
            $returnArray = array();

            foreach ($queryResult as $item) {

                $userParams = array("Email", "Name", "DisplayName", "Surname", "Address",
                    "Postalcode", "Country", "City");
                $userCheck = true;

                foreach ($userParams as $param) {
                    if (!isset($item[$param])) {
                        $userCheck = false;
                        break;
                    }
                }

                $match = new Match();

                $match->user = $item["user_Email"];
                $match->isSelected = $item["IsSelected"];
                $match->isActive = $item["matchIsActive"];
                $match->wishId = $item["wish_Id"];

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
                    $user->dob = $item["Dob"];
                    $user->gender = $item["Gender"];
                    $user->handicap = $item["Handicap"];
                    $match->user = $user;
                }

                $returnArray[] = $match;
            }
            return $returnArray;
        }
        return false;
    }

    public function getMatches($wishId)
    {
        return $this->getReturnArray($this->matchQueryBuilder->getMatches($wishId));
    }

    public function setMatch($wishId, $username)
    {
        $this->matchQueryBuilder->addMatch($wishId, $username);
        $this->sentMatchMessage($wishId, $username);
    }

    public function selectMatch($wishId, $username)
    {
        $this->matchQueryBuilder->selectMatch($wishId, $username);
    }

    public function clearSelected($wishId){
        $this->matchQueryBuilder->clearSelected($wishId);
    }

    public function checkDuplicates($username, $wishId)
    {
        if (empty($this->matchQueryBuilder->checkForUser($username, $wishId))) {
            return false;
        } else {
            return true;
        }
    }

    public function checkOwnWish($username, $wishId)
    {
        if (empty($this->matchQueryBuilder->checkOwnWish($username, $wishId))) {
            return false;
        } else {
            return true;
        }
    }

    public function getCompletedMatches($username)
    {
        return $this->getReturnArray($this->matchQueryBuilder->getMatches(null , $username));
    }

    private function sentMatchMessage($wishId, $username)
    {
        $wishRepo = new WishRepository();

        $wish = $wishRepo->getWish($wishId);
        $user = $this->userRepo->getUser($username);

        $message = $user->displayName . " heeft zichzelf als match opgegeven bij uw wens: " . $wish->title .
            " U kunt contact met " . $user->displayName . " opnemen via de website \n
            Wij hopen u hiermee voldoende te hebben geinformeerd \n Stichting Alladin";
        $title = "U heeft een nieuwe match!";

        $messageId = $this->messageRepo->sendMessage($username, $wish->user->email, $title, $message);
        $this->messageRepo->setLink($wishId, 'Wens', $messageId);
    }
}