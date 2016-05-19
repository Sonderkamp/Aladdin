<?php
include_once("Includes/config.php");


// auto logout
if (!empty($_SESSION["user"])) {
    if ((new UserRepository())->isBlocked($_SESSION["user"]->email)) {
        (new AccountController())->logout();
    }
}

// choose controller
if (Empty($_GET["page"])) {
    (new HomeController())->run();
}
$page = ucfirst(strtolower(htmlspecialchars($_GET["page"]))) . "Controller";


if (class_exists($page)) {
    $controller = (new $page());
} else {
    (new HomeController())->run();
    exit();
}

if (Empty($_GET["action"]) || !method_exists($controller, $_GET["action"])) {
    $controller->run();
    exit();
}

$controller->$_GET["action"]();