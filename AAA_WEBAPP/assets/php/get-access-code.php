<?php

session_start();
$VARIABLES = json_decode(file_get_contents("../../../../SpotifyLabelling.json"));

// If we get an "Access denied" error, return to the user
if(isset($_GET["error"])) {
	header("Location: redirect.php?redirect=&code=001&message=You%20must%20accept%20the%20Spotify%20popup%20if%20you%20want%20to%20use%20this%20application.");
	exit();
}

// If we don't have an access code, return an error
if(!isset($_GET["code"])) {
	header("Location: redirect.php?redirect=&code=001&message=Something%20went%20wrong%20whilst%20getting%20an%20authorisation%20code.");
	exit();
}



// Make the call to the Spotify API
$parameters = http_build_query([
	"grant_type" => "authorization_code",
	"code" => $_GET["code"],
	"redirect_uri" => $VARIABLES->BASE->APP . "assets/php/get-access-code.php"
]);
$context = stream_context_create([
	"http" => [
		"method" => "POST",
		"header" => [
			"Content-Type: application/x-www-form-urlencoded",
			"Authorization: Basic " . base64_encode($VARIABLES->SPOTIFY->CLIENT . ":" . $VARIABLES->SPOTIFY->SECRET)
		],
		"content" => $parameters
	]
]);
$res = @file_get_contents("https://accounts.spotify.com/api/token", false, $context);
$token = @json_decode($res)->access_token;

// If there is no token, throw an error
if($token === NULL) {
	header("Location: redirect.php?redirect=&code=001&message=Something%20went%20wrong%20whilst%20getting%20an%20authorisation%20code.");
	exit();
}

// Add the token to the user in the database
$jwt = $_COOKIE["jwt"];

$context = stream_context_create([
	"http" => [
		"ignore_errors" => "true",
		"method" => "POST",
		"header" => [
			"Content-Type: application/json",
			"Authorization: Bearer $jwt"
		],
		"content" => json_encode(["AccessToken" => $token])
	]
]);

$res = @file_get_contents($VARIABLES->BASE->API . "api/v1/users/add-token/", false, $context);
$jwt = json_decode($res)->jwt;
setcookie("jwt", $jwt, time() + 3600, "/");

// Redirect to the dashboard
header("Location: redirect.php?redirect=dashboard&code=200&message=Logged%20in%20successfully.");
exit();

?>