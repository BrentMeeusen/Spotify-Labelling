<?php

include_once("../private/include_all.php");
include_once("../private/classes/jwt.php");

$token = JSONWebToken::createToken(["sexy" => "yes"], 60);
$isValid = JSONWebToken::checkToken($token);
// $payload = JSONWebToken::getPayload($token);

print(json_encode([ "token" => $token, "is_valid" => $isValid, "payload" => $payload ]));


?>