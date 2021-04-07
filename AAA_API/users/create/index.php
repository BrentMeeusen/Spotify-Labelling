<?php

include_once("../../private/include_all.php");

// Get the cookie JWT and the Authorization header JWT
$cookieJWT = (isset($_COOKIE["jwt"]) ? $_COOKIE["jwt"] : "");
$headerJWT = (isset(getallheaders()["Authorization"]) ? explode("Bearer ", getallheaders()["Authorization"])[1] : "");

// If they're different, or if neither of them exist, return an error
if($cookieJWT !== $headerJWT || ($cookieJWT === "" && $headerJWT === "")) {
	ApiResponse::httpResponse(401, ["message" => "JSON Web Token could not be verified."]);
}

// Verify the token and get the payload if it's valid
JSONWebToken::validateToken($cookieJWT);
$payload = JSONWebToken::getPayload($cookieJWT);

// If the payload doesn't contain "register", return an error
if(!isset($payload->register) || $payload->register !== TRUE) {
	ApiResponse::httpResponse(401, ["message" => "The given JSON Web Token cannot be used to register an account."]);
}



$res = [ "message" => "Creating user...", "GET" => $_GET, "POST" => $_POST, "COOKIES" => $_COOKIE, "METHOD" => $_SERVER["REQUEST_METHOD"], "PAYLOAD" => $payload, "HEADERS" => getallheaders() ];
print(json_encode($res));

?>