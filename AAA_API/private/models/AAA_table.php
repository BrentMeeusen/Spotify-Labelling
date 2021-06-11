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
	 * Creates a unique, randomly generated ID
	 * 
	 * @param	string	The name of the table to check whether it's unique
	 * @return	string	The randomly generated ID
	 * @return	null	If something went wrong
	 */
	protected static function generateRandomID(string $tableName) : ?string {

		// If connection is not set, return null
		if(!isset(self::$conn)) {
			return NULL;
		}

		// As long as it's not unique, keep doing this
		do {

			// Generate the random ID 
			$chars = "0123456789";
			$randomID = "";
			for($i = 0; $i < 32; $i++) {
				$randomID .= $chars[rand(0, strlen($chars) - 1)];		
			}

			// Check if it already exists in the database
			$tableName = self::sanitizeArray([$tableName])[0];
			
			$stmt = self::prepare("SELECT ID FROM $tableName WHERE PublicID = ?");
			$stmt->bind_param("s", $randomID);
			$res = self::execute($stmt);
			$data = self::getResults($stmt);

		}
		while(count($data) !== 0);

		return $randomID;


	}





	/**
	 * Prepares the update method
	 * 
	 * @param		Table		The entry to update
	 * @param		array		The new values in an associative array
	 * @return		Table		The updated entry
	 */
	protected static function prepareUpdate(Table $entry, array $values) : Table {

		// Update its values with the newest values
		foreach($values as $key => $value) {
			$entry->{lcfirst($key)} = $value;
		}

		// Check for duplicate values that should be unique
		$dupes = $entry->hasDuplicates();
		if($dupes !== FALSE) {
			ApiResponse::httpResponse(400, ["error" => "There already exists " . $dupes["key"] . " with the value \"" . $dupes["value"] . "\"."]);
		}

		// Sanitize input and return the entry
		$entry->sanitizeInputs();
		return $entry;

	}





	/**
	 * Deletes an entry from the given table
	 * 
	 * @param		Table		A table child to delete
	 * @param		string		The table to delete data from
	 * @param		bool		Whether the entry was deleted successfully or not
	 */
	protected static function deleteEntry(Table $entry, string $table) {

		// Delete the entry
		$stmt = self::prepare("DELETE FROM $table WHERE PublicID = ?");
		$stmt->bind_param("s", $entry->publicID);
		self::execute($stmt);

		// Return TRUE because everything went right
		return TRUE;

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
	 */
	public static function construct() : Table;

	/**
	 * Checks for duplicates
	 */
	public function hasDuplicates() : void;

	/**
	 * Sanitizes the inputs
	 */
	public function sanitizeInputs() : void;

	/**
	 * Creates an entry
	 */
	public static function create() : Table;

	/**
	 * Updates an entry
	 */
	public static function update() : Table;

	/**
	 * Deletes an entry
	 */
	public static function delete() : bool;

}


?>