<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    // TODO: TABELLEN GROUPEREN (bootstrap pills)
    private $talents, $talents_user, $talent_repository, $talent_numbers, $current_talent_number, $user_talents_number, $current_user_talent_number;

    public function __construct()
    {
        guaranteeLogin("/Talents");

        $this->talent_repository = new TalentRepository();
        $this->talents = $this->talent_repository->getTalentsWithoutAdded();
        $this->talents_user = $this->talent_repository->getUserTalents();
        $this->user_talents_number = ceil($this->talent_repository->checkNumberOfTalentsFromUser()/10);
        $this->talent_numbers = ceil($this->talent_repository->checkNumberOfTalents()/10);
    }

    public function run()
    {
        $this->checkPost();
        $this->checkGet();

        render("talentOverview.php",
            ["title" => "Talenten",
                "talents" => $this->talents,
                "user_talents" => $this->talents_user,
                "number_of_talents" => $this->talent_repository->checkNumberOfTalentsFromUser(),
                "talent_error" => "set",
                "user_talents_number" => $this->user_talents_number,
                "current_user_talent_number" => $this->current_user_talent_number,
                "talent_number" => $this->talent_numbers,
                "current_talent_number" => $this->current_talent_number]);
        exit(0);
    }

    private function checkPost()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_POST["talent_name"])) {
                $this->talent_repository->addTalent($_POST["talent_name"]);

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }

            if (!Empty($_POST["remove_id"])) {
                $this->talent_repository->deleteTalentFromUser($_POST["remove_id"]);

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }
            else if (!Empty($_POST["add_id"])) {
                $this->talent_repository->addTalentToUser($_POST["add_id"]);

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
    }

    private function checkGet()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!Empty($_GET["show_added_talents"])) {
                if($_GET["show_added_talents"] > 0 & $_GET["show_added_talents"] <= $this->user_talents_number) {
                    $this->talents_user = $this->talent_repository->getSelectionUserTalents($_GET["show_added_talents"]);
                    $this->current_user_talent_number = $_GET["show_added_talents"];
                }
                else{
                    $this->talents_user = $this->talent_repository->getSelectionUserTalents(1);
                    $this->current_user_talent_number = 1;
                }
            }
            else {
                $this->talents_user = $this->talent_repository->getSelectionUserTalents(1);
                $this->current_user_talent_number = 1;
            }

            if (!Empty($_GET["show_talents"])) {
                if($_GET["show_talents"] > 0 & $_GET["show_talents"] <= $this->talent_numbers) {
                    $this->talents = $this->talent_repository->getSelectionTalents($_GET["show_talents"]);
                    $this->current_talent_number = $_GET["show_talents"];
                }
                else{
                    $this->talents = $this->talent_repository->getSelectionTalents(1);
                    $this->current_talent_number = 1;
                }
            }
            else {
                $this->talents = $this->talent_repository->getSelectionTalents(1);
                $this->current_talent_number = 1;
            }
        }
    }
}