<?php

// Headers here because this file is added to all endpoints
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Include classes
include_once("classes/api-response.php");
include_once("classes/database.php");
include_once("classes/jwt.php");
include_once("classes/spotify-api.php");

// Include Spotify models
include_once("models/spotify/spotify-collection.php");
include_once("models/spotify/spotify-album.php");
include_once("models/spotify/spotify-artist.php");
include_once("models/spotify/spotify-playlist.php");
include_once("models/spotify/spotify-track.php");

include_once("models/AAA_spotify.php");
include_once("models/AAA_table.php");
include_once("models/album.php");
include_once("models/albums.php");
include_once("models/artist.php");
include_once("models/artists.php");
include_once("models/label.php");
include_once("models/playlist.php");
include_once("models/playlists.php");
include_once("models/track.php");
include_once("models/tracks.php");
include_once("models/user.php");

include_once("methods.php");



// If it's a preflight check, return 200
if($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
	ApiResponse::httpResponse(200);
}



// Set the database connection
Table::setConnection(Database::connect());

// Read the input
$body = (array) json_decode(file_get_contents("php://input"));



// Check whether the method is correct
if(isset($ALLOWED_METHOD)) {
	if($_SERVER["REQUEST_METHOD"] !== $ALLOWED_METHOD && $_SERVER["REQUEST_METHOD"] !== "OPTIONS") {
		ApiResponse::httpResponse(405, [ "error" => "Request method is not allowed." ]);
	}
}

// If we require a token, check it
if(isset($REQUIRE_TOKEN)) {

	// Get the JWT from the Authorization header
	$headerJWT = (isset(getallheaders()["Authorization"]) ? @explode("Bearer ", getallheaders()["Authorization"])[1] : "");

	// Verify the token and get the payload if it's valid
	JSONWebToken::validateToken($headerJWT);
	$payload = JSONWebToken::getPayload($headerJWT);

	if($payload === NULL || !isset($payload->user) || !isset($payload->user->id)) {
		ApiResponse::httpResponse(500, ["error" => "Could not validate your account."]);
	}

	// Check if this user actually exists
	$user = User::findByPublicID($payload->user->id);
	if($user === NULL) {
		ApiResponse::httpResponse(500, ["error" => "Could not validate your account."]);
	}

	// Get the newest payload from the user so that it is up-to-date with the info in the database
	$payload = json_decode(json_encode($user->createPayload()));

}

?>