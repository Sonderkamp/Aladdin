<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10/05/2016
 * Time: 16:04
 */

class SurveyController extends Controller
{
    public $survey;

    public function run()
    {
        $this->survey = new Survey();

        if(isset($_GET["submit"])){
            $this->handleSurveySubmit();
        } else {
            $this->showSurvey();
        }
    }

    private function handleSurveySubmit(){
        foreach($this->survey->questions as $item){
            if(isset($_POST["Question-".$item->id])){
                $givenAnswer = (new Answer())->getAnswer($_POST["Question-".$item->id]);
                $this->survey->surveyGroups[$givenAnswer->positiveGroup] += 1;
//                $this->survey->surveyGroups[$givenAnswer->negativeGroup] -= 1;
            } else {
                $this->apologize("U heeft vraag: " . $item->id . " niet beantwoord");
            }
        }
        $highestScore = max($this->survey->surveyGroups);
        $group = array_search($highestScore , $this->survey->surveyGroups);
        $this->showSurveyResults($group);
    }

    private function showSurvey()
    {
        $this->render("survey.tpl", ["title" => $_SESSION["user"]->displayName, "questions" => $this->survey->questions]);
    }


    private function showSurveyResults($group){
        $groupMessage = $this->survey->getSurveyText($group);
        $this->render("surveyResult.tpl", ["title" => $_SESSION["user"]->displayName , "groupMessage" => $groupMessage]);
    }

}