<?php

// If the ID is set, update ID
if(isset($id)) {

	// Check whether the current user (JWT) is allowed to update another user (ID)
	if(!isset($payload->rights->users->update) || $payload->rights->users->update !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "You are not allowed to update someone else's account.", "data" => $payload->user]);
	}
	$updateID = $id;
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
if(!my_isset($email)) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in.", "data" => $payload->user]);
}

// Check if the password is the same as another value
if(!my_isset($password) && ($password == $payload->user->emailAddress)) {
	ApiResponse::httpResponse(400, ["error" => "Your password must be a unique value.", "data" => $payload->user]);
}



// If the user isn't found, return an error
$user = User::findByPublicID($updateID);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find " . strtolower($prefix) . " account.", "data" => $payload->user]);
}



// Set values of the payload
$newValues = [];
foreach($values as $key => $value) {
	if(!empty($value)) {
		$values[$key] = $value;
	}
}

// Update the user
$res = User::update($user, $newValues);

// Create a new token
$user = User::findByPublicID($payload->user->id);
$payload = $user->createPayload();
$token = JSONWebToken::createToken($payload, 60);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "$prefix account has been successfully updated.", "data" => $res, "jwt" => $token]);


?>