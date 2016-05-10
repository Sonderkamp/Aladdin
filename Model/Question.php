<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10/05/2016
 * Time: 16:27
 */
class Question
{
    public $id, $questionContent, $answers;

    public function getAllQuestions(){
        $result = Database::query("SELECT * FROM `question`");
        return $this->getReturnArray($result);
    }

    private function getReturnArray($result){
        $returnArray = array();

        foreach($result as $item){
            $question = new Question();
            $question->id = $item["Id"];
            $question->questionContent = $item["QuestionContent"];
            $question->answers = (new Answer())->getAllAnswers($item["Id"]);
            $returnArray[] = $question;
        }
        return $returnArray;
    }
}