<?php

// Collect data
$method = $_SERVER["REQUEST_METHOD"];
$url = $_SERVER["REQUEST_URI"];
$get = $_GET;
$post = json_decode(file_get_contents("php://input"));




// print(json_encode(["REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"], "GET" => $_GET, "BODY" => json_decode(file_get_contents("php://input")), "URI" => $_SERVER["REQUEST_URI"], "URL" => $_SERVER["REDIRECT_URL"] ]));

// exit();

?>