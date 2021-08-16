<?php

// If the payload doesn't contain "register", return an error
if(!isset($payload->rights->register) || $payload->rights->register !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to register an account."]);
}



// Check whether all required fields are filled in
if(empty($password) || empty($email)) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in."]);
}

// Check if the password is the same as another value
if($password == $email) {
	ApiResponse::httpResponse(400, ["error" => "Your password must be a unique value."]);
}



// Create the entry in the user class
$res = User::create(["Password" => $password, "EmailAddress" => $email]);

// Check whether everything went right whilst adding to the database, set response headers and messages.
ApiResponse::httpResponse(200, [ "message" => "Successfully registered. You can login after you have verified your account.", "data" => $res ]);

?>