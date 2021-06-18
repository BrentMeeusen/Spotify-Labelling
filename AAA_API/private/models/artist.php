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
	 * @param		Track		The track to link to
	 * @return		bool		Whether it was a success or not
	 */
	public function store(Track $track) : bool {

		// Prepare the statement
		$stmt = Database::prepare("INSERT INTO ARTISTS (Name, SpotifyID) VALUES (?, ?);");

		// Insert the data
		$stmt->bind_param("ss", $this->name, $this->spotifyID);

		// Execute the statement and return the result
		$result = Database::execute($stmt);
		if($result === FALSE) { return FALSE; }



		// Add artist-album link
		$stmt = Database::prepare("INSERT INTO ARTISTS_TO_ALBUMS (ArtistID, AlbumID) VALUES (?, ?);");
		$stmt->bind_param("ss", $this->spotifyID, $track->album->spotifyID);
		$result = Database::execute($stmt);
		if($result === FALSE) { return FALSE; }

		// Add artist-track link
		$stmt = Database::prepare("INSERT INTO TRACKS_TO_ARTISTS (ArtistID, TrackID) VALUES (?, ?);");
		$stmt->bind_param("ss", $this->spotifyID, $track->spotifyID);
		return Database::execute($stmt);		

	}

}

?>