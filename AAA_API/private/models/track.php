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
	 * Stores the artists, the artist-album links and the artist-track links
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	private function storeArtists() : bool {

		// Prepare the artist-track link
		$artistTrack = Database::prepare("INSERT INTO TRACKS_TO_ARTISTS (TrackID, ArtistID) VALUES (?, ?);");

		// For every artist
		foreach($this->artists->artists as $artist) {

			// Store the artist
			$result = $artist->store();
			if($result === FALSE) { return FALSE; }

			// Prepare the artist-track link
			$artistTrack->bind_param("ss", $this->spotifyID, $artist->spotifyID);

			// Insert the links
			$result = Database::execute($artistTrack);
			if($result === FALSE) { return FALSE; }

		}

		return TRUE;

	}





	/**
	 * Stores the track and its album and artists, including the linking together
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {

		// Prepare the statement
		$stmt = Database::prepare("INSERT INTO TRACKS (Name, SpotifyID, ReleaseDate) VALUES (?, ?, ?)");

		// Insert the data
		$stmt->bind_param("sss", $this->name, $this->spotifyID, $this->releaseDate);

		// Execute the statement
		$result = Database::execute($stmt);
		if($result === FALSE) { return FALSE; }



		// Store the album
		$result = $this->album->store();
		if($result === FALSE) { return FALSE; }

		// Store the artists and the artist-track link
		return $this->storeArtists();

	}

}

?>