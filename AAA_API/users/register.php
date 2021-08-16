<?php

$payload = ["rights" => ["login" => TRUE, "register" => TRUE]];
$timeValid = 15;
$token = JSONWebToken::createToken($payload, $timeValid);

// Return the token so that the client can store it and send it on a request
ApiResponse::httpResponse(200, ["jwt" => $token, "message" => NULL]);

?>
