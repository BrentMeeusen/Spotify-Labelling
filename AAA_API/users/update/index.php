<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If the ID is set, update ID
if(isset($_GET["id"])) {

	// Check whether the current user (JWT) is allowed to update another user (ID)
	if(!isset($payload->rights->users->update) || $payload->rights->users->update !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "You are not allowed to update someone else's account.", "data" => $payload->user]);
	}
	$updateID = $_GET["id"];
	$prefix = "The";

}

// If the ID is not set, update self
else {

	// If the payload doesn't contain "user.id", return an error
	if(!isset($payload->user->id) || !isset($payload->rights->user->update) || $payload->rights->user->update !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "You are not allowed to update your account.", "data" => $payload->user]);
	}
	$updateID = $payload->user->id;
	$prefix = "Your";

}



// Check if inputs are set and not empty
if(setAndEmpty($body, "FirstName") || setAndEmpty($body, "LastName") || setAndEmpty($body, "Username") || setAndEmpty($body, "EmailAddress")) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in.", "data" => $payload->user]);
}

// Check if the password is the same as another value
if(isset($body["Password"]) && ($body["Password"] == $payload->user->firstname || $body["Password"] == $payload->user->lastname || $body["Password"] == $payload->user->username || $body["Password"] == $payload->user->emailAddress)) {
	ApiResponse::httpResponse(400, ["error" => "Your password must be a unique value.", "data" => $payload->user]);
}



// If the user isn't found, return an error
$user = User::findByPublicID($updateID);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find " . strtolower($prefix) . " account.", "data" => $payload->user]);
}



// Set values of the payload
$values = [];
foreach($body as $key => $value) {
	if(!empty($value)) {
		$values[$key] = $value;
	}
}

// Update the user
$res = User::update($user, $values);

// Create a new token
$user = User::findByPublicID($payload->user->id);
$payload = $user->createPayload();
$token = JSONWebToken::createToken($payload, 60);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "$prefix account has been successfully updated.", "data" => $res, "jwt" => $token]);


?>