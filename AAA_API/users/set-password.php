<?php

// RESETS THE PASSWORD

// If the user isn't found, return an error
$user = User::findByEmailAddress($emailAddress);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find your account."]);
}

// If the user ID is not the same as the user ID of the found user, return an error
if($user->publicID != $userID) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find your account."]);
}

// Set the password
$user->setPassword($password);

// Return
ApiResponse::httpResponse(200, ["message" => "Your password was reset successfully."]);


?>