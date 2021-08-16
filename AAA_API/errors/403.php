<?php
include_once("../classes/api-response.php");
ApiResponse::httpResponse(403, [ "error" => "Forbidden", "URI" => $_SERVER["REQUEST_URI"], "URL" => $_SERVER["REDIRECT_URL"] ]);
?>