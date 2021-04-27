<?php

include_once("../../private/include_all.php");

// If the method is not DELETE
if($_SERVER["REQUEST_METHOD"] !== "DELETE") {
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
	if(!isset($payload->rights->users->delete) || $payload->rights->users->delete !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to delete someone else's account."]);
	}
	$userID = $_GET["id"];

}

// If the ID is not set, update self
else {

	// If the payload doesn't contain "user.id", return an error
	if(!isset($payload->user->id) || !isset($payload->rights->user->delete) || $payload->rights->user->delete !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to delete your account."]);
	}
	$userID = $payload->user->id;

}



// If the user isn't found, return an error
$user = User::findByPublicID($userID);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The requested user was not found."]);
}



// Set values of the payload
$values = [];
foreach($_POST as $key => $value) {
	$values[$key] = $value;
}

// Update the user
$res = User::deleteUser($userID);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "Deleted user.", "data" => $res]);


?>