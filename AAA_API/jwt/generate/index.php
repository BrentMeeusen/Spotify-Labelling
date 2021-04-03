<?php

include_once("../../private/include_all.php");
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 15);
$token = json_encode(["jwt" => $token]);

print($token);

header( "Set-Cookie: jwt=$token; httpOnly" );

?>