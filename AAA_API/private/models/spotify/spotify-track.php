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

		return FALSE;

	}

}

?>
