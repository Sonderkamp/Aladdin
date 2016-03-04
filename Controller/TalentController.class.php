<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    private $talents;

    public function run()
    {
        // comment dit uit als je wil dat de pagina een inlog-restrictie heeft
        guaranteeLogin("/Talents");

        $this->talents = array();
        $this->talents[0] = new Talent($_SESSION["user"]->email, "PHP");
        $this->talents[1] = new Talent($_SESSION["user"]->email, "Slagwerk");
        $this->talents[2] = new Talent($_SESSION["user"]->email, "Een hele lange tekst na een vraag van Max over resizing hoe dat eruit gaat zien waar we ook erg benieuwd naar zijn");

        if (!Empty($_POST["talent"])) {
            $this->deleteValue($_POST["talent"]);

            header("HTTP/1.1 303 See Other");
            header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit(0);
        }

        render("talentOverview.php", ["title" => "Talenten", "talents" => $this->talents]);
        exit(0);
    }

    public function deleteValue($talent)
    {
        $talent2 = new Talent($_SESSION["user"]->email, $talent);

        if (($key = array_search($talent2, $this->talents)) !== false) {
            unset($this->talents[$key]);
        }
    }
}