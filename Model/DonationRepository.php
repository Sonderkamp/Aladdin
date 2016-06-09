<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 5-6-2016
 * Time: 17:15
 */
class DonationRepository
{

    public $mollie, $donateQB;

    public function __construct()
    {
        $this->mollie = new Mollie_API_Client;
        $this->mollie->setApiKey(MOLLIE_ID);
        $this->donateQB = new DonateQueryBuilder();
    }

    public function newDonation($amount, $name, $description, $user, $anonymous)
    {
        try {
            // create payment
            $_SESSION["payment"] = $this->mollie->payments->create(
                array(
                    'amount' => $amount,
                    'description' => 'Donatie aan Aladdin',
                    'redirectUrl' => 'http://' . $_SERVER["SERVER_NAME"] . '/donate'
                )


            );

            if ($user instanceof User) {
                $user = $user->email;
                $name = null;

            } else {
                $user = null;

                if (empty($name)) {
                    return;
                }

            }

            // Save ID to database WITH user if possible and set status to new
            $this->donateQB->addDonation($_SESSION["payment"]->id, $amount, $name, $description, $anonymous, $user, $_SERVER['REMOTE_ADDR']);

        } catch (Mollie_API_Exception $e) {
            echo "Doneer API heeft gefaald : " . htmlspecialchars($e->getMessage());
            echo " op veld: " . htmlspecialchars($e->getField());
            exit();
        }
    }

    public function getCurrentDonation()
    {

        if (empty($_SESSION["payment"])) {
            return null;
        }
        return $_SESSION["payment"];
    }

    public function resetCurrentDonation()
    {
        $_SESSION["payment"] = null;
    }

    public function getPayment($id)
    {
        return $this->mollie->payments->get($id);
    }

    public function closeDonation($id)
    {
        // DATABASE SET PAID
        $this->donateQB->setPaid($id);

    }

    public function getDonations($email = null)
    {

        return $this->resultsToDonations($this->donateQB->getDonations($email));

    }

    private function resultsToDonations($array)
    {

        $result = [];

        foreach ($array as $item) {

            $userParams = array("Email", "Name", "DisplayName", "Surname", "Address",
                "Postalcode", "Country", "City", "Dob", "Gender", "Handicap");
            $userCheck = true;

            foreach ($userParams as $param) {
                if (!isset($item[$param])) {
                    $userCheck = false;
                    break;
                }
            }

            $donation = new Donation();

            $donation->id = $item["PaymentId"];
            $donation->amount = $item["Amount"];
            $donation->name = $item["dName"];
            $donation->description = $item["Description"];
            $donation->anonymous = $item["Anonymous"];
            $donation->IP = $item["IP"];
            $donation->date = strftime(" %H:%M %#d %B %Y", strtotime($item["Date"]));

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
                $donation->user = $user;
            }

            $result[] = $donation;
        }

        return $result;


    }
}