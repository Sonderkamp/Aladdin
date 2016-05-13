<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 25-4-2016
 * Time: 11:55
 */
class ForbiddenWordRepository
{
    private $wordBuilder;
    
    public function __construct()
    {
        $this->wordBuilder = new ForbiddenWordQueryBuilder();
    }

    // ###### CREATE ######
    public function createForbiddenWord($word) {
        $this->wordBuilder->createForbiddenWord($word);
    }
    
    // ###### READ ######
    public function getForbiddenWords($page = null, $search = null) {
        return $this->createReturnArray($this->wordBuilder->getForbiddenWords($page, null, $search));
    }
    
    public function getForbiddenWord($word) {
        return $this->createReturnArray($this->wordBuilder->getForbiddenWords(null, $word));
    }
    
    // ###### UPDATE ######
    public function updateForbiddenWord($oldWord, $newWord) {
        $this->wordBuilder->updateForbiddenWord($oldWord, $newWord);
    }
    
    // ###### DELETE ######
    public function deleteForbiddenWord($word) {
        $this->wordBuilder->deleteForbiddenWord($word);
    }

    // True als hij niet fout is, False als het woord niet goedgekeurt is
    public function isValid($word) {

        return Empty($this->wordBuilder->getForbiddenWords(null,strtolower($word)));
    }

    public function createReturnArray($result) {

        if(!Empty($result)) {

            $returnArray = array();

            foreach($result as $item) {

                array_push($returnArray, $item["Word"]);
            }

            return $returnArray;
        } else {
            
            return null;
        }
    }
}