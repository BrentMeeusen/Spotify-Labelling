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
	 */
	public static function getMyPlaylists() {

		return self::sendRequest("v1/me/playlists", "GET");

	}










	/**
	 * Sends a request to a certain endpoint
	 * 
	 * @param		string		The endpoint to send to
	 * @param		string		The method to use
	 * @param		array		The parameters to send
	 * @return		StdClass	A standard class with the given data
	 */
	private static function sendRequest(string $endpoint, string $method, array $parameters = []) : StdClass {

		// Make the call to the Spotify API
		$parameters = http_build_query($parameters);
		$context = stream_context_create([
			"http" => [
				"ignore_errors" => "true"
				"method" => $method,
				"header" => [
					"Content-Type: application/x-www-form-urlencoded",
					"Authorization: Bearer " . self::$authorisationToken
				],
				"content" => $parameters
			]
		]);
		$res = @file_get_contents("https://api.spotify.com/" . $endpoint, false, $context);

		return json_encode($res);

	}

}


?>