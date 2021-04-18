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



// If the ID is set, update ID
if(isset($_GET["id"])) {

	// Check whether the current user (JWT) is allowed to update another user (ID)
	if(!isset($payload->users->update)) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to update someone else's account."]);
	}
	$updateID = $_GET["id"];

}

// If the ID is not set, update self
else {

	// If the payload doesn't contain "user.id", return an error
	if(!isset($payload->user->id)) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to update your account."]);
	}
	$updateID = $payload->user->id;

}



// If the user isn't found, return an error
$user = User::findByID($updateID);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The requested user was not found."]);
}



// Set values of the payload
$values = [];
foreach($_POST as $key => $value) {
	$values[$key] = $value;
}

// Update the user
$res = User::updateUser($updateID, $values);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "Updated user.", "data" => $res]);


?>