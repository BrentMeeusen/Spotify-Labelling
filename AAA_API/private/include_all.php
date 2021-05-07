<?php

// Headers here because this file is added to all endpoints
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
header("Content-Type: application/json; charset=UTF-8");

// Include all the files here so that all the other files only need one include
include_once("classes/api-response.php");
include_once("classes/database.php");
include_once("classes/jwt.php");

include_once("models/AAA_table.php");
include_once("models/label.php");
include_once("models/user.php");


// Set the database connection
Table::setConnection(Database::connect());

// Read the input
$body = (array) json_decode(file_get_contents("php://input"));


// If we require a token, check it
if(isset($REQUIRE_TOKEN)) {

	// Get the cookie JWT and the Authorization header JWT
	$cookieJWT = (isset($_COOKIE["jwt"]) ? $_COOKIE["jwt"] : "");
	$headerJWT = (isset(getallheaders()["Authorization"]) ? @explode("Bearer ", getallheaders()["Authorization"])[1] : "");

	// If they're different, or if either one of them doesn't exist, return an error
	if($cookieJWT !== $headerJWT || $cookieJWT === "" || $headerJWT === "") {
		ApiResponse::httpResponse(401, ["error" => "JSON Web Token could not be verified.", "COOKIE" => $cookieJWT, "HEADER" => $headerJWT]);
	}

	// Verify the token and get the payload if it's valid
	JSONWebToken::validateToken($cookieJWT);
	$payload = JSONWebToken::getPayload($cookieJWT);

}

// Check whether the method is correct
if(isset($ALLOWED_METHOD)) {
	if($_SERVER["REQUEST_METHOD"] !== $ALLOWED_METHOD && $_SERVER["REQUEST_METHOD"] !== "OPTIONS") {
		ApiResponse::httpResponse(405, [ "error" => "Request method is not allowed." ]);
	}
}

?>