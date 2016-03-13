<?php
include_once("Includes/config.php");


if (Empty($_GET["page"])) {
    (new HomeController())->run();
}

$page = strtolower(htmlspecialchars($_GET["page"]));
switch ($page) {
    case "product":
        (new ProductController())->run();
        break;
    case "admin":
        (new AdminController())->run();
        break;
    case "admin2":
        (new ProductController())->run();
        break;
    case "navigation":
        (new NavigationController())->run();
        break;
    case "index.php":
    case "home":
        (new HomeController())->run();
        break;
    default:
        apologize("Sorry. Pagina bestaat niet!");
        break;
}