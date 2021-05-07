<?php

session_start();
$redirect = "../../" . $_GET["redirect"];

$_SESSION["code"] = $_GET["code"];
$_SESSION["message"] = $_GET["message"];

if(!empty($_GET["jwt"]) && $_GET["jwt"] != "undefined") { $_SESSION["jwt"] = $_GET["jwt"]; }

header("Location: $redirect");
exit();

?>