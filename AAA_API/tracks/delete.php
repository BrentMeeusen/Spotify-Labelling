<?php

// Find the track
$track = ITrack::findBySpotifyId($trackID);
if($track === NULL) {
	ApiResponse::httpResponse(400, ["error" => "The track to delete from your account was not found."]);
}

// Remove the track/user link
$track->removeUser($payload->user->id);

// Return result
ApiResponse::httpResponse(200, [ "message" => "Successfully removed song." ]);


?>