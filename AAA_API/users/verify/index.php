<?php

$ALLOWED_METHOD = "GET";

include_once("../../private/include_all.php");



// Get the user by ID
if(!isset($_GET["id"]) || !isset($_GET["email"])) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were passed."]);
}

// Verify the user
$user = User::findByPublicID($_GET["id"]);
if($user === NULL || $user->emailAddress === $_GET["email"]) {
	ApiResponse::httpResponse(400, ["error" => "The requested user wasn't found."]);
}

// Set the account status
$user->verify();

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "Successfully verified account."]);


?>