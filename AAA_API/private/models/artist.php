<?php


class Artist implements SpotifyData {


	// Declare variables
	public string $name;
	public string $spotifyID;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		$this->name = (isset($data->name) ? $data->name : $data->Name);
		$this->spotifyID = (isset($data->id) ? $data->id : $data->SpotifyID);

	}





	/**
	 * Stores the artist
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {

		// If the row is already there, return TRUE
		if(Database::findArtistBySpotifyID($this->spotifyID) !== NULL) {
			return TRUE;
		}

		// Prepare the statement
		$stmt = Database::prepare("INSERT INTO ARTISTS (Name, SpotifyID) VALUES (?, ?);");

		// Insert the data
		$stmt->bind_param("ss", $this->name, $this->spotifyID);

		// Execute the statement and return the result
		return Database::execute($stmt);

	}

}

?>