<?php

ApiResponse::httpResponse(403, ["message" => "Forbidden"]);
Initialise::createTables(Database::connect());
ApiResponse::httpResponse(200, ["message" => "Created tables successfully."]);

?>
