<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/02/2016
 * Time: 18:20
 */
class WishRepository
{

    private $talentRepository, $userRepository, $WishQueryBuilder, $admin;

    public $wishLimit = 3;

    public function __construct()
    {
        $this->WishQueryBuilder = new WishQueryBuilder();
        $this->talentRepository = new TalentRepository();
        $this->userRepository = new UserRepository();
        $this->admin = new Admin();
    }

    /**
     * Creates array of wish objects with the params given in the $queryResult.
     * And adds the user elements to the given objects
     * It is used to prevent duplicate code.
     * @param $queryResult
     * @return array
     */
    private function getReturnArray($queryResult)
    {
        if (!empty($queryResult)) {
            $returnArray = array();

            foreach ($queryResult as $item) {

                $userParams = array("Email", "Name", "DisplayName", "Surname", "Address",
                    "Postalcode", "Country", "City", "Dob", "Gender", "Handicap");
                $userCheck = true;

                foreach ($userParams as $param) {
                    if (!isset($item[$param])) {
                        $userCheck = false;
                        break;
                    }
                }

                $wish = new Wish();

                $wish->id = $item["wish_Id"];
                $wish->title = $item["Title"];
                $wish->content = $item["Content"];
                $wish->accepted = $item["IsAccepted"];
                $wish->contentDate = $item["Date"];
                $wish->status = $item["Status"];

                if ($userCheck) {
                    $user = new User();
                    $user->email = $item[$userParams[0]];
                    $user->name = $item[$userParams[1]];
                    $user->displayName = $item[$userParams[2]];
                    $user->surname = $item[$userParams[3]];
                    $user->address = $item[$userParams[4]];
                    $user->postalcode = $item[$userParams[5]];
                    $user->country = $item[$userParams[6]];
                    $user->city = $item[$userParams[7]];
                    $user->dob = $item[$userParams[8]];
                    $user->gender = $item[$userParams[9]];
                    $user->handicap = $item[$userParams[10]];
                    $wish->user = $user;
                }

                $returnArray[] = $wish;
            }
            return $returnArray;
        }
        return false;
    }


