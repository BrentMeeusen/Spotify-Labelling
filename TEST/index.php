<?php

include_once("../private/include_all.php");
include_once("../private/classes/jwt.php");

print(JSONWebToken::createToken(["sexy" => "yes"]));


?>