<?php

// If we get an "Access denied" error, return to the user
if(isset($_GET["error"])) {
	header("Location: php/redirect.php?redirect=&code=001&message=You%20must%20accept%20the%20Spotify%20popup%20if%20you%20want%20to%20use%20this%20application.");
	exit();
}

// If we don't have an access code, return an error
if(!isset($_GET["code"])) {
	header("Location: php/redirect.php?redirect=&code=001&message=Something%20went%20wrong%20whilst%20getting%20an%20authorisation%20code.");
	exit();
}

// If we have a code, redirect to the dashboard
session_start();
$_SESSION["spotify-access-code"] = $_GET["code"];
header("Location: php/redirect.php?redirect=dashboard&code=200&message=Logged%20in%20successfully.");
exit();

?>