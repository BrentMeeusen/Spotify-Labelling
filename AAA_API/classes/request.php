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

				// If the number of requests is exceeded, exit
				if($newReq >= 1500) {
					ApiResponse::httpResponse(429, ["error" => "Please lower the amount of requests you make."]);
				}

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
	 * Checks whether the request method is correct
	 * 
	 * @param		array		The allowed methods
	 * @param		string		The method
	 */
	public static function checkRequestMethod(array $allowed) : void {

		if(!(in_array($_SERVER["REQUEST_METHOD"], $allowed))) {
			ApiResponse::httpResponse(405, ["error" => "Method not allowed."]);
		}

	}





	/**
	 * Checks whether the token is valid
	 * 
	 * @param		string		The token
	 * @return		StdClass	The most recent payload
	 */
	public static function requireToken(string $token) : StdClass {

		// Verify the token and get the payload if it's valid
		JSONWebToken::validateToken($token);
		$payload = JSONWebToken::getPayload($token);

		if($payload === NULL || !isset($payload->user) || !isset($payload->user->id)) {
			ApiResponse::httpResponse(500, ["error" => "Could not validate your account."]);
		}

		// Check if this user actually exists
		$user = User::findByPublicID($payload->user->id);
		if($user === NULL) {
			ApiResponse::httpResponse(500, ["error" => "Could not validate your account."]);
		}

		// Get the newest payload from the user so that it is up-to-date with the info in the database
		return json_decode(json_encode($user->createPayload()));

	}

}



?>
