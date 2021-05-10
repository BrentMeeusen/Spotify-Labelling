<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If the payload doesn't contain "register", return an error
if(!isset($payload->rights->register) || $payload->rights->register !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to register an account."]);
}



// Check whether all required fields are filled in
if(empty($body["FirstName"])|| empty($body["LastName"]) || empty($body["Username"]) || empty($body["Password"]) || empty($body["EmailAddress"])) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in."]);
}

// Check if the password is the same as another value
if($body["Password"] == $body["FirstName"] || $body["Password"] == $body["LastName"] || $body["Password"] == $body["Username"] || $body["Password"] == $body["EmailAddress"]) {
	ApiResponse::httpResponse(400, ["error" => "Your password must be a unique value."]);
}


// Set values of the payload
$values = ["FirstName" => NULL, "LastName" => NULL, "Username" => NULL, "Password" => NULL, "EmailAddress" => NULL];
foreach($body as $key => $value) {
	$values[$key] = $value;
}



// Create the entry in the user class
$res = User::createUser($values);

// Check whether everything went right whilst adding to the database, set response headers and messages.
ApiResponse::httpResponse(200, [ "message" => "Successfully registered. You can login after you have verified your account.", "data" => $res ]);

?>