<?php


// TODO		Create `Initialise extends Database` class
// TODO		Refactor AAA_Table class into Database class
// TODO		Create `Table extends Database` class
// TODO		Make Spotify classes extend table if needed
// TODO		Refactor Labels class and SQL table
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
	 * @return		ICollection	All the tracks found
	 * @return		null		If something went wrong
	 */
	public static function findTracksByUser(string $userID) : ?ICollection {

		// Get all tracks the user has
		// TODO: figure out what happens on multiple artists with the same track!
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
		return ICollection::createTrackCollection($ret);

	}

}

?>
