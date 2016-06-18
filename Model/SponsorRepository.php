<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 04-06-16
 * Time: 16:15
 */
class SponsorRepository
{

    private $sponsorQB, $userRepo;

    public function __construct()
    {
        $this->sponsorQB = new SponsorQueryBuilder();
        $this->userRepo = new UserRepository();
    }

    public function getAllSponsors()
    {
        $result = $this->sponsorQB->getAllSponsors();
        return $this->sponsorCreator($result);
    }

    public function addSponsor(Sponsor $sponsor)
    {
        if ($sponsor != null) {
            $this->sponsorQB->addSponsor($this->addUserEmail($sponsor));
        }
    }

    public function updateSponsor(Sponsor $sponsor)
    {
        $user = $this->userRepo->getUser($sponsor->userMail);
        $sponsor->userMail = $user->email;

        if ($sponsor != null) {
            $this->sponsorQB->updateSponsor($this->addUserEmail($sponsor));
        }
    }

    public function addUserEmail(Sponsor $sponsor)
    {
        $user = $this->userRepo->getUser($sponsor->userMail);
        $sponsor->userMail = $user->email;
        return $sponsor;
    }

    public function deleteSponsor(Sponsor $sponsor)
    {
        if ($sponsor != null) {
            $this->sponsorQB->deleteSponsor($this->addUserEmail($sponsor));
        }
    }

    public function searchSponsor($searchKey)
    {
        $result = $this->sponsorQB->getAllSponsors($searchKey);
        return $this->sponsorCreator($result);
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