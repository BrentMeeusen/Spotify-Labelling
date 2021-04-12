<?php

include_once("../../private/include_all.php");

// If the method is not POST
if($_SERVER["REQUEST_METHOD"] !== "POST") {
	ApiResponse::httpResponse(405);
}

// Get the cookie JWT and the Authorization header JWT
$cookieJWT = (isset($_COOKIE["jwt"]) ? $_COOKIE["jwt"] : "");
$headerJWT = (isset(getallheaders()["Authorization"]) ? explode("Bearer ", getallheaders()["Authorization"])[1] : "");

// If they're different, or if neither of them exist, return an error
if($cookieJWT !== $headerJWT || ($cookieJWT === "" && $headerJWT === "")) {
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
Database::initialise(Database::connect());			// REMOVE ALL TABLES AND ITS ENTRIES AND RECREATE IT: ONLY FOR TESTING!
$user = new User(Database::connect());
$res = $user->createUser($values);

// TODO: check whether everything went right whilst adding to the database, set response headers and messages.




$res = [ "message" => "Creating user...", "GET" => $_GET, "POST" => $_POST, "COOKIES" => $_COOKIE, "METHOD" => $_SERVER["REQUEST_METHOD"], "VALUES" => $values, "PAYLOAD" => $payload, "HEADERS" => getallheaders() ];
print(json_encode($res));

?>