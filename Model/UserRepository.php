<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 26-04-16
 * Time: 16:39
 */
class UserRepository
{

    public function getUser($emailOrDisplayName)
    {
        $result = Database::query_safe("SELECT * FROM user WHERE user.Email = ? OR user.DisplayName = ?", array($emailOrDisplayName, $emailOrDisplayName));
        return $this->createUser($result);
    }

//        $newUser = new User();
//        $newUser->email = $email;
//        $newUser->isAdmin = $result[0]["Admin"];
//        $newUser->name = $result[0]["Name"];
//        $newUser->surname = $result[0]["Surname"];
//        $newUser->address = $result[0]["Address"];
//        $newUser->handicap = $result[0]["Handicap"];
//        $newUser->postalcode = $result[0]["Postalcode"];
//        $newUser->country = $result[0]["Country"];
//        $newUser->city = $result[0]["City"];
//        $newUser->dob = $result[0]["Dob"];
//        $newUser->gender = $result[0]["Gender"];
//        $newUser->displayName = $result[0]["DisplayName"];
//        $newUser->initials = $result[0]["Initials"];
//
//        return $newUser;


    public function blockUser($email)
    {
        Database::query_safe("INSERT INTO adminBlock (`IsBlocked`, `Reason`, `moderator_Username`, `user_Email`) VALUES (1, 'xxxxx', 'Admin', ?)", array($email));
    }

    public function createUser($result)
    {
        $newUser = new User();

        $newUser->email = $result[0]["Email"];
        $newUser->isAdmin = $result[0]["Admin"];
        $newUser->name = $result[0]["Name"];
        $newUser->surname = $result[0]["Surname"];
        $newUser->address = $result[0]["Address"];
        $newUser->handicap = $result[0]["Handicap"];
        $newUser->postalcode = $result[0]["Postalcode"];
        $newUser->country = $result[0]["Country"];
        $newUser->city = $result[0]["City"];
        $newUser->dob = $result[0]["Dob"];
        $newUser->gender = $result[0]["Gender"];
        $newUser->displayName = $result[0]["DisplayName"];
        $newUser->initials = $result[0]["Initials"];

        return $newUser;
    }

}