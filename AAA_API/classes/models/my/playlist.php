<?php

class IPlaylist extends Database {


	// Declare variables
	public string $publicID;
	public string $name;
	public string $creator;





	/**
	 * Playlist constructor
	 * 
	 * @param		string		The public ID of the playlist
	 * @param		string		The name of the playlist
	 * @param		string		The public ID of the creator
	 */
	public function __construct(string $publicID, string $name, string $creator) {

		$this->publicID = $publicID;
		$this->name = $name;
		$this->creator = $creator;

	}





	/**
	 * Sanitizes the inputs
	 */
	public function sanitizeInputs() : void {
		$this->name = parent::sanitize($this->name);
	}





	/**
	 * Default method for duplicates check
	 * 
	 * @return		bool		False if no duplicates are found
	 * @return		array		[key => Property, value => Duplicate value]
	 */
	public function hasDuplicates() {

		// Get all entries that are from this creator with this name
		$res = parent::findLink("SELECT P.* FROM PLAYLISTS AS P JOIN PLAYLISTS_TO_USERS AS PTU ON P.PublicID = PTU.PlaylistID WHERE PTU.UserID = ? AND P.Name = ?;", $this->creator, $this->name);

		// Return whether it has found a duplicate or not
		return (count($res) === 0 ? FALSE : ["key" => "a playlist", "value" => $res[0]->Name]);

	}










	/**
	 * Create the playlist with the given values
	 * 
	 * @param		array		The values to create the playlist with
	 * @return		IPlaylist	The playlist that was created
	 */
	public static function create(array $values) : IPlaylist {

		// Create a playlist object
		$playlist = new IPlaylist(Database::generateRandomID("PLAYLISTS"), $values["Name"], $values["Creator"]);

		// Create playlist
		$stmt = self::prepare("INSERT INTO PLAYLISTS (PublicID, Name) VALUES (?, ?);");
		$playlist->sanitizeInputs();
		$stmt->bind_param("ss", $playlist->publicID, $playlist->name);
		self::execute($stmt);

		// Create playlist-to-user
		$stmt = self::prepare("INSERT INTO PLAYLISTS_TO_USERS (PlaylistID, UserID) VALUES (?, ?);");
		$stmt->bind_param("ss", $playlist->publicID, $playlist->creator);
		self::execute($stmt);

		// Return the result
		return $playlist;

	}





	/**
	 * Updates the playlist
	 * 
	 * @param		IPlaylist	The playlist to update
	 * @param		array		The new values in an associative array
	 * @param		IPlaylist	The updated playlist
	 */
	public static function update(IPlaylist $playlist, array $values) : IPlaylist {

		// Prepare the update process
		$playlist = parent::prepareUpdate($playlist, $values);

		// Do the actual updating
		$stmt = self::prepare("UPDATE PLAYLISTS SET Name = ? WHERE PublicID = ?;");
		$stmt->bind_param("ss", $playlist->name, $playlist->publicID);
		self::execute($stmt);

		// Return the new playlist
		return $playlist;

	}





	/**
	 * Deletes a playlist
	 * 
	 * @param		IPlaylist	The playlist to delete
	 */
	public static function delete(IPlaylist $playlist) : void {
		parent::deleteEntry($playlist, "PLAYLISTS");
	}










	/**
	 * Finds the playlist by playlist public ID
	 * 
	 * @param		string		The playlist ID
	 * @return		IPlaylist	If it was found
	 * @return		null		If no playlist was found
	 */
	public static function findById(string $id) : ?IPlaylist {

		// If no playlist was found, return NULL
		$res = parent::find("SELECT P.*, PTU.UserID AS UserID FROM PLAYLISTS AS P JOIN PLAYLISTS_TO_USERS AS PTU ON P.PublicID = PTU.PlaylistID WHERE PublicID = ?;", $id);
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found playlist as an object
		return new IPlaylist($res[0]->PublicID, $res[0]->Name, $res[0]->UserID);

	}





	/**
	 * Finds the playlist by name
	 * 
	 * @param		string		The name of the playlist
	 * @param		string		The owner ID
	 * @return		IPlaylist	If it was found
	 * @return		null		If no playlist was found
	 */
	public static function findByName(string $name, string $userID) : ?IPlaylist {

		// If no playlist is found, return NULL
		$res = parent::findLink("SELECT P.* FROM PLAYLISTS AS P JOIN PLAYLISTS_TO_USERS AS PTU ON P.PublicID = PTU.PlaylistID WHERE Name = ? AND PTU.UserID = ?;", $name, $userID);
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found playlist as an object
		return new IPlaylist($res[0]->PublicID, $res[0]->Name, $userID);

	}





	/**
	 * Finds the playlist by creator
	 * 
	 * @param		string		The creator ID
	 * @return		array		If playlists were found
	 * @return		null		If no playlist was found
	 */
	public static function findByCreator(string $userID) : ?array {

		// If no labels are found, return NULL
		$res = parent::find("SELECT * FROM PLAYLISTS AS P JOIN PLAYLISTS_TO_USERS AS PTU ON P.PublicID = PTU.PlaylistID WHERE PTU.UserID = ?;", $userID);
		if(count($res) === 0) {
			return NULL;
		}

		// Loop over all labels and return the array
		$return = [];
		foreach($res as $row) {
			array_push($return, new IPlaylist($row->PublicID, $row->Name, $userID));
		}

		return $return;
	}

}


?>
