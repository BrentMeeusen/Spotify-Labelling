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

		$this->name = (isset($data->track->name) ? $data->track->name : $data->Name);
		$this->releaseDate = (isset($data->track->album->release_date) ? strtotime($data->track->album->release_date) : $data->ReleaseDate);
		$this->spotifyID = (isset($data->track->id) ? $data->track->id : $data->SpotifyID);

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

			// If the link already exists, continue
			if(Database::findTrackToArtist($this->spotifyID, $artist->spotifyID) !== NULL) {
				continue;
			}

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

		// If the row is already there, return TRUE
		if(Database::findTrackBySpotifyID($this->spotifyID) !== NULL) {
			return TRUE;
		}

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

		// Store the album-track link if it does not exist yet
		if(Database::findTrackToAlbum($this->spotifyID, $this->album->spotifyID) === NULL) {

			$stmt = Database::prepare("INSERT INTO TRACKS_TO_ALBUMS (TrackID, AlbumID) VALUES (?, ?);");
			$stmt->bind_param("ss", $this->spotifyID, $this->album->spotifyID);
			$result = Database::execute($stmt);
			if($result === FALSE) { return FALSE; }

		}

		// Store the artists and the artist-track link
		return $this->storeArtists();

	}

}

?>