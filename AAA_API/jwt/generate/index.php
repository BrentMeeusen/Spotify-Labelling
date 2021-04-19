<?php

include_once("../../private/include_all.php");


// If the user wants to login
if(isset($_GET["login"]) && $_GET["login"] === TRUE) {

	// Try to log the user in
	$user = User::login();

	// Create a payload
	// $payload = User::createPayload();

	// TODO Get the rights the user has
	
	// TODO Add the rights the user has

	// Create token
	// $token = JSONWebToken::createToken($payload, 60);

}

// If the user wants to logout
else if(isset($_GET["logout"]) && $_GET["logout"] === TRUE) {

	// Clear cookie and return
	setcookie("jwt", "", time() - 60);
	ApiResponse::httpResponse(200, ["message" => "Logged out successfully."]);

}





// Create token
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 15);
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 24 * 60); // Make the token expire after 24hrs (for testing purposes)


// Gets a token that allows getting users
if(isset($_GET["testing"]) && $_GET["testing"] === TRUE) {
	$token = JSONWebToken::createToken(["user" => ["id" => 1], "users" => ["get" => TRUE], "labels" => FALSE ], 24 * 60);
}



// Clear cookie and store new cookie
setcookie("jwt", "", time() - 60);
setcookie("jwt", $token, time() + 15, "/", "", FALSE, TRUE);

// Return the cookie so that the client can store it and send it on a request
ApiResponse::httpResponse(200, ["jwt" => $token, "message" => "Successfully created JSON Web Token."]);

?>