<?php

session_start();

// Unset cookie
setcookie("jwt", "", 1, "/");

// Set variables
if(!isset($_SESSION["code"])) { $_GET["code"] = 200; }
else { $_GET["code"] = $_SESSION["code"]; }

if(!isset($_SESSION["message"])) { $_GET["message"] = "You have been successfully logged out."; }
else { $_GET["message"] = $_SESSION["message"]; }

$_GET["redirect"] = "";

// Redirect to login
include_once("../assets/php/redirect.php");


?>