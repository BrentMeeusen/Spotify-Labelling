<?php

// Find the track
$track = ITrack::findBySpotifyId($trackID);
if($track === NULL) {
	ApiResponse::httpResponse(400, ["error" => "The track to delete from your account was not found."]);
}

// For each label
foreach($post as $key => $value) {

	// Add the label to the track
	if(strpos($key, "label") !== FALSE) {
		$track->addLabel($payload->user->id, $value);
	}

}

// Return result
ApiResponse::httpResponse(200, ["message" => "Successfully added labels to the track."]);

?>