<?php

Initialise::createTables(Database::connect());
ApiResponse::httpResponse(200, ["message" => "Created tables successfully."]);

?>
