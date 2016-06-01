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
    public function addTalent($name, $userEmail = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        $this->talentBuilder->addTalent($name, $userEmail);
    }

    // addTalentToUser
    public function addTalentUser($talentId, $userEmail = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        $this->talentBuilder->addTalentToUser($talentId, $userEmail);
    }

    // addSynonym
    public function addSynonym($talentId, $synonymId)
    {
        $this->talentBuilder->addSynonym($talentId, $synonymId);
    }


    // ####### Read ######

    // get or search AllTalents
    public function getTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page), $synonyms);
    }

    public function searchTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, null, null, null, null, $search), $synonyms);
    }

    // get or search acceptedTalents
    public function getAcceptedTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, true), $synonyms);
    }

    public function searchAcceptedTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, true, null, null, null, null, null, null, null, $search), $synonyms);
    }

    // get or search unaddedTalents
    public function getUnaddedTalents($userEmail = null, $page = null, $synonyms = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, $userEmail), $synonyms);
    }

    public function searchUnaddedTalents($userEmail = null, $search, $page = null, $synonyms = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, $userEmail, null, null, null, null, null, null, $search), $synonyms);
    }

    // get or search AddedTalents (returns not denied talents added by User)
    public function getAddedTalents($userEmail = null, $page = null, $synonyms = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, $userEmail), $synonyms);

    }

    public function searchAddedTalents($userEmail = null, $search, $page = null, $synonyms = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, $userEmail, null, null, null, null, $search), $synonyms);
    }

    // getTalentById
    public function getTalent($id, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents(null, null, null, $id), $synonyms);
    }

    // get or search requested talents by user
    public function getRequestedTalents($userEmail = null, $page = null, $synonyms = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, $userEmail), $synonyms);

    }

    public function searchRequestedTalents($userEmail = null, $search, $page = null, $synonyms = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, $userEmail, null, null, null, $search), $synonyms);
    }

    // get or search all requested talents
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
    public function updateTalent($name, $isRejected, $id, $adminUsername = null)
    {
        if ($adminUsername == null) {
            $adminUsername = (new AdminRepository())->getCurrentAdmin()->username;
        }
        $this->talentBuilder->updateTalent($name, $isRejected, $id, $adminUsername);
    }


    // ###### Delete ######

    // delete talent from current user or given user
    public function deleteTalent($talentId, $userEmail = null)
    {
        if ($userEmail == null) {
            $userEmail = $this->userRepo->getCurrentUser()->email;
        }
        $this->talentBuilder->deleteTalentFromUser($talentId, $userEmail);
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
    // Prevents duplicate code
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