<?php


class Table {

	protected static mysqli $conn;





	/**
	 * Sanitizes the given array
	 * 
	 * @param	array	All values to be sanitized
	 * @return	array	Sanitized array
	 */
	protected static function sanitizeArray(array $inputs) : array {

		$sanitized = [];
		foreach($inputs as $input) {
			array_push($sanitized, htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $input)))));
		}
		return $sanitized;

	}





	/**
	 * Prepares an SQL statement
	 * 
	 * @param	string			SQL to prepare
	 * @return	mysqli_stmt		Statement result
	 */
	protected static function prepare(string $SQL) : mysqli_stmt {

		$res = self::$conn->prepare($SQL);
		if($res === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst preparing the statement."]);
		}
		return $res;

	}




	/**
	 * Returns the results of a given statement
	 * 
	 * @param	mysqli_stmt		The statement to find the results of
	 * @return	array			The results found in an associative array
	 */
	protected static function getResults(mysqli_stmt $statement) : array {
		
		// If the execution fails, return an error
		$exec = $statement->execute();
		if($exec === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "The given statement could not be executed."]);
		}

		// If getting the results fails, return an error
		$res = $statement->get_result();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "The statement encountered an unknown issue whilst fetching the records."]);
		}

		// Return the result in an associative array
		return $res->fetch_all(1);

	}

}


?>