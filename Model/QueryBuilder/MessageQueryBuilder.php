<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 12-5-2016
 * Time: 12:39
 */
class MessageQueryBuilder
{


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

    public function getbox($search, &$page, $size, $folder, &$pages)
    {

        $page--;

        if ($page < 0)
            $page = 0;

        $offset = $page * $size;


        $res = DATABASE::query_safe("SELECT count(*) as res FROM `inbox` WHERE `user_Email` = ? AND  `folder_name` = ?", array($_SESSION["user"]->email, $folder));
        if ($res[0]["res"] <= $offset)
            $page = 0;

        $pages = ceil($res[0]["res"] / $size);
        $offset = $page * $size;

        if ($search != "") {
            $res = DATABASE::query_safe("SELECT * , `inbox`.`Id` as 'inbId' FROM `inbox` left join `message` on
`inbox`.`message_Id`= `message`.`Id`  WHERE `user_Email` = ? AND  folder_name = ? order by `Date` Desc", array($_SESSION["user"]->email, $folder));

            $mess = $this->getMessages($res, $search);
            $mess = array_chunk($mess, $size);
            $pages = count($mess);

            if (empty($mess[$page]))
                return null;

            return $mess[$page];
        } else {
            $res = DATABASE::query_safe("SELECT *, `inbox`.`Id` as 'inbId' FROM `inbox` left join `message` on
`inbox`.`message_Id`= `message`.`Id` WHERE `user_Email` = ? AND  folder_name = ? order by `Date` Desc Limit $offset, $size", array($_SESSION["user"]->email, $folder));
            $mess = $this->getMessages($res, $search);
            return $mess;
        }
    }

    public function getMessage($messageID, $me)
    {
        $res = DATABASE::query_safe("SELECT * FROM `inbox` WHERE `Id` = ? and `user_Email` = ? ", array($messageID, $me));
        if ($res === false || $res === null || count($res) == 0)
            return false;

        $User = new UserRepository();
        $res = $res[0];
        $mess = DATABASE::query_safe("SELECT * FROM `message` WHERE `Id` = ?", array($res["message_Id"]));
        $mess = $mess[0];

        $mesmodel = new Message();
        $mesmodel->date = strftime(" %H:%M %e %B %Y", strtotime($res["Date"]));
        $mesmodel->isopened = $res["IsOpend"];
        $mesmodel->title = $mess["Subject"];
        $mesmodel->content = $mess["Message"];
        $mesmodel->content = htmlspecialcharsWithNL($mesmodel->content);
        $mesmodel->folder = $res["folder_Name"];
        $mesmodel->receiver = $User->getUser($mess["user_Receiver"])->displayName;

        $mesmodel->id = $res["Id"];
        $mesmodel->adminSender = false;

        if (isset($mess["moderator_Sender"])) {
            $mesmodel->adminSender = true;
            $mesmodel->sender = $mess["moderator_Sender"];
        } else {
            $mesmodel->sender = $User->getUser($mess["user_Sender"])->displayName;
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

    private function getMessages($dbres, $search)
    {
        $ret = [];
        setlocale(LC_TIME, 'Dutch');
        $User = new UserRepository();
        foreach ($dbres as $row) {

            $mesmodel = new Message();
            $mesmodel->date = strftime("%e %B %Y", strtotime($row["Date"]));
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

            $mesmodel->receiver = $User->getUser($row["user_Receiver"])->displayName;
            if ($mesmodel->content != $row["Message"])
                $mesmodel->content .= "...";

            $mesmodel->id = $row["inbId"];
            $mesmodel->adminSender = false;

            if (isset($row["moderator_Sender"])) {
                $mesmodel->adminSender = true;
                $mesmodel->sender = $row["moderator_Sender"];
            } else {
                $mesmodel->sender = $User->getUser($row["user_Sender"])->displayName;
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

    public function sendMessage($me, $recipient, $title, $message)
    {
        $user = new UserRepository();
        $pdo = DATABASE::getPDO();
        $pdo->beginTransaction();
        $itemNR = null;

        if ($user->getUser($me) !== null) {
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

        return $itemNR;
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

    public function deleteMessagesUser($username)
    {
        DATABASE::query_safe("UPDATE `inbox` SET `folder_Name` = 'removed' WHERE `inbox`.`user_Email` = ?", array($username));
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
}