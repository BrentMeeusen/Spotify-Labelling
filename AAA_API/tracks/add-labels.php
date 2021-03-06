<?php

$tracks = [];

// If it's only one track
if(!(empty($trackID))) {
	$tracks = [ITrack::findBySpotifyId($trackID)];
	if($tracks[0] === NULL) {
		ApiResponse::httpResponse(400, ["error" => "The track to add a label to was not found."]);
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

		// Add the label to the track
		if(strpos($key, "label") !== FALSE) {
			$track->addLabel($value);
		}

	}

}

// Return result
$trackOrTracks = (count($tracks) === 1 ? "track" : "tracks");
ApiResponse::httpResponse(200, ["message" => "Successfully added labels to the $trackOrTracks."]);

?>