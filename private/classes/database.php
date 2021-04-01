<?php


class Database {

	// Declare variables
	private static string $host = "localhost";
	private static string $username = "root";
	private static string $password = "";
	private static string $database = "spotify_labelling_api";
	private static mysqli $conn;


	/**
	 * Connects to the database
	 * 
	 * @return	mysqli	the connection
	 */
	public static function connect() : ?mysqli {

		self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$database);
		
		if(self::$conn->connect_errno !== 0) {
			httpResponseCode(500, "Database connection failed", [ "db_errno" => self::$conn->connect_errno, 
					"db_error" => self::$conn->connect_error ]);
			return NULL;
		}
		
		return self::$conn;

	}


	/**
	 * Creates the tables needed
	 * 
	 * @param	mysqli 	database to create the tables in
	 */

}




?>