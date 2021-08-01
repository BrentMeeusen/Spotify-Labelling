<?php


// TODO		Refactor Users class


class Database {

	// Declare variables
	private static string $host = "localhost";
	private static string $username = "root";
	private static string $password = "";
	private static string $database = "spotify_labelling_api";
	private static mysqli $conn;


	// WARNING: HARDCODED
	// private static string $host = "localhost";
	// private static string $username = "u236549530_BrentSpotify";
	// private static string $password = "MySp0t1fy!";
	// private static string $database = "u236549530_SpotifyLabels";
	// private static mysqli $conn;





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
	 * Sanitizes a certain input
	 * 
	 * @param		string		The input to sanitize
	 * @return		string		The sanitized input
	 */
	protected function sanitize(string $toSanitize) : string {
		return mysqli_real_escape_string(self::$conn, $toSanitize);
	}





	/**
	 * Prepares the update method
	 * 
	 * @param		Database	The entry to update
	 * @param		array		The new values in an associative array
	 * @return		Database	The updated entry
	 */
	protected function prepareUpdate(Database $entry, array $values) : Database {

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
	 * @param		Database	A table child to delete
	 * @param		string		The table to delete data from
	 * @param		bool		Whether the entry was deleted successfully or not
	 */
	protected function deleteEntry(Database $entry, string $table) : bool {

		// Delete the entry
		$stmt = self::prepare("DELETE FROM $table WHERE PublicID = ?");
		$stmt->bind_param("s", $entry->publicID);
		self::execute($stmt);

		// Return TRUE because everything went right
		return TRUE;

	}










	/**
	 * Creates a unique, randomly generated ID
	 * 
	 * @param	string	The name of the table to check whether it's unique
	 * @return	string	The randomly generated ID
	 * @return	null	If something went wrong
	 */
	public function generateRandomID(string $tableName) : ?string {

		// If connection is not set, return null
		if(!isset(self::$conn)) {
			return NULL;
		}

		// As long as it's not unique, try finding a unique ID
		do {

			$chars = "0123456789";
			$randomID = "";
			for($i = 0; $i < 32; $i++) {
				$randomID .= $chars[rand(0, strlen($chars) - 1)];		
			}
			$data = self::find("SELECT ID FROM $tableName WHERE PublicID = ?;", $randomID);

		}
		while(count($data) !== 0);
		return $randomID;

	}










	/**
	 * Finds an entry in a specific table with one parameter
	 * 
	 * @param		string		The SQL to run
	 * @param		string		The parameter
	 * @return		array		An associative array with objects of the results
	 */
	public static function find(string $SQL, string $parameter) : array {

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
	public static function findLink(string $SQL, string $p1, string $p2) : array {

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
	 * @return		ICollection	All the tracks found
	 * @return		null		If something went wrong
	 */
	public static function findTracksByUser(string $userID) : ?ICollection {

		// Get all tracks the user has
		$tracks = self::find("SELECT T.*, TTU.AddedAt, ALB.Name AS AlbumName, ALB.SpotifyID AS AlbumID, ART.Name AS ArtistName, ART.SpotifyID AS ArtistID FROM TRACKS AS T 
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
			array_push($ret, new ITrack($track));
		}

		// Create and return a collection of tracks
		$collection = ICollection::createTrackCollection($ret);
		return $collection->merge();

	}

}

?>
