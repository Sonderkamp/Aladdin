<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $talents, $talents_user, $talent_repository;

    public function __construct()
    {
        $this->talent_repository = new TalentRepository();
        $this->talents = $this->talent_repository->getTalentsWithoutAdded();
        $this->talents_user = $this->talent_repository->getUserTalents();
    }

    public function run()
    {
        // comment dit uit als je wil dat de pagina een inlog-restrictie heeft
        guaranteeLogin("/Talents");

        $this->checkPosts();

        render("talentOverview.php", ["title" => "Talenten", "talents" => $this->talents, "user_talents" => $this->talents_user]);
        exit(0);
    }

    private function checkPosts()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
}