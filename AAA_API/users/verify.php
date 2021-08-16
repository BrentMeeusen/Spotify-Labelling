<?php

// Get the user by ID
if(!isset($id) || !isset($email)) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in."]);
}

// Verify the user
$user = User::findByPublicID($id);
if($user === NULL || $user->emailAddress !== $email) {
	ApiResponse::httpResponse(400, ["error" => "We couldn't find your account."]);
}

// Set the account status
$user->verify();

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "Your account has been successfully verified."]);


?>