<?php

include_once("../../private/include_all.php");


$msg = httpResponseCode(200, "OK");

print(json_encode([ $msg, "editing user", "GET" => $_GET, "POST" => $_POST, "FILES" => $_FILES ]));

?>