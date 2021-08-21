<?php

class ITrack {


	// Declare variables
	public IAlbum $album;
	public ICollection $artists;
	public ICollection $labels;
	public string $id;
	public string $name;
	public string $releaseDate;
	public ?string $addedAt;





	/**
	 * Creates a track
	 * 
	 * @param		StdClass	The data
	 */
	public function __construct(StdClass $data) {

		$this->album = IAlbum::createFromTrack($data);
		$this->artists = ICollection::createArtistCollection([IArtist::createFromTrack($data)]);
		$this->labels = ICollection::createLabelCollection([Label::createFromTrack($data)]);

		$this->id = $data->SpotifyID;
		$this->name = $data->Name;
		$this->releaseDate = $data->ReleaseDate;
		$this->addedAt = $data->AddedAt;

	}





	/**
	 * Sets the artists
	 * 
	 * @param		ICollection	The artists
	 */
	public function setArtists(ICollection $artists) : void {
		$this->artists = $artists;
	}





	/**
	 * Sets the labels
	 * 
	 * @param		ICollection	The labels
	 */
	public function setLabels(ICollection $labels) : void {
		$this->labels = $labels;
	}










	/**
	 * Finds a track by Spotify ID
	 * 
	 * @param		string		The ID to search for
	 * @return		null		If not found
	 * @return		ITrack		If found
	 */
	public static function findBySpotifyId(string $spotifyID) : ?ITrack {

		$tracks = Database::find("SELECT T.*, TTU.AddedAt, ALB.Name AS AlbumName, ALB.SpotifyID AS AlbumID, GROUP_CONCAT(ART.Name) AS ArtistNames, GROUP_CONCAT(ART.SpotifyID) AS ArtistIDs, GROUP_CONCAT(L.Name) AS LabelNames, GROUP_CONCAT(TTL.LabelID) AS LabelIDs FROM TRACKS AS T 
			LEFT JOIN TRACKS_TO_USERS AS TTU ON T.SpotifyID = TTU.TrackID 	-- Always join track, even if no TTU exists
			JOIN TRACKS_TO_ALBUMS AS TTALB ON T.SpotifyID = TTALB.TrackID 
			JOIN ALBUMS AS ALB ON ALB.SpotifyID = TTALB.AlbumID 
			JOIN TRACKS_TO_ARTISTS AS TTART ON T.SpotifyID = TTART.TrackID 
			JOIN ARTISTS AS ART ON ART.SpotifyID = TTART.ArtistID 
			LEFT JOIN TRACKS_TO_LABELS AS TTL ON TTL.TrackID = T.SpotifyID	-- Always join track, even if no label exists
			LEFT JOIN LABELS AS L ON L.PublicID = TTL.LabelID
			WHERE T.SpotifyID = ?
			GROUP BY T.SpotifyID;", $spotifyID);

		if($tracks === NULL) { return NULL; }

		// Create Track objects and store them in an array
		$ret = [];
		foreach($tracks as $track) {
			array_push($ret, new ITrack($track));
		}

		// Create and return a collection of tracks
		$collection = ICollection::createTrackCollection($ret);
		return @$collection->format();

	}





	/**
	 * Gets all the tracks that the user has imported
	 * 
	 * @param		string		The user ID
	 * @return		ICollection	All the tracks found
	 * @return		null		If something went wrong
	 */
	public static function findByUser(string $userID) : ?ICollection {

		// Get all tracks the user has
		$tracks = Database::find("SELECT T.*, TTU.AddedAt, ALB.Name AS AlbumName, ALB.SpotifyID AS AlbumID, GROUP_CONCAT(ART.Name) AS ArtistNames, GROUP_CONCAT(ART.SpotifyID) AS ArtistIDs, GROUP_CONCAT(L.Name) AS LabelNames, GROUP_CONCAT(TTL.LabelID) AS LabelIDs FROM TRACKS AS T 
			LEFT JOIN TRACKS_TO_USERS AS TTU ON T.SpotifyID = TTU.TrackID 	-- Always join track, even if no TTU exists
			JOIN TRACKS_TO_ALBUMS AS TTALB ON T.SpotifyID = TTALB.TrackID 
			JOIN ALBUMS AS ALB ON ALB.SpotifyID = TTALB.AlbumID 
			JOIN TRACKS_TO_ARTISTS AS TTART ON T.SpotifyID = TTART.TrackID 
			JOIN ARTISTS AS ART ON ART.SpotifyID = TTART.ArtistID 
			LEFT JOIN TRACKS_TO_LABELS AS TTL ON TTL.TrackID = T.SpotifyID	-- Always join track, even if no label exists
			LEFT JOIN LABELS AS L ON L.PublicID = TTL.LabelID
			WHERE TTU.UserID = ?
			GROUP BY T.SpotifyID;", $userID);

		// Create Track objects and store them in an array
		$ret = [];
		foreach($tracks as $track) {
			array_push($ret, new ITrack($track));
		}

		// Create and return a collection of tracks
		$collection = ICollection::createTrackCollection($ret);
		return $collection->format();

	}










	/**
	 * Adds a tracks_to_labels link
	 * 
	 * @param		string		The label ID
	 */
	public function addLabel(string $labelID) : void {

		// If the link doesn't exist yet, add tracks_to_labels link
		if(count(Database::findLink("SELECT * FROM TRACKS_TO_LABELS WHERE TrackID = ? AND LabelID = ?;", $this->id, $labelID))  === 0) {
			$stmt = Database::prepare("INSERT INTO TRACKS_TO_LABELS (TrackID, LabelID) VALUES (?, ?);");
			$stmt->bind_param("ss", $this->id, $labelID);
			Database::execute($stmt);
		}

	}










	/**
	 * Removes the track_to_user link if existent, also removes artist, album if there is no other links
	 * 
	 * @param		string		The user ID
	 */
	public function removeUser(string $userID) : void {

		// Remove TTU link
		$stmt = Database::prepare("DELETE FROM TRACKS_TO_USERS WHERE TrackID = ? AND UserID = ?;");
		$stmt->bind_param("ss", $this->id, $userID);
		Database::execute($stmt);

		// If there are no other TTU links (aka nobody stores the track), get the track and remove it
		$links = Database::find("SELECT * FROM TRACKS_TO_USERS WHERE TrackID = ?;", $this->id);
		if(count($links) !== 0) { return; }

		$stmt = Database::prepare("DELETE FROM TRACKS WHERE SpotifyID = ?;");
		$stmt->bind_param("s", $this->id);
		Database::execute($stmt);

		// If there are no other TTAlbum links (aka there are no tracks in that album anymore), remove the album
		$links = Database::find("SELECT * FROM TRACKS_TO_ALBUMS WHERE AlbumID = ?;", $this->album->id);
		if(count($links) === 0) {
			$stmt = Database::prepare("DELETE FROM ALBUMS WHERE SpotifyID = ?;");
			$stmt->bind_param("s", $this->album->id);
			Database::execute($stmt);
		}

		// For each artist
		foreach($this->artists->data as $artist) {

			// If there are no other TTArtist links (aka there are no tracks from that artist anymore), remove the artist
			$links = Database::find("SELECT * FROM TRACKS_TO_ARTISTS WHERE ArtistID = ?;", $artist->id);
			if(count($links) === 0) {
				$stmt = Database::prepare("DELETE FROM ARTISTS WHERE SpotifyID = ?;");
				$stmt->bind_param("s", $artist->id);
				Database::execute($stmt);
			}

		}

	}










	/**
	 * Checks whether everything except the artist is equal
	 * 
	 * @param		ITrack		A track to compare to
	 * @return		bool		Whether they're equal or not
	 */
	public function equalsExceptArtist(ITrack $track) : bool {

		return $this->album->equals($track->album) &&
				$this->id === $track->id &&
				$this->name === $track->name &&
				$this->releaseDate === $track->releaseDate &&
				$this->addedAt === $track->addedAt;

	}





	/**
	 * Checks whether everything is equal
	 * 
	 * @param		ITrack		A track to compare to
	 * @return		bool		Whether they're equal or not
	 */
	public function equals(ITrack $track) : bool {

		return $this->equalsExceptArtist($track) &&
				$this->artists->equals($track->artists);

	}

}

?>
