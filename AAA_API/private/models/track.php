<?php


class Track implements SpotifyData {


	// Declare variables
	public ?Album $album;
	public ?Artists $artists;
	public string $name;
	public string $releaseDate;
	public string $spotifyID;
	public ?string $addedAt;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		@$album->name = @$data->AlbumName;
		$album->id = 0;

		@$artist->name = @$data->ArtistName;
		$artist->id = 0;

		$this->album = (isset($data->track->album) ? new Album($data->track->album) : new Album($album));
		$this->artists = (isset($data->track->artists) ? Artists::create($data->track->artists) : new Artists([new Artist($artist)]));

		$this->name = (isset($data->track->name) ? $data->track->name : $data->Name);
		$this->releaseDate = (isset($data->track->album->release_date) ? date("Y-m-d", strtotime($data->track->album->release_date)) : $data->ReleaseDate);
		$this->spotifyID = (isset($data->track->id) ? $data->track->id : $data->SpotifyID);
		$this->addedAt = (isset($data->AddedAt) ? $data->AddedAt : null);

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
	 * @param		StdClass	The payload
	 * @return		bool		Whether it was a success or not
	 */
	public function store(StdClass $payload) : bool {

		// Store the track if it does not exist yet
		if(Database::findTrackBySpotifyID($this->spotifyID) === NULL) {

			// Prepare the statement
			$stmt = Database::prepare("INSERT INTO TRACKS (Name, SpotifyID, ReleaseDate) VALUES (?, ?, ?)");

			// Insert the data
			$stmt->bind_param("sss", $this->name, $this->spotifyID, $this->releaseDate);

			// Execute the statement
			$result = Database::execute($stmt);
			if($result === FALSE) { return FALSE; }

		}



		// Store the user-track link if it does not exist yet
		if(Database::findTrackToUser($this->spotifyID, $payload->user->id) === NULL) {

			$stmt = Database::prepare("INSERT INTO TRACKS_TO_USERS (TrackID, UserID) VALUES (?, ?);");
			$stmt->bind_param("ss", $this->spotifyID, $payload->user->id);
			$result = Database::execute($stmt);
			if($result === FALSE) { return FALSE; }

		}
		


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