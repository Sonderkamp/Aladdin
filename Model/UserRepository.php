<?php
/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 26-04-16
 * Time: 16:39
 */

class UserRepository
{
    

    public function getUser($email)
    {
        $result = Database::query_safe("SELECT * FROM user WHERE user.Email = ?", array($email));

        $newUser = new User();
        $newUser->email = $email;
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