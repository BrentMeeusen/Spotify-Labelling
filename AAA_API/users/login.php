<?php

// Try to log the user in
if(!isset($identifier) || !isset($password)) {
	ApiResponse::httpResponse(400, ["error" => "Not all fields were filled in."]);
}
$user = User::login($identifier, $password);

// If the user has not verified their account yet, throw an error
if($user->accountStatus !== 2) {
	ApiResponse::httpResponse(401, ["error" => "You have to verify your account before you can login."]);
}

// Create a payload and token
$payload = $user->createPayload();
$timeValid = 60;
$token = JSONWebToken::createToken($payload, $timeValid);

// Return the token so that the client can store it and send it on a request
ApiResponse::httpResponse(200, ["jwt" => $token, "message" => "You are logged in."]);

?>
