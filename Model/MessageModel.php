<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 18:51
 */
class messageModel
{
    public function getInbox($search)
    {
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `user_Email` = ? AND  folder_name = 'inbox' order by `Date` Desc ", array($_SESSION["user"]->email));
        return $this->getMessages($res, $search);
    }

    public function getOutbox($search)
    {
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `user_Email` = ? AND  folder_name = 'outbox' order by `Date` Desc ", array($_SESSION["user"]->email));
        return $this->getMessages($res, $search);
    }

    public function getTrash($search)
    {
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `user_Email` = ? AND  folder_name = 'trash' order by `Date` Desc ", array($_SESSION["user"]->email));
        return $this->getMessages($res, $search);
    }

    private function getMessages($dbres, $search)
    {
        $ret = [];
        setlocale(LC_TIME, 'Dutch');
        $User = new User();
        foreach ($dbres as $row) {
            $mess = DATABASE::query_safe("SELECT * FROM `message` WHERE `Id` = ?", array($row["message_Id"]));
            $mess = $mess[0];

            $mesmodel = new Message();
            $mesmodel->date = strftime("%#d %B %Y", strtotime($row["Date"]));
            $mesmodel->isopened = $row["IsOpend"];
            $mesmodel->title = $mess["Subject"];
            $mesmodel->content = substr($mess["Message"], 0, 100);

            $pieces = explode("\n", $mesmodel->content);

            $mesmodel->content = $pieces[0];
            if(count($pieces) > 1)
            {
                $mesmodel->content .=  $pieces[1];
            }
            if(count($pieces) > 2)
            {
                $mesmodel->content .=  $pieces[2];
            }

            $mesmodel->receiver = $User->getUser($mess["user_Receiver"])["DisplayName"];
            if ($mesmodel->content != $mess["Message"])
                $mesmodel->content .= "...";

            $mesmodel->id = $row["Id"];
            $mesmodel->adminSender = false;

            if (isset($mess["moderator_Sender"])) {
                $mesmodel->adminSender = true;
                $mesmodel->sender = $mess["moderator_Sender"];
            } else {
                $mesmodel->sender = $User->getUser($mess["user_Sender"])["DisplayName"];
            }


            if ($search != "") {
                if (strrpos(strtolower($mess["Message"]), strtolower($search)) !== false
                    || strrpos(strtolower($mesmodel->receiver), strtolower($search)) !== false
                    || strrpos(strtolower($mesmodel->sender), strtolower($search)) !== false
                    || strrpos(strtolower($mesmodel->title), strtolower($search)) !== false
                )
                    $ret[] = $mesmodel;
            } else
                $ret[] = $mesmodel;
        }
        return $ret;
    }

    public function getMessage($messageID)
    {
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `Id` = ? ", array($messageID));
        if ($res === false || $res === null || count($res) == 0)
            return false;

        $User = new User();
        $res = $res[0];
        $mess = DATABASE::query_safe("SELECT * FROM `message` WHERE `Id` = ?", array($res["message_Id"]));
        $mess = $mess[0];

        $mesmodel = new Message();
        $mesmodel->date = strftime(" %H:%M %#d %B %Y", strtotime($res["Date"]));
        $mesmodel->isopened = $res["IsOpend"];
        $mesmodel->title = $mess["Subject"];
        $mesmodel->content = $mess["Message"];
        $mesmodel->receiver = $User->getUser($mess["user_Receiver"])["DisplayName"];

        $mesmodel->id = $res["Id"];
        $mesmodel->adminSender = false;

        if (isset($mess["moderator_Sender"])) {
            $mesmodel->adminSender = true;
            $mesmodel->sender = $mess["moderator_Sender"];
        } else {
            $mesmodel->sender = $User->getUser($mess["user_Sender"])["DisplayName"];
        }

        return $mesmodel;

    }

    public function checkblock($me, $recipient)
    {
        $res = DATABASE::query_safe("SELECT count(*) as counter FROM `blockedUsers` WHERE `user_Blocker` = ? and `user_Blocked` = ?", array($me, $recipient));
        $res = $res[0];
        if ($res["counter"] != 0)
            return "Je hebt deze gebruiker geblokkeerd.";

        $res = DATABASE::query_safe("SELECT count(*) as counter FROM `blockedUsers` WHERE `user_Blocker` = ? and `user_Blocked` = ?", array($recipient, $me));
        $res = $res[0];
        if ($res["counter"] != 0)
            return "Deze gebruiker heeft u geblokkeerd.";

        return false;
    }

    public function sendMessage($me, $recipient, $title, $message)
    {

        // DATABASE
        $user = new User();
        $pdo = DATABASE::getPDO();
        $pdo->beginTransaction();
        $itemNR = null;

        if ($user->getUser($me) !== false) {
            DATABASE::transaction_action_safe($pdo, "INSERT INTO `message` (`Subject`, `Message`, `user_Sender`, `user_Receiver`) VALUES ( ?, ?, ?, ?)", array($title, nl2br($message), $me, $recipient));
            $itemNR = $pdo->lastInsertId();
            DATABASE::transaction_action_safe($pdo, "INSERT INTO `inbox` ( `folder_Name`, `message_Id`, `user_Email`) VALUES ('outbox', ?, ?)", array($itemNR, $me));
            // insert into outbox
        } else {
            DATABASE::transaction_action_safe($pdo, "INSERT INTO `message` (`Subject`, `Message`, `moderator_Sender`, `user_Receiver`) VALUES ( ?, ?, 'mariodv@hotmail.nl', 'mbeekman1@avans.nl')", array($title, $message, $me, $recipient));
            $itemNR = $pdo->lastInsertId();
        }
        // insert into recipient mailbox
        DATABASE::transaction_action_safe($pdo, "INSERT INTO `inbox` ( `folder_Name`, `message_Id`, `user_Email`) VALUES ('inbox', ?, ?)", array($itemNR, $recipient));

        $pdo->commit();

        // EMAIL
        $user = new User();
        $user = $user->getUser($recipient);
        $mail = new Email();
        $mail->to = $recipient;
        $mail->toName = $user["Name"] . " " . $user["Surname"];;
        $mail->subject = "Aladdin:  " . $title;
        $mail->message = "Dit bericht is verstuurd via " . $_SERVER["SERVER_NAME"] . ": \nReageren kan via de website. Mailtjes naar dit emailadress worden niet gezien.\n\n" . $message;
        $mail->sendMail();

    }
}