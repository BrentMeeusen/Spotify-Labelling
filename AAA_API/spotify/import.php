<?php

// Get the authorisation token from the JWT
SpotifyApi::setAuthorisationToken($payload->user->accessToken);

// Get the tracks
if(empty($playlistID)) { $data = SpotifyApi::getMyLikedTracks(); }
else { $data = SpotifyApi::getTracksFromPlaylist($playlistID); }

// Parse the data using the models
$tracks = SpotifyCollection::createTrackCollection($data);

// Store the data in the collection
$result = $tracks->store($payload->user->id);



// If something went wrong, return an error
if($result === FALSE) {
	ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst importing your songs."]);
}

// Return success
ApiResponse::httpResponse(200, ["message" => "Imported your songs successfully."]);

?>