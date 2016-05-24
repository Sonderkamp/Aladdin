<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 6-3-2016
 * Time: 16:35
 */
class TalentRepository
{
    // Instant variables
    private $talentBuilder, $userRepo;

    // Constants
    public $TALENT_MINIMUM = 3;

    // Constructor
    public function __construct()
    {
        $this->talentBuilder = new TalentQueryBuilder();
        $this->userRepo = new UserRepository();
    }

    // ###### Create ######

    // addTalent
    public function addTalent($name)
    {
        $this->talentBuilder->addTalent($name, $this->userRepo->getCurrentUser()->email);
    }

    // addTalentToUser
    public function addTalentUser($talentId, $user = null)
    {
        if($user != null) {

            $this->talentBuilder->addTalentToUser($talentId, $user);
        } else {

            $this->talentBuilder->addTalentToUser($talentId, $this->userRepo->getCurrentUser()->email);
        }
    }

    // addSynonym
    public function addSynonym($talentId, $synonymId)
    {
        $this->talentBuilder->addSynonym($talentId, $synonymId);
    }


    // ####### Read ######

    // getAllTalents
    public function getTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page), $synonyms);
    }

    public function searchTalents($search, $page = null, $synonyms = null) {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, null, null, null, null, $search), $synonyms);
    }

    // getAcceptedTalents
    public function getAcceptedTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, true), $synonyms);
    }

    public function searchAcceptedTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, true, null, null, null, null, null, null, null, $search), $synonyms);
    }

    // getNotAddedTalents
    public function getUnaddedTalents($user = null, $page = null, $synonyms = null)
    {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, $user), $synonyms);
    }

    public function searchUnaddedTalents($user = null, $search, $page = null, $synonyms = null)
    {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, $user, null, null, null, null, null, null, $search), $synonyms);
    }

    // getAddedTalents
    public function getAddedTalents($user = null, $page = null, $synonyms = null) {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, $user), $synonyms);

    }

    public function searchAddedTalents($user = null, $search, $page = null, $synonyms = null)
    {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, $user, null, null, null, null, $search), $synonyms);
    }

    // getTalentById
    public function getTalent($id, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents(null, null, null, $id), $synonyms);
    }

    // getTalentsRequestedByCurrentUser
    public function getRequestedTalents($user = null, $page = null, $synonyms = null)
    {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, $user), $synonyms);

    }

    public function searchRequestedTalents($user = null, $search, $page = null, $synonyms = null)
    {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, $user, null, null, null, $search), $synonyms);
    }

    // getAllRequestedTalents
    public function getAllRequestedTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, null, true), $synonyms);
    }

    public function searchAllRequestedTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, null, true, null, null, $search), $synonyms);
    }

    // getSynonymsOfTalents
    public function getSynonymsOfTalents($talent)
    {
        return $this->createTalentArray($this->talentBuilder->getSynonymsOfTalents($talent));
    }


    // ###### Update ######

    // updateTalent
    public function updateTalent($name, $isRejected, $id, $admin = null)
    {
        if($admin == null) {
            $admin = (new Admin())->getCurrentAdmin()->username;
        }
        $this->talentBuilder->updateTalent($name, $isRejected, $id, $admin);
    }


    // ###### Delete ######

    // deleteTalentFromCurrentUser
    public function deleteTalent($talentId, $user = null)
    {
        if($user == null) {
            $user = $this->userRepo->getCurrentUser()->email;
        }
        $this->talentBuilder->deleteTalentFromUser($talentId,$user);
    }

    // deleteSynonymFromTalent
    public function deleteSynonym($talentId, $synonymId)
    {
        $this->talentBuilder->deleteSynonym($talentId, $synonymId);
    }

    public function getWishTalents(Wish $wish)
    {
        return $this->createTalentArray($this->talentBuilder->getWishTalents($wish));
    }

    // Helping methods
    private function createTalentArray($result, $synonym = null)
    {

        $returnArray = array();

        if (!Empty($result)) {

            foreach ($result as $item) {

                $talent = new Talent(
                    $item["Id"],
                    $item["Name"],
                    $item["CreationDate"],
                    $item["AcceptanceDate"],
                    $item["IsRejected"],
                    $item["moderator_Username"],
                    $item["user_Email"]
                );

                if ($synonym != null) {

                    $synonyms = $this->talentBuilder->getSynonyms($item["Id"]);

                    if (!Empty($synonyms)) {

                        foreach ($synonyms as $value) {

                            $talent->addSynonym($value["synonym_Id"], $this->talentBuilder->getTalents(null, null, null, $value["synonym_Id"])[0]["Name"]);
                        }
                    }
                }

                array_push($returnArray, $talent);
            }
        }

        return $returnArray;
    }
}