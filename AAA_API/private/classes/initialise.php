<?php

class Initialise extends Database {


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
	 * Creates REQUESTS table
	 * 
	 * @param		mysqli		The database to create the table in
	 */
	private static function createRequests(mysqli $conn) {

		$tableName = "REQUESTS";
		$SQL = "CREATE TABLE $tableName (
			ID				INT(11)			NOT NULL	AUTO_INCREMENT,
			IP				VARCHAR(64)		NOT NULL,
			Minute			DATETIME		NOT NULL,
			NumberRequests	INT(3)			NOT NULL	DEFAULT		1,

			PRIMARY KEY (ID)
		);";
		$res = self::createTable($conn, $SQL, $tableName);

	}










	/**
	 * Creates the tables needed
	 * 
	 * @param	mysqli 	database to create the tables in
	 */
	public static function createTables(mysqli $conn) {

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
		self::createRequests($conn);

		// Insert special rights into table

	}

}

?>