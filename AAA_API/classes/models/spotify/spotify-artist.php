<?php

class SpotifyArtist implements SpotifyData {


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
	 * Stores the artist in our database
	 * 
	 * @param		string		The track ID to connect to
	 * @return		bool		Whether it was a success or not
	 */
	public function store(string $trackID) : bool {

		// If the artist does not exist yet
		if(IArtist::findBySpotifyId($this->id) === NULL) {

			// Store the artist
			$stmt = Database::prepare("INSERT INTO ARTISTS (SpotifyID, Name) VALUES (?, ?);");
			$stmt->bind_param("ss", $this->id, $this->name);
			if(!(Database::execute($stmt))) {
				return FALSE;
			}

		}

		// If the track-artist link does not exist yet
		if(count(Database::findLink("SELECT * FROM TRACKS_TO_ARTISTS WHERE TrackID = ? AND ArtistID = ?;", $trackID, $this->id)) === 0) {

			// Store the track-artist link
			$stmt = Database::prepare("INSERT INTO TRACKS_TO_ARTISTS (TrackID, ArtistID) VALUES (?, ?);");
			$stmt->bind_param("ss", $trackID, $this->id);
			if(!(Database::execute($stmt))) {
				return FALSE;
			}

		}

		return TRUE;

	}

}

?>
