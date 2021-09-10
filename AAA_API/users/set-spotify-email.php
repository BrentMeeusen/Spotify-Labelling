<?php

// If the user isn't found, return an error
$user = User::findByPublicID($payload->user->id);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find your account."]);
}

// Set the Spotify email
SpotifyApi::setAuthorisationToken($payload->user->accessToken);
$user->setSpotifyEmail();

// Return
ApiResponse::httpResponse(200, ["message" => "Spotify email set successfully."]);


?>