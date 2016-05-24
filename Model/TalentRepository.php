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
    private $talentBuilder;

    // Constants
    public $TALENT_MINIMUM = 3;

    // Constructor
    public function __construct()
    {
        $this->talentBuilder = new TalentQueryBuilder();
    }

    // ###### Create ######

    // addTalent
    public function addTalent($name)
    {
        $this->talentBuilder->addTalent($name);
    }

    // addTalentToUser
    public function addTalentUser($talentId, $user = null)
    {
        $this->talentBuilder->addTalentToUser($talentId, $user);
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
    public function getUnaddedTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, true), $synonyms);
    }

    public function searchUnaddedTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, true, null, null, null, null, null, null, $search), $synonyms);
    }

    // getAddedTalents
    public function getAddedTalents($page = null, $synonyms = null) {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, true), $synonyms);
    }

    public function searchAddedTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, true, null, null, null, null, $search), $synonyms);
    }

    // getTalentById
    public function getTalent($id, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents(null, null, null, $id), $synonyms);
    }

    // getTalentUser
    public function getTalentsUser($user, $page = null, $synonyms = null) {

        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, null, null, $user), $synonyms);
    }

    public function searchTalentsUser($user, $search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, null, null, $user, null, $search), $synonyms);
    }

    // getTalentsRequestedByCurrentUser
    public function getRequestedTalents($page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, true), $synonyms);
    }

    public function searchRequestedTalents($search, $page = null, $synonyms = null)
    {
        return $this->createTalentArray($this->talentBuilder->getTalents($page, null, null, null, null, true, null, null, null, $search), $synonyms);
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
    public function updateTalent($name, $isRejected, $id)
    {
        $this->talentBuilder->updateTalent($name, $isRejected, $id);
    }


    // ###### Delete ######

    // deleteTalentFromCurrentUser
    public function deleteTalent($talentId)
    {
        $this->talentBuilder->deleteTalentFromUser($talentId);
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