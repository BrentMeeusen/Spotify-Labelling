<?php

$ALLOWED_METHOD = "GET";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Get all tracks from this user in a collection
$res = Database::findTracksByUser($payload->user->id);

// Merge all tracks if the IDs are the same
$res = $res->merge();

// If the merging went wrong, return an error
if($res === NULL) {
	ApiResponse::httpResponse(500, ["error" => "Something went wrong with getting your tracks."]);
}

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "Tracks found.", "data" => $res ]);

?>