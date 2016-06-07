<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 05-06-16
 * Time: 11:46
 */
class DonateQueryBuilder
{
    public function addDonation($id, $amount, $name, $description, $anonymous, $user, $ip)
    {
        Database::query_safe("INSERT INTO `donation`(`PaymentId`, `Amount`, `Name`, `Description`, `Anonymous`, `user_Email`, `IP`) VALUES (?, ?, ?, ?, ?, ?, ?);",
            array($id, $amount, $name, $description, $anonymous, $user, $ip));
    }

    public function setPaid($id)
    {
        Database::query_safe("UPDATE `donation` set Paid = 1 where PaymentID = ?",
            array($id));
    }

    public function getDonations($email)
    {
        if ($email != null) {
            return Database::query_safe("SELECT *, `donation`.`Name` as dName from `donation` left join `user` on `user_Email` = `email` where Paid = 1 and `email` = ? And Anonymous = 0 order by `Date` Desc", array($email));
        }
        return Database::query("SELECT *, `donation`.`Name` as dName from `donation` left join `user` on `user_Email` = `email` where Paid = 1 order by `Date` Desc;");
    }

}