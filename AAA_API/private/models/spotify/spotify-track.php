<?php

class SpotifyTrack implements SpotifyData {


	// Declare variables
	public SpotifyAlbum $album;
	public SpotifyCollection $artists;
	public string $name;
	public string $releaseDate;
	public string $id;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The track data
	 */
	public function __construct(StdClass $data) {

		$this->album = new SpotifyAlbum($data->track->album);
		$this->artists = SpotifyCollection::createArtistCollection($data->track->artists);
		$this->name = $data->track->name;
		$this->releaseDate = $data->track->album->release_date;
		$this->id = $data->track->id;

	}





	/**
	 * Stores the track in our database
	 * 
	 * @param		string		The user ID that wants to store
	 * @return		bool		Whether it was a success or not
	 */
	public function store(string $userID) : bool {

		// If the track exists, everything exists (given that it all went right) and we can return TRUE
		if(Database::findTrackBySpotifyID($this->id) !== NULL) {
			return TRUE;
		}

		// Store the track
		$stmt = Database::prepare("INSERT INTO TRACKS (SpotifyID, Name, ReleaseDate) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $this->id, $this->name, $this->releaseDate);
		Database::execute($stmt);

		return FALSE;

	}

}

?>
