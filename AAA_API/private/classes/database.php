<?php


class Database {

	// Declare variables
	private static string $host = "localhost";
	private static string $username = "root";
	private static string $password = "";
	private static string $database = "spotify_labelling_api";
	private static mysqli $conn;





	/**
	 * Create a table based on an SQL
	 * 
	 * @return	bool	true if everything went right
	 */
	private static function createTable(mysqli $conn, string $SQL, string $table) : bool {

		// Drop the table if it exists
		$stmt = $conn->prepare("DROP TABLE IF EXISTS $table;");
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst preparing table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}

		// Execute the statement and check whether nothing went wrong
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst creating table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}


		// Prepare the statement and check whether it's fine
		$stmt = $conn->prepare($SQL);
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst preparing table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}

		// Execute the statement and check whether nothing went wrong
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst creating table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}

		// Return true, because nothing went wrong
		return TRUE;

	}


	/**
	 * Connects to the database
	 * 
	 * @return	mysqli	the connection
	 */
	public static function connect() : mysqli {

		self::$conn = @new mysqli(self::$host, self::$username, self::$password, self::$database);
		
		if(self::$conn->connect_errno !== 0) {
			ApiResponse::httpResponse(500, [ "error" => "Database connection failed", "db_errno" => self::$conn->connect_errno, "db_error" => self::$conn->connect_error ]);
		}
		
		return self::$conn;

	}


	/**
	 * Creates the tables needed
	 * 
	 * @param	mysqli 	database to create the tables in
	 */
	public static function initialise(mysqli $conn) {

		// Create USERS table
		$tableName = "USERS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT 			NOT NULL 	AUTO_INCREMENT,
			FirstName		VARCHAR(50) 	NOT NULL,
			LastName		VARCHAR(50)		NOT NULL,
			Username		VARCHAR(100)	NOT NULL,
			EmailAddress	VARCHAR(250)	NOT NULL,
			Password		VARCHAR(256)	NOT NULL,
			AccountStatus	INT(1)			NOT NULL,
			
			PRIMARY KEY (ID)
		);";

		// $res = self::createTable($conn, $SQL, $tableName);


		// Create LABELS table


		// Create RIGHTS table


		// Create SONGS table


		// Create ARTISTS table



	}

}




?>