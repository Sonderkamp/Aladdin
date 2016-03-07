<?php

class MailController
{

    private $messageModel;
    private $error = null;
    private $search = null;
    private $title = "";

    public function run()
    {
        $this->messageModel = new messageModel();
        guaranteeLogin("/Inbox");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_GET["action"])) {
                switch (strtolower($_GET["action"])) {
                    case "new":
                        $this->sendNewMessage();
                        redirect("/Inbox/folder=outbox");
                    default:
                        render("inbox.php", ["title" => "Inbox", "folder" => "Postvak in"]);
                        break;
                }
                exit();
            }
            else
            {
                // reply
                if(isset($_POST["reply"]))
                {
                    if (filter_var($_POST["reply"], FILTER_VALIDATE_INT) === false) {
                        $this->error = "Invalide parameter meegegeven.";
                        $this->renderInbox();
                    }

                    $message = $this->messageModel->getMessage($_POST["reply"]);
                    if ($message === false) {
                        $this->error = "Bericht bestaat niet.";
                        $this->renderInbox();
                    }

                    $user = new User();
                    $names = $user->getAllDislaynames();
                    if (($key = array_search($_SESSION["user"]->displayName, $names)) !== false) {
                        unset($names[$key]);
                    }

                    $message->content = "\n\n\n-------------------------------\n Origineel: \n-------------------------------\n" .$message->content;
                    $message->content = str_replace("<br />", "\n", $message->content);
                    render("newMessage.php", ["title" => "Inbox", "folder" => "Nieuw bericht", "message" => $message, "names" => $names ]);

                    exit();

                }
                else if(isset($_POST["delete"]))
                {
                    if (filter_var($_POST["delete"], FILTER_VALIDATE_INT) === false) {
                        $this->error = "Invalide parameter meegegeven.";
                        $this->renderInbox();
                    }
                    if($this->messageModel->connectMessage($_SESSION["user"]->email, $_POST["delete"]) === false)
                    {
                        $this->error = "Het is niet mogelijk om andermans berichten te verwijderen.";
                        $this->renderInbox();
                    }
                    $this->messageModel->deleteMessage($_POST["delete"]);
                    $this->renderInbox();
                }
                else if(isset($_POST["trash"]))
                {
                    if (filter_var($_POST["trash"], FILTER_VALIDATE_INT) === false) {
                        $this->error = "Invalide parameter meegegeven.";
                        $this->renderInbox();
                    }
                    if($this->messageModel->connectMessage($_SESSION["user"]->email, $_POST["trash"]) === false)
                    {
                        $this->error = "Het is niet mogelijk om andermans berichten te verwijderen.";
                        $this->renderInbox();
                    }
                    $this->messageModel->moveTrash($_POST["trash"]);
                    $this->renderInbox();

                }
                else if(isset($_POST["reset"]))
                {
                    if (filter_var($_POST["reset"], FILTER_VALIDATE_INT) === false) {
                        $this->error = "Invalide parameter meegegeven.";
                        $this->renderInbox();
                    }
                    if($this->messageModel->connectMessage($_SESSION["user"]->email, $_POST["reset"]) === false)
                    {
                        $this->error = "Het is niet mogelijk om andermans berichten te verwijderen.";
                        $this->renderInbox();
                    }
                    $this->messageModel->resetMessage($_SESSION["user"]->email, $_POST["reset"]);
                    $this->renderInbox();
                }


            }
        } else {


            if (!Empty($_GET["action"])) {
                switch (strtolower($_GET["action"])) {
                    case "new":
                        // get all DisplayNames
                        $user = new User();
                        $names = $user->getAllDislaynames();
                        if (($key = array_search($_SESSION["user"]->displayName, $names)) !== false) {
                            unset($names[$key]);
                        }
                        render("newMessage.php", ["title" => "Inbox", "folder" => "Nieuw bericht", "names" => $names]);
                        break;
                    default:
                        render("inbox.php", ["title" => "Inbox", "folder" => "Postvak in"]);
                        break;
                }
                exit();
            }
            if (!Empty($_GET["search"])) {
                if (strlen($_GET["search"]) < 3) {
                    $this->error = "Zoekcriteria moet minimaal 3 characters lang zijn.";
                } else {
                    $this->search = $_GET["search"];
                }
            }

            if (!Empty($_GET["message"])) {

                $message = $_GET["message"];
                if (filter_var($message, FILTER_VALIDATE_INT) === false) {
                    $this->renderInbox();
                }

                // get message
                $message = $this->messageModel->getMessage($message);

                if ($message === false) {
                    $this->error = "Bericht bestaat niet.";
                    $this->renderInbox();
                }

                if (!Empty($_GET["folder"])) {
                    switch (strtolower($_GET["folder"])) {
                        case "inbox":
                            render("message.php", ["title" => "Inbox", "folder" => "Postvak in" . $this->title, "folderShortcut" => "inbox", "in" => true, "message" => $message, "error" => $this->error, "search" => $this->search]);
                            break;
                        case "outbox":
                            render("message.php", ["title" => "Inbox", "folder" => "Postvak uit" . $this->title, "out" => true, "folderShortcut" => "outbox", "message" => $message, "error" => $this->error, "search" => $this->search]);
                            break;
                        case "trash":
                            render("message.php", ["title" => "Inbox", "folder" => "Prullenbak" . $this->title, "trash" => true,"folderShortcut" => "trash" ,"message" => $message, "error" => $this->error, "search" => $this->search]);
                            break;
                        default:
                            render("message.php", ["title" => "Inbox", "folder" => "Postvak in" . $this->title, "folderShortcut" => "inbox", "message" => $message, "error" => $this->error, "search" => $this->search]);
                            break;
                    }
                    exit();
                }
                render("message.php", ["title" => "Inbox", "folder" => "Postvak in" . $this->title, "folderShortcut" => "inbox", "in" => true, "message" => $message, "error" => $this->error, "search" => $this->search]);
                exit();
            }

            $this->renderInbox();
        }
        $this->renderInbox();
        exit(2);
    }

    public function renderInbox()
    {
        if (!Empty($_GET["folder"])) {
            switch (strtolower($_GET["folder"])) {
                case "inbox":
                    render("inbox.php", ["title" => "Inbox", "folder" => "Postvak in" . $this->title, "in" => true, "folderShortcut" => "inbox", "messages" => $this->messageModel->getInbox($this->search), "error" => $this->error, "search" => $this->search]);
                    break;
                case "outbox":
                    render("inbox.php", ["title" => "Inbox", "folder" => "Postvak uit" . $this->title, "out" => true, "folderShortcut" => "outbox", "messages" => $this->messageModel->getOutbox($this->search), "error" => $this->error, "search" => $this->search]);
                    break;
                case "trash":
                    render("inbox.php", ["title" => "Inbox", "folder" => "Prullenbak" . $this->title, "trash" => true,"folderShortcut" => "trash", "messages" => $this->messageModel->getTrash($this->search), "error" => $this->error, "search" => $this->search]);
                    break;
                default:
                    render("inbox.php", ["title" => "Inbox", "folder" => "Postvak in" . $this->title, "folderShortcut" => "inbox", "messages" => $this->messageModel->getInbox($this->search), "error" => $this->error, "search" => $this->search]);
                    break;
            }
            exit();
        }
        render("inbox.php", ["title" => "Inbox", "folder" => "Postvak in" . $this->title, "in" => true, "folderShortcut" => "inbox", "messages" => $this->messageModel->getInbox($this->search), "error" => $this->error, "search" => $this->search]);
        exit();
    }

    public function sendNewMessage()
    {
        $user = new User();
        $names = $user->getAllDislaynames();
        if (($key = array_search($_SESSION["user"]->displayName, $names)) !== false) {
            unset($names[$key]);
        }

        if (empty($_POST["recipient"]) ||
            empty($_POST["title"]) ||
            empty($_POST["message"])
        ) {
            render("newMessage.php", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => "Niet alles is ingevuld." , "names" => $names]);
            exit();
        }
        $_POST["recipient"] = trim($_POST["recipient"]);
        $_POST["title"] = trim($_POST["title"]);
        $_POST["message"] = trim($_POST["message"]);

        if (empty($_POST["recipient"]) ||
            empty($_POST["title"]) ||
            empty($_POST["message"])
        ) {
            render("newMessage.php", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => "Niet alles is ingevuld." , "names" => $names]);
            exit();
        }

        $user = new User();
        $username = $user->getUsername($_POST["recipient"]);

        if ($username === false) {
            render("newMessage.php", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => "Gebruiker bestaat niet" , "names" => $names]);
            exit();
        }

        // check if there is a block
        $mes = new messageModel();
        $res = $mes->checkblock($_SESSION["user"]->email, $username);

        if ($res !== false) {
            render("newMessage.php", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => $res , "names" => $names]);
            exit();
        }

        // send message
        $mes->sendMessage($_SESSION["user"]->email, $username, $_POST["title"], $_POST["message"]);

    }

}
