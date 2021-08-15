<?php

// Headers here because this file is added to all endpoints
header("Access-Control-Allow-Origin: http://spotify-labelling.21webb.nl");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Collect data
$url = $_SERVER["REQUEST_URI"];
$get = $_GET;
$post = json_decode(file_get_contents("php://input"));
$jwt = (isset(getallheaders()["Authorization"]) ? @explode("Bearer ", getallheaders()["Authorization"])[1] : "");

// Include classes
include_once("classes/api-response.php");
include_once("classes/database.php");
include_once("classes/initialise.php");
include_once("classes/jwt.php");
include_once("classes/request.php");
include_once("classes/spotify-api.php");

// If it's a preflight check, return 200
if($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
	ApiResponse::httpResponse(200);
}

// Include Spotify models
include_once("classes/models/spotify/spotify-collection.php");
include_once("classes/models/spotify/spotify-album.php");
include_once("classes/models/spotify/spotify-artist.php");
include_once("classes/models/spotify/spotify-playlist.php");
include_once("classes/models/spotify/spotify-track.php");

// Include my Spotify data models
include_once("classes/models/my/album.php");
include_once("classes/models/my/artist.php");
include_once("classes/models/my/collection.php");
include_once("classes/models/my/track.php");

// Include general
include_once("classes/models/label.php");
include_once("classes/models/user.php");
include_once("classes/methods.php");

// Connect with the database
Database::connect();








// If the URL does not contain `/api/`, return an error
if(strpos($url, "/api/") === FALSE) {
	ApiResponse::httpResponse(404, ["error" => "Page not found."]);
}



/*************************
 ******** ROUTING ********
 ************************/
$routes = explode("/", explode("/api/", $url)[1]);

// /api/v1
if(isset($routes[0]) && $routes[0] === "v1") {

	// /api/v1/tracks
	if(isset($routes[1]) && $routes[1] === "tracks") {

		// /api/v1/tracks/get
		if(isset($routes[2]) && $routes[2] === "get") {

			// Request method has to be "GET" and token is required
			Request::checkRequestMethod(["GET"]);
			$payload = Request::requireToken($jwt);

			// /api/v1/tracks/get/[track-id]
			if(isset($routes[3]) && $routes[3] !== "") {
				$trackID = $routes[3];
			}
			include_once("tracks/get.php");

		}	// /api/v1/tracks/get

		// /api/v1/tracks/[track-id]/delete
		if(isset($routes[2]) && preg_match("/[a-zA-Z0-9]+/", $routes[2]) !== FALSE && isset($routes[3]) && $routes[3] === "delete") {

			// Request method has to be "DELETE" and token is required
			Request::checkRequestMethod(["DELETE"]);
			$payload = Request::requireToken($jwt);

			$trackID = $routes[2];
			include_once("tracks/delete.php");

		}	// /api/v1/tracks/[track-id]/delete

	}	// /api/v1/tracks


}	// /api/v1



print(json_encode([ "route" => $routes, "payload" => $payload ]));

// exit();

?>