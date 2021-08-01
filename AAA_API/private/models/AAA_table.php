<?php


class Table {

	protected static mysqli $conn;










	/**
	 * Set the connection for the user object
	 * 
	 * @param		mysqli		The connection with the database
	 */
	public static function setConnection(mysqli $conn) {
		self::$conn = $conn;
	}





	/**
	 * Default method for duplicates check
	 * 
	 * @return		bool		False if no duplicates are found
	 * @return		array		[key => Property, value => Duplicate value]
	 */
	public function hasDuplicates() {
		return FALSE;
	}









	
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
	 * Executes the given statement
	 * 
	 * @param	mysqli_stmt		The statement to execute
	 * @return	mysqli_stmt		The same statement, but executed
	 */
	protected static function execute(mysqli_stmt $statement) : mysqli_stmt {

		// If the execution fails, return an error
		if(!$statement->execute()) {
			ApiResponse::httpResponse(500, ["error" => "The given statement could not be executed."]);
		}

		return $statement;

	}





	/**
	 * Returns the results of a given statement
	 * 
	 * @param	mysqli_stmt		The statement to find the results of
	 * @return	array			The results found in an associative array
	 */
	protected static function getResults(mysqli_stmt $statement) : array {

		$statement = self::execute($statement);

		// If getting the results fails, return an error
		$res = $statement->get_result();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "The statement encountered an unknown issue whilst fetching the records."]);
		}

		// Return the result in an associative array
		return $res->fetch_all(1);

	}

}















interface TableInterface {

	/**
	 * Static constructor
	 * 
	 * @param		array		The values to create an entry from
	 * @return		Table		The created entry
	 */
	public static function construct(array $values) : Table;

	/**
	 * Sanitizes the inputs
	 */
	public function sanitizeInputs() : void;

	/**
	 * Creates an entry in the database
	 * 
	 * @param		array		The values to create an entry from
	 * @return		Table		The created entry
	 */
	public static function create(array $values) : Table;

	/**
	 * Updates an entry in the database
	 * 
	 * @param		Table		The entry to update
	 * @param		array		The values to set
	 * @return		Table		The updated entry
	 */
	public static function update(Table $entry, array $values) : Table;

	/**
	 * Deletes an entry in the database
	 * 
	 * @param		Table		The entry to delete
	 * @return		bool		Whether it was deleted successfully or not
	 */
	public static function delete(Table $entry) : bool;

}


?>