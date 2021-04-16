<?php


class Table {

	protected static mysqli $conn;





	/**
	 * Sanitizes the given array
	 * 
	 * @param	array	All values to be sanitized
	 * @return	array	Sanitized array
	 */
	private static function sanitizeArray(array $inputs) : array {

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
	public static function prepare(string $SQL) : mysqli_stmt {
		$res = self::$conn->prepare($SQL);
		if($res === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst preparing the statement."]);
		}
		return $res;
	}

}


?>