<?php


class Album implements SpotifyData {


	// Declare variables
	public string $name;
	public string $spotifyID;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		$this->name = $data->name;
		$this->spotifyID = $data->id;

	}





	/**
	 * Stores the track AND the links to album, artists, and user
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {

		// Prepare the statement
		$stmt = Database::prepare("INSERT INTO ALBUMS (Name, SpotifyID) VALUES (?, ?)");

		// Insert the data
		$stmt->bind_param("ss", $this->name, $this->spotifyID);

		// Execute the statement and return the result
		return Database::execute($stmt);

	}

}

?>