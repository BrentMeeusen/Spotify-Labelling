<?php

class Request {


	/**
	 * Increments the number of requests for this minute
	 * 
	 * @param		string		The IP that makes the request
	 */
	public static function increment(string $ip) : void {

		// Check whether the IP already exists in the database
		$found = Database::find("SELECT * FROM REQUESTS WHERE ID = ?;", $ip);

		print(json_encode([$ip, $found]));

		// If the IP does not exist yet
		if(count($found) === 0) {

			// Create a row for the IP
			$stmt = Database::prepare("INSERT INTO REQUESTS (IP, Minute) VALUES (?, ?);");
			@$stmt->bind_param("ss", $ip, date("Y-m-d H:i:s"));
			Database::execute($stmt);

		}

		// If the IP already exists
		else {

			// Check for the datetime
			print(json_encode($ip));
			exit();

		}

	}





	/**
	 * Checks whether the limit is exceeded
	 */
	public static function checkLimit(string $ip) {
		return NULL;
	}


}



?>
