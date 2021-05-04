<?php

session_start();
$redirect = "../../" . $_GET["redirect"];

$_SESSION["code"] = $_GET["code"];
$_SESSION["message"] = $_GET["message"];

header("Location: $redirect");
exit();

?>