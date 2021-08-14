<?php

class ITrack {


	// Declare variables
	public IAlbum $album;
	public ICollection $artists;
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
	 * Finds a track by Spotify ID
	 * 
	 * @param		string		The ID to search for
	 * @return		null		If not found
	 * @return		ITrack		If found
	 */
	public static function findBySpotifyId(string $spotifyID) : ?ITrack {

		$data = Database::find("SELECT T.*, TTU.AddedAt, ALB.Name AS AlbumName, ALB.SpotifyID AS AlbumID, ART.Name AS ArtistName, ART.SpotifyID AS ArtistID FROM TRACKS AS T 
			LEFT JOIN TRACKS_TO_USERS AS TTU ON T.SpotifyID = TTU.TrackID 	-- Always join track, even if no TTU exists
			JOIN TRACKS_TO_ALBUMS AS TTALB ON T.SpotifyID = TTALB.TrackID 
			JOIN ALBUMS AS ALB ON ALB.SpotifyID = TTALB.AlbumID 
			JOIN TRACKS_TO_ARTISTS AS TTART ON T.SpotifyID = TTART.TrackID 
			JOIN ARTISTS AS ART ON ART.SpotifyID = TTART.ArtistID
			WHERE T.SpotifyID = ?;", $spotifyID);

		return (isset($data[0]) ? new ITrack($data[0]) : NULL);

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

		Database::prepare("DELETE FROM TRACKS WHERE SpotifyID = ?;");
		$stmt->bind_param("s", $this->id);
		Database::execute();

		// If there are no other TTAlbum links (aka there are no tracks in that album anymore), remove the album

		// If there are no other TTArtist links (aka there are no tracks from that artist anymore), remove the artist

		ApiResponse::httpResponse(200, ["message" => "Removing..."]);
		return;

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
