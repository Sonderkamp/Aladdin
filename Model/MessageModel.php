<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 18:51
 */
class messageModel
{

    private $page = 0;
    private $size = 4;
    private $pages = 0;

    public function getInbox($search, $page)
    {
        return $this->getbox($search, $page, "inbox");
    }

    private function getbox($search, $page, $folder)
    {
        $this->page = $page;
        $this->page--;

        if ($this->page < 0)
            $this->page = 0;

        $offset = $this->page * $this->size;


        $res = DATABASE::query_safe("SELECT count(*) as res FROM `inbox` WHERE `user_Email` = ? AND  `folder_name` = ?", array($_SESSION["user"]->email, $folder));
        if ($res[0]["res"] <= $offset)
            $this->page = 0;

        $this->pages = ceil($res[0]["res"] / $this->size);
        $offset = $this->page * $this->size;

        if ($search != "") {
            $res = DATABASE::query_safe("SELECT * FROM `inbox` left join `message` on
`inbox`.`message_Id`= `message`.`Id`  WHERE `user_Email` = ? AND  folder_name = ? order by `Date` Desc", array($_SESSION["user"]->email, $folder));

            $mess = $this->getMessages($res, $search);
            $mess = array_chunk($mess, $this->size);
            $this->pages = count($mess);

            if (empty($mess[$this->page]))
                return null;

            return $mess[$this->page];
        } else {
            $res = DATABASE::query_safe("SELECT * FROM `inbox` left join `message` on
`inbox`.`message_Id`= `message`.`Id` WHERE `user_Email` = ? AND  folder_name = ? order by `Date` Desc Limit $offset, $this->size", array($_SESSION["user"]->email, $folder));
            $mess = $this->getMessages($res, $search);
            return $mess;
        }
    }

    public function getOutbox($search, $page)
    {
        return $this->getbox($search, $page, "outbox");

    }

    public function getTrash($search, $page)
    {
        return $this->getbox($search, $page, "trash");
    }

    public function isValidPageInbox()
    {
        return [$this->page + 1, $this->pages];
    }

    public function isValidPageOutbox()
    {
        return [$this->page + 1, $this->pages];
    }

    public function isValidPageTrash()
    {
        return [$this->page + 1, $this->pages];
    }


    private function getMessages($dbres, $search)
    {
        $ret = [];
        setlocale(LC_TIME, 'Dutch');
        $User = new User();
        foreach ($dbres as $row) {

            $mesmodel = new Message();
            $mesmodel->date = strftime("%#d %B %Y", strtotime($row["Date"]));
            $mesmodel->isopened = $row["IsOpend"];
            $mesmodel->title = $row["Subject"];
            $mesmodel->content = substr($row["Message"], 0, 100);

            $pieces = explode("\n", $mesmodel->content);

            $mesmodel->content = $pieces[0];
            if (count($pieces) > 1) {
                $mesmodel->content .= $pieces[1];
            }
            if (count($pieces) > 2) {
                $mesmodel->content .= $pieces[2];
            }

            $mesmodel->content = htmlspecialcharsWithNL($mesmodel->content);

            $mesmodel->receiver = $User->getUser($row["user_Receiver"])["DisplayName"];
            if ($mesmodel->content != $row["Message"])
                $mesmodel->content .= "...";

            $mesmodel->id = $row["Id"];
            $mesmodel->adminSender = false;

            if (isset($row["moderator_Sender"])) {
                $mesmodel->adminSender = true;
                $mesmodel->sender = $row["moderator_Sender"];
            } else {
                $mesmodel->sender = $User->getUser($row["user_Sender"])["DisplayName"];
            }

            $links = DATABASE::query_safe("SELECT * FROM `messageLink` WHERE `message_Id` = ?", array($row["message_Id"]));
            if (count($links) > 0) {
                $mesmodel->links = [];
                foreach ($links as $link) {
                    $l = new MessageLink();
                    $l->content = $link["Content"];
                    $l->action = $link["Action"];
                    $mesmodel->links[] = $l;
                }
            }

            if ($search != "") {
                if (strrpos(strtolower($row["Message"]), strtolower($search)) !== false
                    || strrpos(strtolower($mesmodel->receiver), strtolower($search)) !== false
                    || strrpos(strtolower($mesmodel->sender), strtolower($search)) !== false
                    || strrpos(strtolower($mesmodel->title), strtolower($search)) !== false
                ) {


                    $ret[] = $mesmodel;
                }
            } else
                $ret[] = $mesmodel;
        }
        return $ret;
    }

    public function getMessage($messageID, $me)
    {
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `message_Id` = ? and `user_Email` = ? ", array($messageID, $me));
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
        $mesmodel->content = htmlspecialcharsWithNL($mesmodel->content);
        $mesmodel->folder = $res["folder_Name"];
        $mesmodel->receiver = $User->getUser($mess["user_Receiver"])["DisplayName"];

        $mesmodel->id = $res["Id"];
        $mesmodel->adminSender = false;

        if (isset($mess["moderator_Sender"])) {
            $mesmodel->adminSender = true;
            $mesmodel->sender = $mess["moderator_Sender"];
        } else {
            $mesmodel->sender = $User->getUser($mess["user_Sender"])["DisplayName"];
        }

        $links = DATABASE::query_safe("SELECT * FROM `messageLink` WHERE `message_Id` = ?", array($res["message_Id"]));
        if (count($links) > 0) {
            $mesmodel->links = [];
            foreach ($links as $link) {
                $l = new MessageLink();
                $l->content = $link["Content"];
                $l->action = $link["Action"];
                $mesmodel->links[] = $l;
            }
        }
        return $mesmodel;

    }

    public function connectMessage($me, $message)
    {
        $val = DATABASE::query_safe("SELECT count(*) as counter FROM `inbox` WHERE `Id` = ? AND `user_Email` = ?", array($message, $me));
        $val = $val[0];
        if ($val["counter"] == 1)
            return true;
        return false;
    }

    public function setLink($content, $action, $message)
    {
        DATABASE::query_safe("INSERT INTO `messageLink` ( `Action`, `message_Id`, `Content`) VALUES (?, ?, ?)", array($action, $message, $content));
    }

    public function moveTrash($message)
    {
        DATABASE::query_safe("UPDATE `inbox` SET `folder_Name` = 'trash' WHERE `inbox`.`Id` = ?", array($message));
    }

    public function deleteMessage($message)
    {
        DATABASE::query_safe("UPDATE `inbox` SET `folder_Name` = 'removed' WHERE `inbox`.`Id` = ?", array($message));
    }

    public function resetMessage($me, $message)
    {
        // get message from message
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `Id` = ? ", array($message));
        if ($res === false || $res === null || count($res) == 0)
            return false;

        $res = $res[0];
        $mess = DATABASE::query_safe("SELECT * FROM `message` WHERE `Id` = ?", array($res["message_Id"]));
        $mess = $mess[0];

        if ($mess["user_Receiver"] == $me) {
            DATABASE::query_safe("UPDATE `inbox` SET `folder_Name` = 'inbox' WHERE `inbox`.`Id` = ?", array($message));
        } else if ($mess["user_Sender"] == $me) {
            DATABASE::query_safe("UPDATE `inbox` SET `folder_Name` = 'outbox' WHERE `inbox`.`Id` = ?", array($message));
        }
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
        // TODO: Check if me equals admin
        if ($user->getUser($me) !== false) {
            DATABASE::transaction_action_safe($pdo, "INSERT INTO `message` (`Subject`, `Message`, `user_Sender`, `user_Receiver`) VALUES ( ?, ?, ?, ?)", array($title, $message, $me, $recipient));
            $itemNR = $pdo->lastInsertId();
            DATABASE::transaction_action_safe($pdo, "INSERT INTO `inbox` ( `folder_Name`, `message_Id`, `user_Email`) VALUES ('outbox', ?, ?)", array($itemNR, $me));
            // insert into outbox
        } else {
            DATABASE::transaction_action_safe($pdo, "INSERT INTO `message` (`Subject`, `Message`, `moderator_Sender`, `user_Receiver`) VALUES ( ?, ?, ?, ?)", array($title, $message, $me, $recipient));
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

        return $itemNR;

    }


}