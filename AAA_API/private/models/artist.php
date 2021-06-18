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

		$this->name = $data->name;
		$this->spotifyID = $data->id;

	}





	/**
	 * Stores the artist
	 * 
	 * @param		Album		The album to link to
	 * @return		bool		Whether it was a success or not
	 */
	public function store(Album $album) : bool {

		// Prepare the statement
		$stmt = Database::prepare("INSERT INTO ARTISTS (Name, SpotifyID) VALUES (?, ?)");

		// Insert the data
		$stmt->bind_param("ss", $this->name, $this->spotifyID);

		// Execute the statement and return the result
		$result = Database::execute($stmt);
		if($result === FALSE) { return FALSE; }

		// Add artist-album link
		$this->storeLink($album);

	}

}

?>