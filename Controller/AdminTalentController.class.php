<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 6-4-2016
 * Time: 14:25
 */
class AdminTalentController
{
    private $message_model, $page, $talents, $all_talents, $unaccepted_talents, $current_all_talents_number, $all_talents_number, $talent_repository;

    public function __construct()
    {
        guaranteeAdmin("admintalents");

        $this->page = "m";
        $this->talent_repository = new TalentRepository();
        $this->message_model = new MessageModel();

        $this->talents = $this->talent_repository->getAllTalents(false);
        $this->all_talents_number = ceil($this->talent_repository->checkNumberOfAllTalents()/10);
        $this->unaccepted_talents = $this->talent_repository->getAllRequestedTalents();
    }

    public function run()
    {
        $this->checkPost();
        $this->checkGet();
        $this->checkSession();

        render("Admin/talent.tpl",
            ["title" => "Talenten beheer",
                "all_talents" => $this->all_talents,
                "all_talent_number" => $this->all_talents_number,
                "current_all_talents_number" => $this->current_all_talents_number,
                "unaccepted_talents" => $this->unaccepted_talents,
                "talents" => $this->talents]);
        exit(0);
    }

    private function checkSession()
    {
        if(!Empty($_SESSION["talent_admin"])){
            if($this->all_talents_number > 1){
                $this->current_all_talents_number = $_SESSION["talent_admin"];
                $this->all_talents = $this->talent_repository->getAllTalents($_SESSION["talent_admin"]);
            } else{
                $_SESSION["talent_admin"] = $this->all_talents_number;
            }
        }
    }

    private function checkGet()
    {
        if (!Empty($_GET["admin_a"])) {
            if($_GET["admin_a"] > 0 & $_GET["admin_a"] <= $this->all_talents_number) {
                $this->all_talents = $this->talent_repository->getAllTalents($_GET["admin_a"]);
                $this->current_all_talents_number = $_GET["admin_a"];
                $_SESSION["talent_admin"] = $this->current_all_talents_number;
            } else{
                $this->all_talents = $this->talent_repository->getAllTalents(1);
                $this->current_all_talents_number = 1;
                $_SESSION["talent_admin"] = $this->current_all_talents_number;
            }
        } else {
            $this->all_talents = $this->talent_repository->getAllTalents(1);
            $this->current_all_talents_number = 1;
        }
    }

    private function checkPost()
    {
        if (!Empty($_POST["admin_talent_name"]) && !Empty($_POST["admin_talent_id"])) {

            if(strlen($_POST["admin_talent_name"]) > 0 && strlen($_POST["admin_talent_name"]) <= 45){
                $correct = true;
                foreach($this->talent_repository->getAllTalentsName() as $name_of_talent){
                    if(strtolower($name_of_talent) == strtolower($_POST["admin_talent_name"])){
                        //De ingevoegde naam is al toegevoegd, aangevraagd of geweigerd.
                        $correct = false;
                        break;
                    }
                }
                if($correct == true){
                    if(!preg_match('/[^a-z\s]/i', $_POST["admin_talent_name"])) {
                        $this->talent_repository->updateTalentName($_POST["admin_talent_name"], $_POST["admin_talent_id"]);
                    } else{
                        //Er mogen alleen letters en spaties worden gebruikt in het talent!
                    }
                }
            }

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }

        if(!Empty($_POST["deny_message"]) && !Empty($_POST["deny_id"])){
            $this->talent_repository->rejectTalent($_POST["deny_id"]);

            $talent = $this->talent_repository->getTalentById($_POST["deny_id"]);

            $message_id = $this->message_model->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is afgewezen", $_POST["deny_message"]);
            $this->message_model->setLink("", "Talent", $message_id);

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }

        if(!Empty($_POST["accept_id"])){
            $this->talent_repository->acceptTalent($_POST["accept_id"]);

            $talent = $this->talent_repository->getTalentById($_POST["accept_id"]);
            $this->talent_repository->addTalentToUser2($_POST["accept_id"],$talent->user_email);

            $message_id = $this->message_model->sendMessage("Admin", $talent->user_email, "Het talent '" . $talent->name . "' is geaccepteerd", "Het talent '" . $talent->name . "' is geaccepteerd, omdat het voldoet aan de algemene voorwaarden. Het talent is toegevoegt aan 'mijn talenten'.");
            $this->message_model->setLink("", "Talent", $message_id);

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }
    }
}