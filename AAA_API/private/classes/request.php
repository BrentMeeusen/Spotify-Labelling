<?php

class Request {


	/**
	 * Increments the number of requests for this minute
	 * 
	 * @param		string		The IP that makes the request
	 */
	public static function increment(string $ip) : void {

		// Check whether the IP already exists in the database
		$found = Database::find("SELECT * FROM REQUESTS WHERE IP = ?;", $ip);

		// If the IP does not exist yet
		if(count($found) === 0) {

			// Create a row for the IP
			$stmt = Database::prepare("INSERT INTO REQUESTS (IP, Minute) VALUES (?, ?);");
			@$stmt->bind_param("ss", $ip, date("Y-m-d H:i"));
			Database::execute($stmt);

		}

		// If the IP already exists
		else {

			$foundDate = date("Y-m-d H:i", strtotime($found[0]->Minute));
			$currentDate = date("Y-m-d H:i");

			// If it is the exact same minute, increment count
			if($foundDate === $currentDate) {

				$newReq = $found[0]->NumberRequests + 1;
				$stmt = Database::prepare("UPDATE REQUESTS SET NumberRequests = ? WHERE IP = ?;");
				$stmt->bind_param("is", $newReq, $found[0]->IP);
				Database::execute($stmt);

			}

			// If it is not, set a new date and increment
			else {

				$stmt = Database::prepare("UPDATE REQUESTS SET NumberRequests = 1, Minute = ? WHERE IP = ?;");
				$stmt->bind_param("ss", $currentDate, $found[0]->IP);
				Database::execute($stmt);

			}

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
