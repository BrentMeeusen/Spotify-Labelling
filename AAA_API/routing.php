<?php

// Headers here because this file is added to all endpoints
header("Access-Control-Allow-Origin: http://spotify-labelling.21webb.nl");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Collect data
$url = $_SERVER["REQUEST_URI"];
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

	// /api/v1/login
	if(isset($routes[1]) && $routes[1] === "login") {

		Request::checkRequestMethod(["POST"]);

		$identifier = $post->Identifier;
		$password = $post->Password;
		include_once("users/login.php");

	}	// /api/v1/login



	// /api/v1/register
	else if(isset($routes[1]) && $routes[1] === "register") {

		Request::checkRequestMethod(["POST"]);
		include_once("users/register.php");

	}	// /api/v1/register



	// /api/v1/tracks
	else if(isset($routes[1]) && $routes[1] === "tracks") {

		// /api/v1/tracks/get
		if(isset($routes[2]) && $routes[2] === "get") {

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

			Request::checkRequestMethod(["DELETE"]);
			$payload = Request::requireToken($jwt);

			$trackID = $routes[2];
			include_once("tracks/delete.php");

		}	// /api/v1/tracks/[track-id]/delete

	}	// /api/v1/tracks



	// /api/v1/users
	if(isset($routes[1]) && $routes[1] === "users") {

		// /api/v1/users/add-token
		if(isset($routes[2]) && $routes[2] === "add-token") {

			Request::checkRequestMethod(["POST"]);
			$payload = Request::requireToken($jwt);

			$token = $post->accessToken;
			include_once("users/add-token.php");

		}	// /api/v1/users/add-token

		// /api/v1/users/create
		if(isset($routes[2]) && $routes[2] === "create") {

			Request::checkRequestMethod(["POST"]);
			$payload = JSONWebToken::getPayload($jwt);

			$email = $post->EmailAddress;
			$password = $post->Password;
			include_once("users/create.php");

		}	// /api/v1/users/create

		// /api/v1/users/delete
		if((isset($routes[2]) && $routes[2] === "delete") || (isset($routes[3]) && $routes[3] === "delete")) {

			Request::checkRequestMethod(["DELETE"]);
			$payload = Request::requireToken($jwt);

			$id = (isset($routes[3]) ? $routes[2] : NULL);
			$password = $post->Password;
			include_once("users/delete.php");

		}	// /api/v1/users/delete

		// /api/v1/users/get
		if(isset($routes[2]) && $routes[2] === "get") {

			Request::checkRequestMethod(["GET"]);
			$payload = Request::requireToken($jwt);

			include_once("users/get.php");

		}	// /api/v1/users/get

		// /api/v1/users/update
		if((isset($routes[2]) && $routes[2] === "update") || (isset($routes[3]) && $routes[3] === "update")) {

			Request::checkRequestMethod(["POST"]);
			$payload = Request::requireToken($jwt);

			$id = (isset($routes[3]) ? $routes[2] : NULL);
			$email = $post->EmailAddress;
			$password = $post->Password;
			include_once("users/update.php");

		}	// /api/v1/users/update

		// /api/v1/users/verify
		if(isset($routes[2]) && $routes[2] === "verify") {

			Request::checkRequestMethod(["GET"]);

			$id = @$routes[3];
			$email = @$routes[4];
			include_once("users/verify.php");

		}	// /api/v1/users/verify

	}	// /api/v1/users

}	// /api/v1



@print(json_encode([ "route" => $routes, "payload" => $payload ]));

// exit();

?>