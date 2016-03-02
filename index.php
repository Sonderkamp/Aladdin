<?php
include_once("Includes/config.php");
// CHANGE

if (Empty($_GET["page"])) {
    (new HomeController())->run();
}

$page = strtolower(htmlspecialchars($_GET["page"]));
switch ($page) {
    case "account":
        (new AccountController())->run();
        break;
    case "admin":
        (new AdminController())->run();
        break;
    case "wishes":
        (new WishController())->run();
        break;
    case "inbox":
        (new MailController())->run();
        break;
    case "talents":
        (new TalentController())->run();
        break;
    case "index.php":
        (new HomeController())->run();
        break;
    case "about":
        (new HomeController())->run();
        break;
    default:
        apologize("Sorry. Pagina bestaat niet");
        break;
}