<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10/05/2016
 * Time: 16:36
 */
class Survey
{
    public $questions, $surveyGroups;

    public function __construct(){
        $this->createSurvey();
    }

    private function createSurvey(){
        $this->questions = (new Question())->getAllQuestions();
        $this->surveyGroups = $this->getSurveyGroups();
    }

    private function getSurveyGroups(){
        $result = Database::query("SELECT * FROM `surveygroups`");

        if(!empty($result)){

            $returnArray = array();

            foreach($result as $item){
                $index = $item["Group"];
                $returnArray[$index] = 0;
            }

            return $returnArray;
        }

        return null;
    }
}