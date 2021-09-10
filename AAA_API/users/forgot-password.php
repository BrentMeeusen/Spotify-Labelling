<?php

// SENDS AN EMAIL TO THE USER TO RESET THE PASSWORD, DOES NOT DO THE ACTUAL RESETTING

// If the user isn't found, return an error
$user = User::findByEmailAddress($emailAddress);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find your account."]);
}

// Send the email
$user->requestNewPassword();

// Return
ApiResponse::httpResponse(200, ["message" => "Email sent."]);


?>