<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9-2-2016
 * Time: 15:23
 */
class Email
{
    public $from, $fromName, $subject, $message, $to, $toName;

    public function __construct()
    {
        $this->from = "webs2eindopdracht@gmail.com";
        $this->fromName = "Webs2 Webshop";
        $this->to = "webs2eindopdracht@gmail.com";
        $this->toName = "Webs2 Webshop";
        $this->subject = "not set.";
        $this->message = "not set.";

    }

    public function sendMail()
    {
        if( $this->from == $this->to)
        {
            echo "Mailer Error: Receipient not set";
            exit();
        }

        $mail = new PHPMailer;

        //Set PHPMailer to use SMTP.
        $mail->isSMTP();
        //Set SMTP host name
        $mail->Host = "smtp.gmail.com";
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //Provide username and password
        $mail->Username = EMAILADRESS;
        $mail->Password = EMAILWACHTWOORD;
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";
        //Set TCP port to connect to
        $mail->Port = 587;

        $mail->From = $this->from;
        $mail->FromName = $this->fromName;

        $mail->addAddress($this->to , $this->toName);

        $mail->isHTML(false);

        $mail->Subject = $this->subject;
        $mail->Body = $this->message;

        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            exit();
        }
    }
}