<?php


class Track implements SpotifyData {


	// Declare variables
	public Album $album;
	public Artists $artists;
	public string $name;
	public int $releaseDate;
	public string $spotifyID;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		$this->album = new Album($data->track->album);
		$this->artists = Artists::create($data->track->artists);
		$this->name = $data->track->name;
		$this->releaseDate = strtotime($data->track->album->release_date);
		$this->spotifyID = $data->track->id;

	}





	/**
	 * Stores the track AND the links to album, artists, and user
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {

		// Store the album
		$result = $this->album->store();
		if($result === FALSE) { return FALSE; }

		// Store the artists
		$result = $this->artists->store($this->album);
		if($result === FALSE) { return FALSE; }



		// Prepare the statement
		$stmt = Database::prepare("INSERT INTO TRACKS (Name, SpotifyID, ReleaseDate) VALUES (?, ?, ?)");

		// Insert the data
		$stmt->bind_param("sss", $this->name, $this->spotifyID, $this->releaseDate);

		// Execute the statement
		$result = Database::execute($stmt);
		if($result === FALSE) { return FALSE; }



		// Add track-album link
		$result = $this->storeLink("ALBUMS");
		if($result === FALSE) { return FALSE; }

		// Add track-artists link
		return $this->storeLinks("ARTISTS");

	}

}

?>