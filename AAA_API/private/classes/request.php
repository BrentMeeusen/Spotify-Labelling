<?php

class Request {


	/**
	 * Increments the number of requests for this minute
	 * 
	 * @param		string		The IP that makes the request
	 */
	public static function increment(string $ip) : void {

		print(json_encode($ip));
		exit();

		// Check whether the IP already exists in the database
		$ip = Database::find("SELECT * FROM REQUESTS WHERE ID = ?;", $ip);

	}


}



?>
