<?php


class Database {

	// Declare variables
	private static string $host = "localhost";
	private static string $username = "u236549530_BrentSpotify";
	private static string $password = "MySp0t1fy!";
	private static string $database = "u236549530_SpotifyLabels";
	private static mysqli $conn;





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
	 * Prepares a statement
	 * 
	 * @param		string			SQL to prepare
	 * @return		mysqli_stmt		The prepared statement
	 */
	public static function prepare(string $SQL) : mysqli_stmt {

		$stmt = self::$conn->prepare($SQL);
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst preparing the statement", "SQL" => $SQL, "backtrace" => debug_backtrace()]);
		}
		return $stmt;

	}





	/**
	 * Executes a statement
	 * 
	 * @param		mysqli_stmt		The statement to execute
	 * @return		bool			Whether it was a success or not
	 */
	public static function execute(mysqli_stmt $stmt) : bool {
		
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst executing the statement", "data" => $stmt, "backtrace" => debug_backtrace()]);
		}
		return TRUE;

	}















	/**
	 * Finds an entry in a specific table with one parameter
	 * 
	 * @param		string		The SQL to run
	 * @param		string		The parameter
	 * @return		array		An associative array with objects of the results
	 */
	private static function find(string $SQL, string $parameter) : array {

		$stmt = self::prepare($SQL);
		$stmt->bind_param("s", $parameter);
		self::execute($stmt);
		$res = $stmt->get_result();
		return json_decode(json_encode($res->fetch_all(1)));

	}





	/**
	 * Finds an entry in a specific table with one parameter
	 * 
	 * @param		string		The SQL to run
	 * @param		string		The first parameter
	 * @param		string		The second parameter
	 * @return		array		An associative array with objects of the results
	 */
	private static function findLink(string $SQL, string $p1, string $p2) : array {

		$stmt = self::prepare($SQL);
		$stmt->bind_param("ss", $p1, $p2);
		self::execute($stmt);
		$res = $stmt->get_result();
		return json_decode(json_encode($res->fetch_all(1)));

	}










	/**
	 * Finds a track by Spotify ID
	 * 
	 * @param		string		The ID to search for
	 * @return		null		If not found
	 * @return		Track		If found
	 */
	public static function findTrackBySpotifyID(string $spotifyID) : ?Track {

		$data = self::find("SELECT * FROM TRACKS WHERE SpotifyID = ?;", $spotifyID);
		$track = (isset($data[0]) ? new Track($data[0]) : NULL);
		return $track;

	}





	/**
	 * Finds an album by Spotify ID
	 * 
	 * @param		string		The ID to search for
	 * @return		null		If not found
	 * @return		Album		If found
	 */
	public static function findAlbumBySpotifyID(string $spotifyID) : ?Album {

		$data = self::find("SELECT * FROM ALBUMS WHERE SpotifyID = ?;", $spotifyID);
		$album = (isset($data[0]) ? new Album($data[0]) : NULL);
		return $album;

	}





	/**
	 * Finds an artist by Spotify ID
	 * 
	 * @param		string		The ID to search for
	 * @return		null		If not found
	 * @return		Artist		If found
	 */
	public static function findArtistBySpotifyID(string $spotifyID) : ?Artist {

		$data = self::find("SELECT * FROM ARTISTS WHERE SpotifyID = ?;", $spotifyID);
		$artist = (isset($data[0]) ? new Artist($data[0]) : NULL);
		return $artist;

	}










	/**
	 * Finds a link between artist and track
	 * 
	 * @param		string		The track ID
	 * @param		string		The artist ID
	 * @return		null		If not found
	 * @return		array		Artist and Track if found
	 */
	public static function findTrackToArtist(string $trackID, string $artistID) : ?array {

		$data = self::findLink("SELECT * FROM TRACKS_TO_ARTISTS WHERE TrackID = ? AND ArtistID = ?;", $trackID, $artistID);
		if(empty($data)) { return NULL; }
		return [];
		// TODO: return [self::findArtist(artistID), self::findTrack(trackID)] as an associative ID

	}





	/**
	 * Finds a link between album and track
	 * 
	 * @param		string		The track ID
	 * @param		string		The album ID
	 * @return		null		If not found
	 * @return		array		Album and Track if found
	 */
	public static function findTrackToAlbum(string $trackID, string $albumID) : ?array {

		$data = self::findLink("SELECT * FROM TRACKS_TO_ALBUMS WHERE TrackID = ? AND AlbumID = ?;", $trackID, $albumID);
		if(empty($data)) { return NULL; }
		return [];
		// TODO: return [self::findAlbum(albumID), self::findTrack(trackID)] as an associative ID

	}





	/**
	 * Finds a link between user and track
	 * 
	 * @param		string		The track ID
	 * @param		string		The user ID
	 * @return		null		If not found
	 * @return		array		User and Track if found
	 */
	public static function findTrackToUser(string $trackID, string $userID) : ?array {

		$data = self::findLink("SELECT * FROM TRACKS_TO_USERS WHERE TrackID = ? AND UserID = ?;", $trackID, $userID);
		if(empty($data)) { return NULL; }
		return [];
		// TODO: return [self::findUser(userID), self::findTrack(trackID)] as an associative ID

	}










	/**
	 * Gets all the tracks that the user has imported
	 * 
	 * @param		string		The user ID
	 * @return		Tracks		All the tracks found
	 */
	public static function getTracksFromUser(string $userID) : Tracks {

		// Get all tracks the user has
		// TODO: figure out what happens on multiple artists with the same track!
		$tracks = self::find("SELECT T.*, TTU.AddedAt, ALB.Name AS AlbumName, ART.Name AS ArtistName FROM TRACKS AS T 
			JOIN TRACKS_TO_USERS AS TTU ON T.SpotifyID = TTU.TrackID 
			JOIN TRACKS_TO_ALBUMS AS TTALB ON T.SpotifyID = TTALB.TrackID 
			JOIN ALBUMS AS ALB ON ALB.SpotifyID = TTALB.AlbumID 
			JOIN TRACKS_TO_ARTISTS AS TTART ON T.SpotifyID = TTART.TrackID 
			JOIN ARTISTS AS ART ON ART.SpotifyID = TTART.ArtistID
			WHERE TTU.UserID = ?;", 
			$userID);

		// Create Track objects and store them in an array
		$ret = [];
		foreach($tracks as $track) {
			array_push($ret, new Track($track));
		}

		// Return the tracks
		return new Tracks($ret);

	}















	/**
	 * Create a table based on an SQL
	 * 
	 * @return	bool	true if everything went right
	 */
	private static function createTable(mysqli $conn, string $SQL, string $table) : bool {

		// Drop the table if it exists
		$stmt = self::prepare("DROP TABLE IF EXISTS $table;");
		self::execute($stmt);

		// Prepare the statement and check whether it's fine
		$stmt = self::prepare($SQL);
		self::execute($stmt);

		// Return true, because nothing went wrong
		return TRUE;

	}










	/**
	 * Creates USERS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createUsers(mysqli $conn) {

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
			AccessToken		VARCHAR(256),
			
			PRIMARY KEY (ID),
			UNIQUE(PublicID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates LABELS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createLabels(mysqli $conn) {

		$tableName = "LABELS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			PublicID		VARCHAR(32)		NOT NULL,
			Name			VARCHAR(100)	NOT NULL,
			IsPublic		INT(1)			NOT NULL,

			PRIMARY KEY (ID),
			UNIQUE (PublicID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates LABELS_TO_USERS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createLabelsToUsers(mysqli $conn) {

		$tableName = "LABELS_TO_USERS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			LabelID			VARCHAR(32)		NOT NULL,
			OwnerID			VARCHAR(32)		NOT NULL,
			IsHidden		INT(1)			NOT NULL	DEFAULT		0,

			PRIMARY KEY (ID),
			FOREIGN KEY (LabelID) REFERENCES LABELS (PublicID) ON DELETE CASCADE,
			FOREIGN KEY (OwnerID) REFERENCES USERS (PublicID) ON DELETE CASCADE
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates RIGHTS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createRights(mysqli $conn) {

		$tableName = "RIGHTS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			Name			VARCHAR(64)		NOT NULL,
			Value			BOOLEAN			NOT NULL,
			
			PRIMARY KEY (ID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);
	}





	/**
	 * Creates RIGHTS_TO_USERS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createRightsToUsers(mysqli $conn) {

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

	}





	/**
	 * Creates TRACKS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createTracks(mysqli $conn) {

		$tableName = "TRACKS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			SpotifyID		VARCHAR(50)		NOT NULL,
			Name			VARCHAR(250)	NOT NULL,
			ReleaseDate		DATE			NULL			DEFAULT		NULL,

			PRIMARY KEY (ID),
			UNIQUE (SpotifyID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates ARTISTS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createArtists(mysqli $conn) {

		$tableName = "ARTISTS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			SpotifyID		VARCHAR(50)		NOT NULL,
			Name			VARCHAR(250)	NOT NULL,

			PRIMARY KEY (ID),
			UNIQUE (SpotifyID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates ALBUMS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createAlbums(mysqli $conn) {

		$tableName = "ALBUMS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			SpotifyID		VARCHAR(50)		NOT NULL,
			Name			VARCHAR(250)	NOT NULL,

			PRIMARY KEY (ID),
			UNIQUE (SpotifyID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates TRACKS_TO_ARTISTS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createTracksToArtists(mysqli $conn) {

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

	}





	/**
	 * Creates TRACKS_TO_ALBUMS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createTracksToAlbums(mysqli $conn) {

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

	}





	/**
	 * Creates TRACKS_TO_USERS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createTracksToUsers(mysqli $conn) {

		$tableName = "TRACKS_TO_USERS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			TrackID			VARCHAR(50)		NOT NULL,
			UserID			VARCHAR(32)		NOT NULL,
			AddedAt			DATETIME		NOT NULL		DEFAULT		CURRENT_TIMESTAMP,

			PRIMARY KEY (ID),
			FOREIGN KEY (UserID) REFERENCES USERS (PublicID) ON DELETE CASCADE,
			FOREIGN KEY (TrackID) REFERENCES TRACKS (SpotifyID) ON DELETE CASCADE
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}





	/**
	 * Creates BANNED_IPS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createBannedIPs(mysqli $conn) {

		$tableName = "BANNED_IPS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			IP				VARCHAR(16)		NOT NULL,
			StartedAt		DATETIME		NOT NULL		DEFAULT		CURRENT_TIMESTAMP,
			EndsAfter		INT(7)			NOT NULL,

			PRIMARY KEY (ID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}










	/**
	 * Creates the tables needed
	 * 
	 * @param	mysqli 	database to create the tables in
	 */
	public static function initialise(mysqli $conn) {

		// Create tables
		self::createUsers($conn);
		self::createLabels($conn);
		self::createLabelsToUsers($conn);
		self::createRights($conn);
		self::createRightsToUsers($conn);
		self::createTracks($conn);
		self::createArtists($conn);
		self::createAlbums($conn);
		self::createTracksToArtists($conn);
		self::createTracksToAlbums($conn);
		self::createTracksToUsers($conn);
		self::createBannedIPs($conn);

		// Insert special rights into table
		$stmt = $conn->prepare("INSERT INTO RIGHTS (Name, Value) VALUES ('label.public', TRUE);");
		$res = $stmt->execute();

	}

}




?>