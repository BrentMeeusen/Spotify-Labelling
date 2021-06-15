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



// Make the call to the Spotify API
// WARNING: HARDCODED
$parameters = http_build_query([
	"grant_type" => "authorization_code",
	"code" => $_GET["code"],
	"redirect_uri" => "http://localhost/Spotify Labelling/AAA_WEBAPP/assets/get-access-code.php"
]);
$context = stream_context_create([
	"http" => [
		"method" => "POST",
		"header" => [
			"Content-Type: application/x-www-form-urlencoded",
			"Authorization: Basic " . base64_encode("a209cbda1aaa4f408bd6ae2efc2264fb:da1ccba787b14df0bffdede8987c63ed")
		],
		"content" => $parameters
	]
]);
$res = @file_get_contents("https://accounts.spotify.com/api/token", false, $context);

// Get the token
$token = @json_decode($res)->access_token;

// Add the token to the user in the database
$jwt = $_COOKIE["jwt"];

$context = stream_context_create([
	"http" => [
		"method" => "POST",
		"header" => [
			"Content-Type: application/json",
			"Authorization: Bearer $jwt"
		],
		"content" => http_build_query(["AccessToken" => $token])
	]
]);

// WARNING: HARDCODED
$res = @file_get_contents("http://localhost/Spotify%20Labelling/AAA_API/api/v1/users/add-token/", false, $context);

print("<pre>");
print_r($res);
print_r($http_response_header);

exit();
// - Redirect to the dashboard

// If we have a code, redirect to the dashboard
session_start();
$_SESSION["spotify-access-code"] = $_GET["code"];
header("Location: php/redirect.php?redirect=dashboard&code=200&message=Logged%20in%20successfully.");
exit();

?>