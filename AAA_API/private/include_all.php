<?php

// Headers here because this file is added to all endpoints
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include all the files here so that all the other files only need one include
include_once("classes/api-response.php");
include_once("classes/database.php");
include_once("classes/jwt.php");
include_once("classes/label.php");

?>