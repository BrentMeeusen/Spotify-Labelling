<?php

include_once("../private/include_all.php");
include_once("../private/classes/jwt.php");

$token = JSONWebToken::createToken(["sexy" => "yes"], 60);
$isValid = JSONWebToken::checkToken($token);
// $payload = JSONWebToken::getPayload($token);


print("ENCODING CUSTOM");
print(json_encode(JSONWebToken::checkToken("a.b.YWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXos")));


print("ENCODING CREATED");
print(json_encode([ "token" => $token, "is_valid" => $isValid ]));
// print(json_encode([ "token" => $token, "is_valid" => $isValid, "payload" => $payload ]));


?>