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

}


?>
