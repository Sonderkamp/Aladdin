<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 04-06-16
 * Time: 16:15
 */
class SponsorRepository
{

    private $sponsorQB;

    public function __construct()
    {
        $this->sponsorQB = new SponsorQueryBuilder();
    }

    /** Getters */

    public function getAllSponsors()
    {
        $result = $this->sponsorQB->getAllSponsors();
        return $this->sponsorCreator($result);
    }


    public function addSponsor(Sponsor $sponsor)
    {
        if ($sponsor != null) {
            $this->sponsorQB->addSponsor($sponsor);
        }
    }
    
    public function deleteSponsor(Sponsor $sponsor){
        if($sponsor != null){
            $this->sponsorQB->deleteSponsor($sponsor);
        }
    }

    public function sponsorCreator($result)
    {
        if (count($result) === 0) return null;

        if (count($result) === 1) {
            return $this->createSponsor($result);
        } else {
            return $this->createSponsors($result);
        }
    }

    public function createSponsors($result)
    {
        $sponsors = array();
        foreach ($result as $item) {
            $sponsors[] = $this->createSponsor(array($item));
        }

        return $sponsors;
    }

    public function createSponsor($result)
    {
        if ($result == null || $result == false || count($result) == 0) {
            return false;
        }

        $sponsor = new Sponsor();
        $sponsor->id = $result[0]["Id"];
        $sponsor->name = $result[0]["Name"];
        $sponsor->image = $result[0]["Image"];
        $sponsor->description = $result[0]["Description"];

        $sponsor->url = $result[0]["WebsiteLink"];

        if (isset($result[0]["user_Email"])) {
            $sponsor->userMail = $result[0]["user_Email"];
        }

        return $sponsor;
    }

}