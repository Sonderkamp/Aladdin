<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 3-2-2016
 * Time: 19:02
 */
class User
{
    public $email, $isAdmin, $name, $surname, $token, $address, $hash, $RecoveryHash, $RecoveryDate,
        $handicap, $postalcode, $country, $city, $dob, $gender, $displayName, $initials, $blocked, $companyName, $guardian, $handicapInfo, $lat, $lon;


    public function checkPassword($password)
    {
        $result = Database::query_safe("SELECT password from user where email = ?", array($this->email));
        $result = $result[0];
        return password_verify($password, $result["password"]);
    }


}