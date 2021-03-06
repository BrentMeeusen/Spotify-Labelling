<?php

// If the payload doesn't contain "user.id", return an error
if(!isset($payload->user->id) || !isset($payload->rights->user->update) || $payload->rights->user->update !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to update your account.", "data" => $payload->user]);
}



// Check if the token is not empty
if(!my_isset($token)) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in.", "data" => $payload->user]);
}



// If the user isn't found, return an error
$user = User::findByPublicID($payload->user->id);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find your account.", "data" => $payload->user]);
}



// Update the user
$res = User::update($user, ["accessToken" => $token]);

// Create a new token
$user = User::findByPublicID($payload->user->id);
$payload = $user->createPayload();
$token = JSONWebToken::createToken($payload, 60);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "Logged in successfully.", "data" => $res, "jwt" => $token]);


?>