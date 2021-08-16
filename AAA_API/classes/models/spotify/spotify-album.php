<?php

class SpotifyAlbum implements SpotifyData {


	// Declare variables
	public string $name;
	public string $id;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The album data
	 */
	public function __construct(StdClass $data) {

		$this->name = $data->name;
		$this->id = $data->id;

	}





	/**
	 * Stores the album in our database
	 * 
	 * @param		string		The track ID that it is connected to
	 * @return		bool		Whether it was a success or not
	 */
	public function store(string $trackID) : bool {

		// If the album does not exist yet
		if(IAlbum::findBySpotifyId($this->id) === NULL) {

			// Store the album
			$stmt = Database::prepare("INSERT INTO ALBUMS (SpotifyID, Name) VALUES (?, ?);");
			$stmt->bind_param("ss", $this->id, $this->name);
			if(!(Database::execute($stmt))) {
				return FALSE;
			}

		}

		// If the track-album link does not exist yet
		if(Database::findTrackToAlbum($trackID, $this->id) === NULL) {

			// Store the track-album link
			$stmt = Database::prepare("INSERT INTO TRACKS_TO_ALBUMS (TrackID, AlbumID) VALUES (?, ?);");
			$stmt->bind_param("ss", $trackID, $this->id);
			if(!(Database::execute($stmt))) {
				return FALSE;
			}

		}

		return TRUE;

	}

}

?>
