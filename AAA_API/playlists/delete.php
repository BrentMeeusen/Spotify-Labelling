<?php

// If ID is not set, return an error
if(!isset($id)) {
	ApiResponse::httpResponse(400, ["error" => "The playlist to delete was not found."]);
}

// If the playlist is not created by user, return an error
$playlist = IPlaylist::findById($id);
if($playlist === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find the playlist.", "data" => $payload->user]);
}

// Delete the playlist
$res = IPlaylist::delete($playlist);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The playlist has been successfully deleted.", "data" => $res]);


?>