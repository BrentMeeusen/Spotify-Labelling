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

		// If the track does not exist yet
		if(ITrack::findBySpotifyId($this->id) === NULL) {

			// Store the track
			$stmt = Database::prepare("INSERT INTO TRACKS (SpotifyID, Name, ReleaseDate) VALUES (?, ?, ?);");
			$stmt->bind_param("sss", $this->id, $this->name, $this->releaseDate);
			if(!(Database::execute($stmt))) {
				return FALSE;
			}

		}

		// If the track-user link does not exist yet
		if(count(Database::findLink("SELECT * FROM TRACKS_TO_USERS WHERE TrackID = ? AND UserID = ?;", $this->id, $userID)) === 0) {

			// Store the track-user link
			$stmt = Database::prepare("INSERT INTO TRACKS_TO_USERS (TrackID, UserID) VALUES (?, ?);");
			$stmt->bind_param("ss", $this->id, $userID);
			if(!(Database::execute($stmt))) {
				return FALSE;
			}

		}

		// Store the album
		if(!($this->album->store($this->id))) {
			return FALSE;
		}

		// Store the artists
		return $this->artists->store($this->id);

	}

}

?>
