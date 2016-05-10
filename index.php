<?php
include_once("Includes/config.php");

if (!empty($_SESSION["user"])) {
    if ((new User)->isBlocked($_SESSION["user"]->email)) {
        (new AccountController())->logout();
    }
}

if (Empty($_GET["page"])) {
    (new HomeController())->run();
}

$page = strtolower(htmlspecialchars($_GET["page"]));
switch ($page) {
    case "account":
        (new AccountController())->run();
        break;
    case "profile":
        (new ProfileController())->run();
        break;
    case "admin":
        (new AdminController())->run();
        break;
    case "adminwish":
        (new AdminWishController())->run();
        break;
    case "dashboard":
        (new DashboardController())->run();
        break;
    case "survey":
        (new SurveyController())->run();
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
    case "admintalents":
        (new AdminTalentController())->run();
        break;
    case "profilecheck":
        (new ProfileCheckController())->run();
        break;
    case "index.php":
        (new HomeController())->run();
        break;
    case "about":
        (new HomeController())->run();
        break;
    case "match":
        (new MatchController())->run();
        break;
    case "report":
        (new ReportController())->run();
        break;
    case "adminuser":
        (new AdminUserController())->run();
        break;
    case "forbiddenwords":
        (new ForbiddenWordController())->run();
        break;
    default:
        apologize("Sorry. Pagina bestaat niet");
        break;
}