<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Get the authorisation token from the JWT
SpotifyApi::setAuthorisationToken($payload->user->accessToken);

// Send a request to the endpoint at Spotify
$data = SpotifyApi::getTracksFromPlaylist($_GET["id"]);

// Parse the data using the models
$tracks = Tracks::create($data);

// Store them in the database
$res = $tracks->store();

// If something went wrong, return an error
if($res === FALSE) {
	ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst importing your songs."]);
}

// Return success
ApiResponse::httpResponse(200, ["message" => "Imported your songs successfully."]);


?>