<?php

include_once("../../private/include_all.php");

// If the method is not GET
if($_SERVER["REQUEST_METHOD"] !== "GET") {
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
if(!isset($payload->users->get) || $payload->users->get !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get users."]);
}




// Write a selector that chooses whether to get all users, get by ID, username, or email adddress (use GET properties)
if(isset($_GET["id"])) {
	User::getByID($_GET["id"]);
}
else if(isset($_GET["username"])) {
	User::getByUsername($_GET["username"]);
}
else if(isset($_GET["email-address"])) {
	User::getByEmailAddress($_GET["email-address"]);
}
else {
	user::getAll();
}

// TODO: properly return the results

// ApiResponse::httpResponse(200, [ "message" => "Successfully registered!", "result" => RESULT_HERE ]);

?>