    /**
     * @return array of wishes where user == current user and status != "Verwijderd"
     */
    public function getMyWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes
        ($this->userRepository->getCurrentUser()->email, [0 => "Aangemaakt",
            1 => "Gepubliseerd",
            2 => "Geweigerd",
            3 => "Match gevonden",
            4 => "Vervuld",
            5 => "Wordt vervuld"]));
    }

    /**
     * @return array of wishes where status == "vervuld" or "wordt vervuld"
     */
    public function getCurrentCompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes([0 => "Vervuld", 1 => "Wordt vervuld"], null, false));
    }

    public function getMyCompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes($this->userRepository->getCurrentUser()->email, [0 => "Vervuld", 1 => "Wordt vervuld"], null));
    }

    /**
     * @return array of wishes where status != "vervuld"
     */
    public function getIncompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Gepubliseerd", 1 => "Match gevonden"], null, false));
    }

    /**
     * @param $key
     * @return array
     * Searches if title or content SOUNDSLIKE $key
     */
    public function searchMyWishes($key)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes($this->userRepository->getCurrentUser()->email, null, $key));
    }


    public function addWish(Wish $wish)
    {
        $this->WishQueryBuilder->addWish();

        $temp = $this->WishQueryBuilder->getLatestWish();
        $wish->id = $temp[0]["Id"];

        $this->addWishContent($wish);
        $this->editWishTalents($wish);
    }

    private function addWishContent(Wish $wish)
    {
        $this->WishQueryBuilder->addWishContent($wish);
    }

    public function editWishContent(Wish $wish)
    {
        $temp = $this->WishQueryBuilder->getSingleWish($wish->id);
        if ($temp[0]["moderator_Username"] === null) {
            $this->WishQueryBuilder->deleteWishContent($wish);
        }

        $this->WishQueryBuilder->addWishContent($wish);
        $this->editWishTalents($wish);
    }

    public function editWishTalents(Wish $wish)
    {
        $talents = $this->talentRepository->getAddedTalents();

        $tags = array();
        foreach ($talents as $talent) {
            $tags[] = $talent->name;
        }

        if (is_array($wish->tags)) {
            $this->deleteWishTalents($wish);
            foreach ($wish->tags as $item) {
                if (!in_array($item, $tags)) {
                    $this->talentRepository->addTalent($item);
                }
                $this->bindToTalent($item, $wish);

//                if (in_array($item, $tags)) {
//                    $this->bindToTalent($item, $wish);
//                } else {
//                    $this->talentRepository->addTalent($item);
//                    $this->bindToTalent($item, $wish);
//                }
            }
        }
    }

    public function deleteWishTalents(Wish $wish)
    {
        $this->WishQueryBuilder->deleteWishTalents($wish);
    }

    public function bindToTalent($talentName, Wish $wish)
    {
        $this->WishQueryBuilder->bindToTalent($talentName, $wish);
    }


    /**
     * check if user has less then 3 wishes
     * @param $email
     * @return bool
     */
    public function canAddWish($email)
    {
        // kortere notatie voor de if/else :)
        return $this->getWishAmount($email) < $this->wishLimit;
    }

    public function getWishAmount($email)
    {
        $wishByUser = $this->getWishesByUser($email);
        if (!empty($wishByUser)) {
            return count($wishByUser);
        } else {
            return 0;
        }
    }

    public function getRequestedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Aangemaakt", 1 => "Gepublieerd"], null, true));
    }

    public function getPublishedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Gepubliseerd"], null, null));
    }

    public function getMatchedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Match gevonden"], null, true));
    }

    public function getPossibleMatches()
    {
        $userTalents = $this->talentRepository->getAddedTalents();
        $synonyms = $this->talentRepository->getSynonymsOfTalents($userTalents);
        $allTalents = array_merge($userTalents, $synonyms);


        $temp = $this->WishQueryBuilder->wishIDByTalents($allTalents);
        $result = $this->WishQueryBuilder->getPossibleMatches($temp, $this->getMyWishes());
        return $this->getReturnArray($result);


//        return $this->wishesByTalents($allTalents);
    }

    public function getCurrentWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Wordt vervuld"], null, null));
    }

    public function getCompletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Vervuld"], null, null));
    }

    public function getDeniedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Geweigerd"], null, null));
    }

    public function getDeletedWishes()
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes(null, [0 => "Verwijderd"], null, null));
    }

    public function acceptWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 1, $this->admin->getCurrentAdmin()->username, "Gepubliseerd");
    }

    public function refuseWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 0, $this->admin->getCurrentAdmin()->username, "Geweigerd");
    }

    public function deleteWish($id)
    {
        $this->WishQueryBuilder->executeAdminAction($id, 0, $this->admin->getCurrentAdmin()->username, "Verwijderd");
    }

    public function getWish($id)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getSingleWish($id, null))[0];
    }


    public function getWishesByUser($username)
    {
        return $this->getReturnArray($this->WishQueryBuilder->getWishes
        ($username, [0 => "Aangemaakt",
            1 => "Gepubliseerd",
            2 => "Geweigerd",
            3 => "Match gevonden",
            5 => "Wordt vervuld"], null, null, true));
    }

    // nog even laten staan, kan wss binnekort verwijdert worden
    public function wishesByTalents($talents)
    {
        $temp = $this->WishQueryBuilder->wishIDByTalents($talents);
        $result = $this->WishQueryBuilder->getPossibleMatches($temp, $this->getMyWishes());
        return $this->getReturnArray($result);
    }

    public function deleteMyWish($id)
    {
        $this->WishQueryBuilder->editWishStatus($id, "Verwijderd");
    }

    public function sendEditMail($id, $titel, $content, $tags)
    {
        $mail = new Email();
        $mail->fromName = "Alladin";
        $mail->subject = "Wens is gewijzigd";
        $mail->message = $this->createMessage($titel, $content, $tags);
        $mail->to = $this->userRepository->getCurrentUser()->email;
//      $mail->sendMail();
//      MARIUS: Nu krijg je 2 mailtjes. Zie inhoud sendMessage.

        $newmail = new messageRepository();
        $msgID = $newmail->sendMessage("Admin", $mail->to, $mail->subject, $mail->message);
        $newmail->setLink($id, "Wens", $msgID);
    }

    public function createMessage($titel, $content, $tags)
    {
        $head = "Beste, \n\n email test";
        $msg = "Uw wensweiziging is ingediend, uw wens zal na goedkeuring zichtbaar zijn voor anderen, we houden u hiervan nog op de hoogte.\n\n";
        $wish = "Uw nieuwe wens is als volgt: \n";
        $wishName = "Naam van de wens: \t\t" . $titel . " \n";
        $wishDescription = "Beschrijving van de wens: \t" . $content . "\n";
        $allTagsForMail = implode(' #', $tags);
        $wishTags = "Uw tags zijn: \t\t\t\t#" . $allTagsForMail . "\n\n";
        $end = "Vriendelijke groeten, \n\n Alladin";

        $message = $head . $msg . $wish . $wishName . $wishDescription . $wishTags . $end;
        return $message;
    }

    public function getComments($wishID)
    {
        return $this->WishQueryBuilder->getComments($wishID);
    }

    public function addComment($comment, $wishID, $user, $img = null)
    {

        // if user can comment this wish (alleen als de wens klaar is && de gebruiker de wenser is of de match)
        // TODO


        // if there has been a comment < 3 minutes ago
//        $res = $this->WishQueryBuilder->lastCommentMinutes($wishID, $user);
//        if ($res < 3 && $res != -1) {
//            return "U moet minstens drie minuten wachten tussen reacties.";
//        }

        if (strlen(trim($comment)) <= 1) {
            $comment = null;
        } else {
            // validate comment
            $forbiddenRepo = new ForbiddenWordRepository();
            $wordArray = explode(" ", trim(preg_replace("/[^0-9a-z]+/i", " ", $comment)));
            foreach ($wordArray as $word) {

                // if word is not valid
                if (!$forbiddenRepo->isValid($word)) {
                    return "Er zijn woorden in uw reactie die niet zijn toegestaan. Eerste verboden woord dat is gevonden: " . $word;
                }
            }
        }


        if ($img != null) {
            if (!empty($img['tmp_name']))
            {


                $url = $this->upload($img);

                if ($url == null) {
                    return "Er is een invalide bestand meegegeven. Alleen foto bestanden worden geaccepteerd tot 10MB";
                }
            }
        }

        // Add comment
        $this->WishQueryBuilder->addComment($comment, $wishID, $user, $url);

        // get email from wish creator
        $wish = $this->getWish($wishID);

        if ($wish->user->email != $user->email) {
            // Set mail
            $mail = new Email();
            $mail->fromName = "Alladin";
            $mail->subject = "Nieuwe Reactie";
            $mail->message = "Hallo " . $wish->user->name . ", \n Er is gereageerd op uw wens. Ga naar de website om uw wens te bekijken.";
            $mail->to = $wish->user->email;

            $newmail = new messageRepository();
            $msgID = $newmail->sendMessage($user->email, $mail->to, $mail->subject, $mail->message);
            $newmail->setLink($wishID, "Wens", $msgID);
        }


    }

    private function upload($img)
    {


        // if to big
        if ($img["size"] > 10000000) {
            return null;
        }

        $client_id = "c21da4b5fe373f9";
        $image = file_get_contents($img['tmp_name']);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        $reply = curl_exec($ch);

        // DIE if imgur breaks.
        if (curl_errno($ch)) {
            echo "IMGUR ERROR. Please try again: ";
            print_r(curl_error($ch));
            exit();
        }

        curl_close($ch);


        $reply = json_decode($reply);

        if ($reply != null && $reply->data != null && $reply->data->link != null) {
            return $reply->data->link;
        }
        return null;

    }

}