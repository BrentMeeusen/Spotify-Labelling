<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

print(json_encode([ "your general request", "GET" => $_GET, "POST" => $_POST, "FILES" => $_FILES ]));

?>