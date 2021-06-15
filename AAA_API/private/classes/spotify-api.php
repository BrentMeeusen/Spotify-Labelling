<?php


class SpotifyApi {


	// Declare variables
	private static string $authorisationToken;





	/**
	 * Sets the authorisation token
	 * 
	 * @param		string		The token
	 */
	public static string setAuthorisationToken(string $token) : void {
		self::$authorisationToken = $token;
	}

}


?>