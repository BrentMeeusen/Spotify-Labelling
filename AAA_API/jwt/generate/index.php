<?php

$ALLOWED_METHOD = "POST";

include_once("../../private/include_all.php");


$message = NULL;

// If the user wants to login
if(isset($_GET["login"]) && $_GET["login"] == TRUE) {

	// Try to log the user in
	if(!isset($body["Identifier"]) || !isset($body["Password"])) {
		ApiResponse::httpResponse(400, ["error" => "Not all fields were filled in."]);
	}
	$user = User::login($body["Identifier"], $body["Password"]);

	// If the user has not verified their account yet, throw an error
	if($user->accountStatus !== 2) {
		ApiResponse::httpResponse(401, ["error" => "You have to verify your account before you can login."]);
	}

	// Create a payload
	$payload = $user->createPayload();

	// Create token
	$timeValid = 60;
	$token = JSONWebToken::createToken($payload, $timeValid);
	$message = "Successfully logged in.";

}

// If the user wants to register
else if(isset($_GET["register"]) && $_GET["register"] == TRUE) {

	$payload = ["rights" => ["login" => TRUE, "register" => TRUE]];
	$timeValid = 15;
	$token = JSONWebToken::createToken($payload, $timeValid);

}





// IF THE DEVELOPER NEEDS TO TEST AND WANTS A SPECIFIC TOKEN
if(isset($_GET["testing"]) && $_GET["testing"] == TRUE) {
	$token = JSONWebToken::createToken(["rights" => ["login" => TRUE, "register" => TRUE]], 24 * 60);
}





// Return the token so that the client can store it and send it on a request
ApiResponse::httpResponse(200, ["jwt" => $token, "message" => ($message ? $message : "Successfully created JSON Web Token.")]);

?>