<?php

// URL Tijdens de test -> http://localhost:63342/Aladdin/?path=search
// URL in APACHE -> localhost/search


_once("Includes/config.php");

if(Empty($_GET["path"]) )
{
    (new HomeController())->Run();
    exit();
}


$page = strtolower(htmlspecialchars($_GET["path"]));
switch ($page)
{
    // no parameters
    case "search":
        (new HomeController())->Run();
        break;
    default:
        apologize("Sorry. Pagina bestaat niet");
        break;
}