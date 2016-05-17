<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10/05/2016
 * Time: 16:36
 */
class Answer
{
    public $positiveGroup , $negativeGroup, $answerContent;

    public function getAllAnswers($questionId){
        $result = Database::query_safe("SELECT * FROM `answer` WHERE question_Id = ?", array($questionId));
        $returnArray = array();

        foreach($result as $item){
            $answer = new Answer();
            $answer->answerContent = $item["AnswerContent"];
            $answer->negativeGroup = $item["NegativeGroup"];
            $answer->positiveGroup = $item["PositiveGroup"];

            $returnArray[] = $answer;
        }

        return $returnArray;
    }

    public function getAnswer($answerContent){
        $result = Database::query_safe("SELECT * FROM `answer` WHERE AnswerContent = ?", array($answerContent));
        $newObject = new Answer();
        $newObject->answerContent = $result[0]["AnswerContent"];
        $newObject->positiveGroup = $result[0]["PositiveGroup"];
        $newObject->negativeGroup = $result[0]["NegativeGroup"];
        return $newObject;
    }
}