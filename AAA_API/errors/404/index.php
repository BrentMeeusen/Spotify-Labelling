<?php
include_once("../../private/include_all.php");
ApiResponse::httpResponse(404, [ "error" => "Not Found", "URI" => $_SERVER['REQUEST_URI'], "URL" => $_SERVER['REDIRECT_URL'] ]);
?>