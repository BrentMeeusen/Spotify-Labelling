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

			// Get the playlists
			$response = self::sendRequest(explode(".com/", $next)[1], "GET", ($i === 1 ? ["limit" => 50] : NULL));
			file_put_contents("test-" . $i . ".json", json_encode($response, JSON_PRETTY_PRINT));

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
	 * Sends a request to a certain endpoint
	 * 
	 * @param		string		The endpoint to send to
	 * @param		string		The method to use
	 * @param		array		The query parameters to send
	 * @param		array		The body parameters to send
	 * @return		StdClass	A standard class with the given data
	 */
	private static function sendRequest(string $endpoint, string $method, ?array $queryParameters = [], ?array $bodyParameters = []) : StdClass {

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
		$url = "https://api.spotify.com/" . $endpoint . ($queryParameters !== NULL ? "?" . http_build_query($queryParameters) : "");
		$res = @file_get_contents($url, false, $context);

		return json_decode($res);

	}

}


?>