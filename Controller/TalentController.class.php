<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:15
 */
class TalentController
{
    public function run()
    {
        /*$result = Database::query("select * from talent where gebruiker = 'gebruiker'");
        $var = "success";
        if($result === false || $result == null){
            $var =  "fuck jou";
        }*/

        $talents = array();
        $talents[0] = new Talent("Joost" , "PHP");
        $talents[1] = new Talent("Joost" , "Slagwerk");
        $talents[2] = new Talent("Joost" , "Guinness");

        render("talentOverview.php", ["title" => "Talenten", "talents" => $talents]);
        exit(1);
    }
}