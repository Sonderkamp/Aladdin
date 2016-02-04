<?php

// URL during testing -> http://localhost:63342/Aladdin/?path=search
// URL during APACHE launch -> localhost/search

require_once("Includes/config.php");

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