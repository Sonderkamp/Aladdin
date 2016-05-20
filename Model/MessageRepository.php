<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 18:51
 */
class messageRepository
{

    private $page = 0;
    private $size = 4;
    private $pages = 0;
    private $messageBuilder;


    function __construct()
    {
        $this->messageBuilder = new MessageQueryBuilder();
    }


    public function getbox($search, $page, $box)
    {
        $pages = 0;
        $messages = $this->messageBuilder->getbox($search, $page, $this->size, $box, $pages);
        $this->page = $page;
        $this->pages = $pages;

        return $messages;
    }
    

    public function isValidPage()
    {
        return [$this->page + 1, $this->pages];
    }

    public function getMessage($messageID, $me)
    {
        return $this->messageBuilder->getMessage($messageID, $me);
    }


    public function connectMessage($me, $message)
    {
        return $this->messageBuilder->connectMessage($me, $message);
    }

    public function setLink($content, $action, $message)
    {
        $this->messageBuilder->setLink($content, $action, $message);
    }

    public function moveTrash($message)
    {
        $this->messageBuilder->moveTrash($message);
    }

    public function deleteMessage($message)
    {
        $this->messageBuilder->deleteMessage($message);
    }

    public function resetMessage($me, $message)
    {
        $this->messageBuilder->resetMessage($me, $message);
    }

    public function checkblock($me, $recipient)
    {
        return $this->messageBuilder->checkblock($me, $recipient);
    }

    public function sendMessage($me, $recipient, $title, $message)
    {

        $itemNR = $this->messageBuilder->sendMessage($me, $recipient, $title, $message);

        // EMAIL
        $user = new UserRepository();
        $user = $user->getUser($recipient);
        $mail = new Email();
        $mail->to = $user->email;
        $mail->toName = $user->name . " " . $user->surname;
        $mail->subject = "Aladdin:  " . $title;
        $mail->message = "Dit bericht is verstuurd via " . $_SERVER["SERVER_NAME"] . ": \nReageren kan via de website. Mailtjes naar dit emailadress worden niet gezien.\n\n" . $message;
        $mail->sendMail();

        return $itemNR;

    }


}