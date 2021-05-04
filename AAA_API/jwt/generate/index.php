<?php

$ALLOWED_METHOD = "POST";

include_once("../../private/include_all.php");



// If the user wants to login
if(isset($_GET["login"]) && $_GET["login"] == TRUE) {

	// Try to log the user in
	if(!isset($_POST["identifier"]) || !isset($_POST["password"])) {
		ApiResponse::httpResponse(400, ["error" => "Not all fields were filled in."]);
	}
	$user = User::login($_POST["identifier"], $_POST["password"]);

	// Create a payload
	$payload = $user->createPayload();



	// TODO Get the additional rights the user has
	
	// TODO Add the additional rights the user has



	// Create token
	$timeValid = 60;
	$token = JSONWebToken::createToken($payload, $timeValid);

}

// If the user wants to logout
else if(isset($_GET["logout"]) && $_GET["logout"] == TRUE) {

	// Clear cookie and return
	setcookie("jwt", "", time() - 60);
	ApiResponse::httpResponse(200, ["message" => "Logged out successfully."]);

}

// If the user wants to be able to login
else if(isset($_GET["register"]) && $_GET["register"] == TRUE) {
	
	$payload = ["rights" => ["login" => TRUE, "register" => TRUE]];
	$timeValid = 15;
	$token = JSONWebToken::createToken($payload, $timeValid);

}





// IF THE DEVELOPER NEEDS TO TEST AND WANTS A SPECIFIC TOKEN
if(isset($_GET["testing"]) && $_GET["testing"] == TRUE) {
	$token = JSONWebToken::createToken(["rights" => ["login" => TRUE, "register" => TRUE]], 24 * 60);
}





// Clear old cookie and store new cookie
setcookie("jwt", "", time() - 60);
setcookie("jwt", $token, time() + $timeValid, "/", "", FALSE, TRUE);

// Return the cookie so that the client can store it and send it on a request
ApiResponse::httpResponse(200, ["jwt" => $token, "message" => "Successfully created JSON Web Token."]);

?>