<?php

// Get the authorisation token from the JWT
SpotifyApi::setAuthorisationToken($payload->user->accessToken);

// Send a request to the endpoint at Spotify
$data = SpotifyApi::getMyPlaylists();
array_push($data, SpotifyApi::getMyLikedTracks());

// Parse the data using the models
$playlists = SpotifyCollection::createPlaylistCollection($data);

// Return the useful playlist data
ApiResponse::httpResponse(200, ["message" => "Found your playlists.", "data" => $playlists->data]);


?>