<?php

include_once("../../private/include_all.php");

// Create token
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 15);
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 24 * 60); // Make the token expire after 24hrs (for testing purposes)

// Clear cookie and store new cookie
setcookie("jwt", "", time() - 60);
setcookie("jwt", $token, time() + 15, "/", "", false, true);

// Return the cookie so that the client can store it and send it on a request
ApiResponse::httpResponse(200, ["jwt" => $token, "message" => "Successfully created JSON Web Token."]);

?>