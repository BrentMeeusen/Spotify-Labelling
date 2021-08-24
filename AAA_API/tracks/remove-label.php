<?php

// Find the track
$track = ITrack::findBySpotifyId($trackID);
if($track === NULL) {
	ApiResponse::httpResponse(400, ["error" => "The track to remove a label from was not found."]);
}

// Remove the label
$track->removeLabel($labelID);

// Return result
ApiResponse::httpResponse(200, ["message" => "Successfully removed the label from the track."]);

?>