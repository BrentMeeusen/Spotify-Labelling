<?php

// If track ID is set, get from ID
if(isset($trackID)) {
	$res = ITrack::findBySpotifyId($trackID);
	if($res === NULL) {
		ApiResponse::httpResponse(500, ["error" => "The track was not found."]);
	}
}

// Else, get all tracks from this user in a collection
else {
	$res = ITrack::findByUser($payload->user->id);
	if($res === NULL) {
		ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst getting your tracks."]);
	}
	$res = $res->data;
}

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "Tracks found.", "data" => $res ]);

?>