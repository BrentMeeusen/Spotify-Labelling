<?php

$tracks = [];

// If it's only one track
if(!(empty($trackID))) {
	$tracks = [ITrack::findBySpotifyId($trackID)];
	if($tracks[0] === NULL) {
		ApiResponse::httpResponse(400, ["error" => "The track to remove a label from was not found."]);
	}
}
// If it's multiple tracks
else if(!(empty($post->tracks))) {
	foreach($post->tracks as $track) {
		array_push($tracks, ITrack::findBySpotifyId($track));
	}
}



// For each track
foreach($tracks as $track) {

	// For each label
	foreach($post as $key => $value) {

		// Remove the label from the track
		if(strpos($key, "label") !== FALSE) {
			$track->removeLabel($value);
		}

	}

}

// Return result
ApiResponse::httpResponse(200, ["message" => "Successfully removed the label from the track."]);

?>