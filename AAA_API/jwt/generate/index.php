<?php

include_once("../../private/include_all.php");
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 15);
setcookie("jwt", $token, time() + 3600, "/", "", false, true);

?>