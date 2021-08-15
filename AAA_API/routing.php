<?php

// Headers here because this file is added to all endpoints
header("Access-Control-Allow-Origin: http://spotify-labelling.21webb.nl");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Collect data
$method = $_SERVER["REQUEST_METHOD"];
$url = $_SERVER["REQUEST_URI"];
$get = $_GET;
$post = json_decode(file_get_contents("php://input"));

// Include classes
include_once("classes/api-response.php");
include_once("classes/database.php");
include_once("classes/initialise.php");
include_once("classes/jwt.php");
include_once("classes/request.php");
include_once("classes/spotify-api.php");

// If it's a preflight check, return 200
if($method === "OPTIONS") {
	ApiResponse::httpResponse(200);
}

// Include Spotify models
include_once("models/spotify/spotify-collection.php");
include_once("models/spotify/spotify-album.php");
include_once("models/spotify/spotify-artist.php");
include_once("models/spotify/spotify-playlist.php");
include_once("models/spotify/spotify-track.php");

// Include my Spotify data models
include_once("models/my/album.php");
include_once("models/my/artist.php");
include_once("models/my/collection.php");
include_once("models/my/track.php");

// Include general
include_once("models/label.php");
include_once("models/user.php");
include_once("methods.php");








// If the URL does not contain `/api/`, return an error
if(strpos($url, "/api/") === FALSE) {
	ApiResponse::httpResponse(404, ["error" => "Page not found."]);
}


print(json_encode(["REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"], "GET" => $_GET, "BODY" => json_decode(file_get_contents("php://input")), "URI" => $_SERVER["REQUEST_URI"], "URL" => $_SERVER["REDIRECT_URL"] ]));

// exit();

?>