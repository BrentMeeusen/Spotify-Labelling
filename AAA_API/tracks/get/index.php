<?php

$ALLOWED_METHOD = "GET";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If track ID is set, get from ID
if(isset($_GET["id"])) {
	$res = ITrack::findBySpotifyId($_GET["id"]);
	if($res === NULL) {
		ApiResponse::httpResponse(500, ["error" => "The track was not found."]);
	}
}

// Else, get all tracks from this user in a collection
else {
	$res = Database::findTracksByUser($payload->user->id);
	if($res === NULL) {
		ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst getting your tracks."]);
	}
	$res = $res->data;
}

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "Tracks found.", "data" => $res ]);

?>