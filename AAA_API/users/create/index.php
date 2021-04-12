<?php

include_once("../../private/include_all.php");

// If the method is not POST
if($_SERVER["REQUEST_METHOD"] !== "POST") {
	ApiResponse::httpResponse(405, [ "error" => "Request method is not allowed." ]);
}



// Get the cookie JWT and the Authorization header JWT
$cookieJWT = (isset($_COOKIE["jwt"]) ? $_COOKIE["jwt"] : "");
$headerJWT = (isset(getallheaders()["Authorization"]) ? explode("Bearer ", getallheaders()["Authorization"])[1] : "");

// If they're different, or if either one of them doesn't exist, return an error
if($cookieJWT !== $headerJWT || $cookieJWT === "" || $headerJWT === "") {
	ApiResponse::httpResponse(401, ["error" => "JSON Web Token could not be verified."]);
}



// Verify the token and get the payload if it's valid
JSONWebToken::validateToken($cookieJWT);
$payload = JSONWebToken::getPayload($cookieJWT);

// If the payload doesn't contain "register", return an error
if(!isset($payload->register) || $payload->register !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to register an account."]);
}



// Set values of the payload
$values = ["FirstName" => NULL, "LastName" => NULL, "Username" => NULL, "Password" => NULL, "EmailAddress" => NULL];
foreach($_POST as $key => $value) {
	$values[$key] = $value;
}

// If the payload doesn't contain any of the required values, return an error
foreach($values as $key => $val) {
	if($val === NULL) {
		ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in."]);
	}
}



// Create the entry in the user class
User::setConnection(Database::connect());
$res = User::createUser($values);

// Check whether everything went right whilst adding to the database, set response headers and messages.
ApiResponse::httpResponse(200, [ "message" => "Successfully registered!" ]);

?>