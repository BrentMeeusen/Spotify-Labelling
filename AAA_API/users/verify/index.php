<?php

$ALLOWED_METHOD = "GET";

include_once("../../private/include_all.php");



// Get the user by ID
if(!isset($_GET["id"]) || !isset($_GET["email"])) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in."]);
}

// Verify the user
$user = User::findByPublicID($_GET["id"]);
if($user === NULL || $user->emailAddress !== $_GET["email"]) {
	ApiResponse::httpResponse(400, ["error" => "We couldn't find your account."]);
}

// Set the account status
$user->verify();

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "Your account has been successfully verified."]);


?>