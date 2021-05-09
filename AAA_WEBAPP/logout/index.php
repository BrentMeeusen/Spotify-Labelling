<?php

// Unset cookie
setcookie("jwt", "", 1, "/");

// Set variables
if(!isset($_GET["code"])) { $_GET["code"] = 200; }
if(!isset($_GET["message"])) { $_GET["message"] = "You have been successfully logged out."; }
$_GET["redirect"] = "";

// Redirect to login
include_once("../assets/php/redirect.php");


?>