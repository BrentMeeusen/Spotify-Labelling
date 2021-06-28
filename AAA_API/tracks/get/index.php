<?php

$ALLOWED_METHOD = "GET";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Get all tracks from this user
$res = Database::findTracksByUser($payload->user->id);

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "Tracks found.", "data" => $res ]);

?>