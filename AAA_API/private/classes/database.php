<?php


class Database {

	// Declare variables
	private static string $host = "localhost";
	private static string $username = "root";
	private static string $password = "";
	private static string $database = "spotify_labelling_api";
	private static mysqli $conn;


	/*
	WARNING: HARDCODED VALUES; MAKE SURE TO UPDATE THEM!
	private static string $host = "localhost";
	private static string $username = "u236549530_BrentSpotify";
	private static string $password = "MySp0t1fy!";
	private static string $database = "u236549530_SpotifyLabels";
	private static mysqli $conn;
	*/





	/**
	 * Prepares a statement
	 * 
	 * @param		string		SQL to prepare
	 * @return		mysqli_stmt	The prepared statement
	 */
	public static function prepare(string $SQL) : mysqli_stmt {

		$stmt = self::$conn->prepare($SQL);
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst preparing the statement", "SQL" => $SQL]);
		}
		return $stmt;

	}





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
	 * Updates constraints
	 * 
	 * @param	mysqli 	database to create the tables in
	 */
	public static function updateConstraint(mysqli $conn) {

		$table = "LABELS";
		

		
		$stmt = $conn->prepare("ALTER TABLE $table DROP FOREIGN KEY Creator;");
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst preparing table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}

		// Execute the statement and check whether nothing went wrong
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst creating table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}



		$stmt = $conn->prepare("ALTER TABLE $table ADD CONSTRAINT owner_account FOREIGN KEY (Creator) REFERENCES USERS (PublicID) ON DELETE CASCADE;");
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst preparing table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}

		// Execute the statement and check whether nothing went wrong
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst creating table \"$table\"", "db_errno" => $conn->errno, "db_error" => $conn->error ]);
		}
		
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
			ID				INT(11)			NOT NULL 	AUTO_INCREMENT,
			PublicID		VARCHAR(32)		NOT NULL,
			FirstName		VARCHAR(50) 	NOT NULL,
			LastName		VARCHAR(50)		NOT NULL,
			Username		VARCHAR(100)	NOT NULL,
			EmailAddress	VARCHAR(250)	NOT NULL,
			Password		VARCHAR(256)	NOT NULL,
			AccountStatus	INT(1)			NOT NULL,
			AccessToken		VARCHAR(240),
			
			PRIMARY KEY (ID),
			UNIQUE(PublicID)
		);";

		// $res = self::createTable($conn, $SQL, $tableName);


		// Create LABELS table
		$tableName = "LABELS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			PublicID		VARCHAR(32)		NOT NULL,
			Creator			VARCHAR(32)		NOT NULL,
			Name			VARCHAR(100)	NOT NULL,
			IsPublic		INT(1)			NOT NULL,

			PRIMARY KEY (ID),
			FOREIGN KEY (Creator) REFERENCES USERS (PublicID) ON DELETE CASCADE,
			UNIQUE (PublicID)
		);";

		// $res = self::createTable($conn, $SQL, $tableName);










		// Add row to USERS table
		$stmt = $conn->prepare("ALTER TABLE USERS ADD COLUMN 
		AccessToken		VARCHAR(240);");
		$res = $stmt->execute();



		// Create RIGHTS table
		$tableName = "RIGHTS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			Name			VARCHAR(64)		NOT NULL,
			Value			BOOLEAN			NOT NULL,
			
			PRIMARY KEY (ID)
		);";

		$res = self::createTable($conn, $SQL, $tableName);

		// Insert special rights into table
		$stmt = $conn->prepare("INSERT INTO RIGHTS (Name, Value) VALUES ('label.public', TRUE);");
		$res = $stmt->execute();

		// Create RIGHTS_TO_USERS table
		$tableName = "RIGHTS_TO_USERS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			UserID			VARCHAR(32)		NOT NULL,
			RightID			INT(11)			NOT NULL,
			
			PRIMARY KEY (ID),
			FOREIGN KEY (UserID) REFERENCES USERS (PublicID) ON DELETE CASCADE,
			FOREIGN KEY (RightID) REFERENCES RIGHTS (ID) ON DELETE CASCADE
		);";

		$res = self::createTable($conn, $SQL, $tableName);

		// Create TRACKS table
		$tableName = "TRACKS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			SpotifyID		VARCHAR(50)		NOT NULL,
			Name			VARCHAR(250)	NOT NULL,
			ReleaseDate		TIMESTAMP		NULL			DEFAULT		NULL,
			AddedAt			TIMESTAMP		NOT NULL		DEFAULT		CURRENT_TIMESTAMP,

			PRIMARY KEY (ID),
			UNIQUE (SpotifyID)
		);";

		$res = self::createTable($conn, $SQL, $tableName);


		// Create ARTISTS table
		$tableName = "ARTISTS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			SpotifyID		VARCHAR(50)		NOT NULL,
			Name			VARCHAR(250)	NOT NULL,

			PRIMARY KEY (ID),
			UNIQUE (SpotifyID)
		);";

		$res = self::createTable($conn, $SQL, $tableName);


		// Create ALBUMS table
		$tableName = "ALBUMS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			SpotifyID		VARCHAR(50)		NOT NULL,
			Name			VARCHAR(250)	NOT NULL,

			PRIMARY KEY (ID),
			UNIQUE (SpotifyID)
		);";

		$res = self::createTable($conn, $SQL, $tableName);


		// Create TRACKS_TO_ARTISTS table
		$tableName = "TRACKS_TO_ARTISTS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			TrackID			VARCHAR(50)		NOT NULL,
			ArtistID		VARCHAR(50)		NOT NULL,

			PRIMARY KEY (ID),
			FOREIGN KEY (TrackID) REFERENCES TRACKS (SpotifyID) ON DELETE CASCADE,
			FOREIGN KEY (ArtistID) REFERENCES ARTISTS (SpotifyID) ON DELETE CASCADE
		);";

		$res = self::createTable($conn, $SQL, $tableName);


		// Create TRACKS_TO_ALBUMS table
		$tableName = "TRACKS_TO_ALBUMS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			TrackID			VARCHAR(50)		NOT NULL,
			AlbumID			VARCHAR(50)		NOT NULL,

			PRIMARY KEY (ID),
			FOREIGN KEY (TrackID) REFERENCES TRACKS (SpotifyID) ON DELETE CASCADE,
			FOREIGN KEY (AlbumID) REFERENCES ALBUMS (SpotifyID) ON DELETE CASCADE
		);";

		$res = self::createTable($conn, $SQL, $tableName);


		// Create ARTISTS_TO_ALBUMS table
		$tableName = "ARTISTS_TO_ALBUMS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			ArtistID		VARCHAR(50)		NOT NULL,
			AlbumID			VARCHAR(50)		NOT NULL,

			PRIMARY KEY (ID),
			FOREIGN KEY (ArtistID) REFERENCES ARTISTS (SpotifyID) ON DELETE CASCADE,
			FOREIGN KEY (AlbumID) REFERENCES ALBUMS (SpotifyID) ON DELETE CASCADE
		);";

		$res = self::createTable($conn, $SQL, $tableName);


		// Create USERS_TO_TRACKS table
		$tableName = "USERS_TO_TRACKS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			UserID			VARCHAR(32)		NOT NULL,
			TrackID			VARCHAR(50)		NOT NULL,

			PRIMARY KEY (ID),
			FOREIGN KEY (UserID) REFERENCES USERS (PublicID) ON DELETE CASCADE,
			FOREIGN KEY (TrackID) REFERENCES TRACKS (SpotifyID) ON DELETE CASCADE
		);";

		$res = self::createTable($conn, $SQL, $tableName);



	}

}




?>