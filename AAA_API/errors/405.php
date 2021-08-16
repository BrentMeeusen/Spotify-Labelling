<?php
include_once("../classes/api-response.php");
ApiResponse::httpResponse(405, [ "error" => "Method Not Allowed", "URI" => $_SERVER["REQUEST_URI"], "URL" => $_SERVER["REDIRECT_URL"] ]);
?>