<?php

include_once("../private/include_all.php");
include_once("../private/classes/jwt.php");

print(json_encode(JSONWebToken::getHeader()));


?>