<?php

include_once("../../private/include_all.php");

// Create token
$token = JSONWebToken::createToken(["login" => TRUE, "register" => TRUE], 15);

// Clear cookie and store new cookie
setcookie("jwt", "", time() - 60);
setcookie("jwt", $token, time() + 15, "/", "", false, true);

?>