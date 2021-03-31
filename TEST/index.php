<?php

include_once("../private/include_all.php");
include_once("../private/classes/jwt.php");

$token = JSONWebToken::createToken(["sexy" => "yes"], 60);
$isValid = JSONWebToken::checkToken($token);
// $payload = JSONWebToken::getPayload($token);


// print("ENCODING CUSTOM\n");
print("Encoding custom: " . json_encode(JSONWebToken::checkToken("invalidbase.b.c")));


// print("ENCODING CREATED\n");
// print(json_encode([ "token" => $token, "is_valid" => $isValid ]));
// print(json_encode([ "token" => $token, "is_valid" => $isValid, "payload" => $payload ]));


?>