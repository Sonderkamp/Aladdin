<?php

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 8-3-2016
 * Time: 17:23
 */
class DonateController extends Controller
{


    public $donateRepo, $userRepo;

    public function __construct()
    {
        // (new AccountController())->guaranteeLogin("/Profile");
        $this->donateRepo = new DonationRepository();
        $this->userRepo = new UserRepository();
    }

    public function run()
    {


        try {
            // close  payment
            if (!empty($this->donateRepo->getCurrentDonation())) {

                $id = $this->donateRepo->getCurrentDonation()->id;
                $payment = $this->donateRepo->getPayment($id);

                if ($payment->isPaid()) {

                    $this->donateRepo->resetCurrentDonation();

                    // save in database
                    $this->donateRepo->closeDonation($id);

                    $this->render("donate.tpl", ["title" => "Aladdin", "success" => "Bedankt voor uw donatie!"]);
                    exit();
                } else {

                    $this->donateRepo->resetCurrentDonation();
                    $this->render("donate.tpl", ["title" => "Aladdin", "error" => "Vorige donatie is niet goed afgerond. wacht even en bekijk uw bankrekening voordat u opnieuw doneert."]);
                    exit();
                }

            }

            // Show donate page
            $this->render("donate.tpl", ["title" => "Aladdin"]);
            exit(0);

        } catch (Mollie_API_Exception $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
            echo " on field " . htmlspecialchars($e->getField());
        }
    }


    public function add()
    {


        $anon = 0;
        if (!empty($_POST["anonymous"])) {
            $anon = 1;
        }

        // get variables min="3" step="0.01"
        if (empty($_POST["quantity"]) || !($_POST["quantity"] >= 3 && $_POST["quantity"] < 1000000) || !is_numeric($_POST["quantity"]) || empty($_POST["name"])) {
            $this->render("donate.tpl", ["title" => "Aladdin", "error" => "geen geldige waarde ingevoerd."]);
            exit();
        }

        $this->donateRepo->newDonation($_POST["quantity"], $_POST["name"], $_POST["description"], $this->userRepo->getCurrentUser(), $anon);

        $donation = $this->donateRepo->getCurrentDonation();


        if ($donation == null) {
            $this->render("donate.tpl", ["title" => "Aladdin", "error" => "geen geldige waarde ingevoerd."]);
            exit();
        }
        // redirect
        header("Location: " . $donation->getPaymentUrl());
        exit;


    }


}