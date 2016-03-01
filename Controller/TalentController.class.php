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
        /*$result = Database::query("select * from talent where gebruiker = 'gebruiker'");
        $var = "success";
        if($result === false || $result == null){
            $var =  "fuck jou";
        }*/

        $this->talents = array();
        $this->talents[0] = new Talent("Joost" , "PHP");
        $this->talents[1] = new Talent("Joost" , "Slagwerk");
        $this->talents[2] = new Talent("Joost" , "Een hele lange tekst na een vraag van Max over resizing hoe dat eruit gaat zien waar we ook erg benieuwd naar zijn");

        if(!Empty($_POST["talent"]))
        {
            $this->deleteValue($_POST["talent"]);
        }

        render("talentOverview.php", ["title" => "Talenten", "talents" => $this->talents]);
        exit(1);
    }

    public function deleteValue($talent)
    {
        $talent2 = new Talent("Joost",$talent);

        if (($key = array_search($talent2, $this->talents)) !== false)
        {
            unset($this->talents[$key]);
        }

        render("talentOverview.php", ["title" => "Talenten", "talents" => $this->talents]);
        exit(1);
    }
}