<?php

class MailController
{

    private $messageModel;
    private $error = null;
    private $search = null;
    private $title = "";
    private $page = 1;

    public function run()
    {
        $this->messageModel = new messageRepository();
        guaranteeLogin("/Inbox");

        // page logic
        if (!empty($_GET["p"])) {
            $_GET["p"] = intval($_GET["p"]);
            if (!is_int($_GET["p"])) {
                $this->page = 1;
            } else {
                if ($_GET["p"] < 1)
                    $this->page = 1;
                $this->page = $_GET["p"];
            }
        } else
            $this->page = 1;


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Empty($_GET["action"])) {
                switch (strtolower($_GET["action"])) {
                    case "new":
                        $this->sendNewMessage();
                        redirect("/Inbox/folder=outbox/p=1");
                    default:
                        render("inbox.tpl", ["title" => "Inbox", "folder" => "Postvak in", "page" => $this->page]);
                        break;
                }
                exit();
            } else {
                if (isset($_POST["reply"])) {
                    $this->reply();
                } else if (isset($_POST["delete"])) {

                    $this->delete();
                } else if (isset($_POST["trash"])) {
                    $this->moveTrash();

                } else if (isset($_POST["reset"])) {
                    $this->undoDelete();
                }


            }
        } else {


            if (!Empty($_GET["action"])) {
                switch (strtolower($_GET["action"])) {
                    case "new":
                        // get all DisplayNames
                        $userRepo = new UserRepository();
                        $names = $userRepo->getAllMatchedDislaynames($_SESSION["user"]);
                        if (($key = array_search($_SESSION["user"]->displayName, $names)) !== false) {
                            unset($names[$key]);
                        }
                        render("newMessage.tpl", ["title" => "Inbox", "folder" => "Nieuw bericht", "names" => $names]);
                        break;
                    default:
                        render("inbox.tpl", ["title" => "Inbox", "folder" => "Postvak in", "page" => $this->messageModel->isValidPage()]);
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

                $this->loadMessage();
            }

            $this->renderInbox();
        }
        $this->renderInbox();
        exit(2);
    }

    public function loadMessage()
    {
        $message = $_GET["message"];
        if (filter_var($message, FILTER_VALIDATE_INT) === false) {
            $this->renderInbox();
        }

        // get message
        $message = $this->messageModel->getMessage($message, $_SESSION["user"]->email);

        if ($message === false) {
            $this->error = "Bericht bestaat niet.";
            $this->renderInbox();
        }

        $folder = "Postvak in";
        $folderShortcut = "inbox";
        $this->setFolder($folder, $folderShortcut);
        render("message.tpl", ["page" => $this->page, "title" => "Inbox", "folder" => $folder . $this->title, "folderShortcut" => $folderShortcut, "message" => $message, "error" => $this->error, "search" => $this->search]);
        exit();
    }

    public function undoDelete()
    {
        if (filter_var($_POST["reset"], FILTER_VALIDATE_INT) === false) {
            $this->error = "Invalide parameter meegegeven.";
            $this->renderInbox();
        }
        if ($this->messageModel->connectMessage($_SESSION["user"]->email, $_POST["reset"]) === false) {
            $this->error = "Het is niet mogelijk om andermans berichten te verwijderen.";
            $this->renderInbox();
        }
        $this->messageModel->resetMessage($_SESSION["user"]->email, $_POST["reset"]);
        $this->renderInbox();
    }

    public function reply()
    {
        if (filter_var($_POST["reply"], FILTER_VALIDATE_INT) === false) {
            $this->error = "Invalide parameter meegegeven.";
            $this->renderInbox();
        }

        $message = $this->messageModel->getMessage($_POST["reply"], $_SESSION["user"]->email);
        if ($message === false) {
            $this->error = "Bericht bestaat niet.";
            $this->renderInbox();
        }

        $userRepo = new UserRepository();
        $names = $userRepo->getAllMatchedDislaynames($_SESSION["user"]);
        if (($key = array_search($_SESSION["user"]->displayName, $names)) !== false) {
            unset($names[$key]);
        }

        $message->content = "\n\n\n-------------------------------\n Origineel: \n-------------------------------\n" . $message->content;
        $message->content = str_replace("<br />", "\n", $message->content);
        render("newMessage.tpl", ["title" => "Inbox", "folder" => "Nieuw bericht", "message" => $message, "names" => $names]);
        exit();
    }

    public function moveTrash()
    {
        if (filter_var($_POST["trash"], FILTER_VALIDATE_INT) === false) {
            $this->error = "Invalide parameter meegegeven.";
            $this->renderInbox();
        }
        if ($this->messageModel->connectMessage($_SESSION["user"]->email, $_POST["trash"]) === false) {
            $this->error = "Het is niet mogelijk om andermans berichten te verwijderen.";
            $this->renderInbox();
        }
        $this->messageModel->moveTrash($_POST["trash"]);
        $this->renderInbox();
    }

    public function delete()
    {
        if (filter_var($_POST["delete"], FILTER_VALIDATE_INT) === false) {
            $this->error = "Invalide parameter meegegeven.";
            $this->renderInbox();
        }
        if ($this->messageModel->connectMessage($_SESSION["user"]->email, $_POST["delete"]) === false) {
            $this->error = "Het is niet mogelijk om andermans berichten te verwijderen.";
            $this->renderInbox();
        }
        $this->messageModel->deleteMessage($_POST["delete"]);
        $this->renderInbox();
    }

    private function setFolder(&$folder, &$folderShortcut)
    {
        if (!Empty($_GET["folder"])) {
            switch (strtolower($_GET["folder"])) {
                case "inbox":
                    $folder = "Postvak in";
                    $folderShortcut = "inbox";
                    break;
                case "outbox":
                    $folder = "Postvak uit";
                    $folderShortcut = "outbox";
                    break;
                case "trash":
                    $folder = "Prullenbak";
                    $folderShortcut = "trash";
                    break;
                default:
                    break;
            }
        }
    }

    public function renderInbox()
    {
        $folder = "Postvak in";
        $folderShortcut = "inbox";
        $this->setFolder($folder, $folderShortcut);

        render("inbox.tpl", ["title" => "Inbox", "folder" => $folder . $this->title, "folderShortcut" => $folderShortcut, "messages" => $this->messageModel->getbox($this->search, $this->page, $folderShortcut), "error" => $this->error, "search" => $this->search, "page" => $this->messageModel->isValidPage()]);
        exit();
    }

    public function sendNewMessage()
    {
        $userRepo = new UserRepository();
        $names = $userRepo->getAllDislaynames();
        if (($key = array_search($_SESSION["user"]->displayName, $names)) !== false) {
            unset($names[$key]);
        }

        if (empty($_POST["recipient"]) ||
            empty($_POST["title"]) ||
            empty($_POST["message"])
        ) {
            render("newMessage.tpl", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => "Niet alles is ingevuld.", "names" => $names]);
            exit();
        }
        $_POST["recipient"] = trim($_POST["recipient"]);
        $_POST["title"] = trim($_POST["title"]);
        $_POST["message"] = trim($_POST["message"]);

        if (empty($_POST["recipient"]) ||
            empty($_POST["title"]) ||
            empty($_POST["message"])
        ) {
            render("newMessage.tpl", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => "Niet alles is ingevuld.", "names" => $names]);
            exit();
        }

        $userRepo = new UserRepository();
        $username = $userRepo->getUser($_POST["recipient"])->email;

        if ($username === false) {
            render("newMessage.tpl", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => "Gebruiker bestaat niet", "names" => $names]);
            exit();
        }

        // check if there is a block
        $mes = new messageRepository();
        $res = $mes->checkblock($_SESSION["user"]->email, $username);

        if ($res !== false) {
            render("newMessage.tpl", ["title" => "Inbox", "folder" => "Nieuw bericht", "error" => $res, "names" => $names]);
            exit();
        }

        // send message
        $mes->sendMessage($_SESSION["user"]->email, $username, $_POST["title"], $_POST["message"]);

    }

}
