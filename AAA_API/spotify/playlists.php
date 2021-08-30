<?php

// Get the authorisation token from the JWT
SpotifyApi::setAuthorisationToken($payload->user->accessToken);

// Send a request to the endpoint at Spotify
$data = SpotifyApi::getMyPlaylists();

// Parse the data using the models
$playlists = SpotifyCollection::createPlaylistCollection($data);

// Get liked tracks
@$liked->name = "Liked Songs";
$liked->id = NULL;
@$liked->numTracks = count(SpotifyApi::getMyLikedTracks());
array_unshift($playlists->data, $liked);

// Return the useful playlist data
ApiResponse::httpResponse(200, ["message" => "Found your playlists.", "data" => $playlists->data]);


?>