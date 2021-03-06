<?php


class SpotifyApi {


	// Declare variables
	private static string $authorisationToken;





	/**
	 * Sets the authorisation token
	 * 
	 * @param		string		The token
	 */
	public static function setAuthorisationToken(string $token) : void {
		self::$authorisationToken = $token;
	}









	/**
	 * Gets the email address the user is logged in with
	 * 
	 * @return		string		The email address
	 */
	public static function getEmailAddress() : string {

		$profile = self::sendRequest("v1/me", "GET");
		return $profile->email;

	}





	/**
	 * Gets the songs from a given playlist from the Spotify API
	 * 
	 * @param		string		The playlist ID
	 * @return		array		An array of tracks
	 */
	public static function getTracksFromPlaylist(string $playlistID) {

		// While there are still tracks left to retrieve, get the tracks
		$tracks = [];
		$next = ".com/v1/playlists/$playlistID/tracks";

		do {

			// Get the playlists, only add limit parameter if we're at the first parameter
			$response = self::sendRequest(explode(".com/", $next)[1], "GET");

			// Store the playlists
			foreach($response->items as $track) {
				if(!$track->is_local) { array_push($tracks, $track); }
			}

			// Setup the next request
			$next = $response->next;

		}
		while($next !== NULL);

		return $tracks;

	}





	/**
	 * Gets the playlists of the user
	 * 
	 * @return		array		An array of playlists
	 */
	public static function getMyPlaylists() : array {

		// While there are still playlists left to retrieve, get the playlists
		$playlists = [];
		$next = ".com/v1/me/playlists";
		$i = 1;

		do {

			// Get the playlists, only add limit parameter if we're at the first parameter
			$response = self::sendRequest(explode(".com/", $next)[1], "GET", ($i === 1 ? ["limit" => 50] : NULL));

			// Store the playlists
			foreach($response->items as $list) {
				array_push($playlists, $list);
			}

			// Setup the next request
			$next = $response->next;
			$i++;

		}
		while($next !== NULL);

		return $playlists;

	}





	/**
	 * Gets the liked tracks from the user
	 * 
	 * @return		array		The tracks
	 */
	public static function getMyLikedTracks() : array {

		$tracks = [];
		$next = ".com/v1/me/tracks";
		$i = 1;

		do {

			// Get the tracks, only add limit parameter if we're at the first parameter
			$response = self::sendRequest(explode(".com/", $next)[1], "GET", ($i === 1 ? ["limit" => 50] : NULL));

			// Store the tracks
			foreach($response->items as $track) {
				array_push($tracks, $track);
			}

			// Setup the next request
			$next = $response->next;
			$i++;

		}
		while($next !== NULL);

		return $tracks;

	}










	/**
	 * Sends a request to a certain endpoint
	 * 
	 * @param		string						The endpoint to send to
	 * @param		string						The method to use
	 * @param		array						The query parameters to send
	 * @param		array						The body parameters to send
	 * @return		StdClass					A standard class with the given data
	 * @throws		UnexpectedValueException	If Spotify responds with something else than a 2xx code
	 */
	private static function sendRequest(string $endpoint, string $method, ?array $queryParameters = [], ?array $bodyParameters = []) : StdClass {

		// Wait for 10ms to prevent HTTP 428/419 error
		usleep(10000);

		// Make the call to the Spotify API
		$context = stream_context_create([
			"http" => [
				"ignore_errors" => "true",
				"method" => $method,
				"header" => [
					"Content-Type: application/x-www-form-urlencoded",
					"Authorization: Bearer " . self::$authorisationToken
				],
				"content" => http_build_query($bodyParameters)
			]
		]);
		$url = "https://api.spotify.com/" . $endpoint . (($queryParameters !== NULL && !empty($queryParameters)) ? "?" . http_build_query($queryParameters) : "");
		$res = @file_get_contents($url, false, $context);
		$res = json_decode($res);

		$httpResponse = substr(explode("HTTP/1.0 ", $http_response_header[0])[1], 0, 3);

		// If the HTTP response is not 200, return an error
		if(substr($httpResponse, 0, 1) !== "2") {

			ApiResponse::httpResponse(400, ["error" => $res->error->message . " (" . $res->error->status . ")", "data" => $http_response_header]);

		}

		return $res;

	}

}


?>