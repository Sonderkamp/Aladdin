<?php


// requirements
require_once('Libs/Smarty/Smarty.class.php');
require_once('Libs/PHPMailer/PHPMailerAutoload.php');
require("classes.php");
require("constants.php");
require("functions.php");

// enable sessions
session_start();

$page = explode("/", $_SERVER["PHP_SELF"])[1];


if ($page != "index.php") {

    redirect("/");

}

?>
