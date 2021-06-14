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

// TODO
// If we have a code
// - Make a call to the correct Spotify endpoint (https://developer.spotify.com/documentation/general/guides/authorization-guide/#authorization-code-flow)
// - Get the actual, correct authorisation token
// - Add that to the user in the database using the cookie JWT (that I should be able to get here if I'm not mistaken)
// - Redirect to the dashboard

// If we have a code, redirect to the dashboard
session_start();
$_SESSION["spotify-access-code"] = $_GET["code"];
header("Location: php/redirect.php?redirect=dashboard&code=200&message=Logged%20in%20successfully.");
exit();

?>