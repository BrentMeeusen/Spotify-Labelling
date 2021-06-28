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
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		$this->album = new SpotifyAlbum($data->track->album);
		$this->artists = SpotifyCollection::createArtistCollection($data->track->artists);
		$this->name = $data->track->name;
		$this->releaseDate = $data->track->album->release_date;
		$this->id = $data->track->id;

	}

}

?>
