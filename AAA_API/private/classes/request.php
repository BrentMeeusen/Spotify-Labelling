<?php

class Request {


	/**
	 * Increments the number of requests for this minute
	 * 
	 * @param		string		The IP that makes the request
	 */
	public static function increment(string $ip) : void {

		// Check whether the IP already exists in the database
		$ip = Database::find("SELECT * FROM REQUESTS WHERE ID = ?;", $ip);

		// If the IP does not exist yet
		if(count($ip) === 0) {

			// Create a row for the IP
			$stmt = Database::prepare("INSERT INTO REQUESTS (IP, Minute) VALUES (?, ?);");
			$stmt->bind_param("ssi", $ip, date("Y-m-d H:i:s"));
			Database::execute($stmt);

		}

		// If the IP already exists
		else {

		}

	}


}



?>
