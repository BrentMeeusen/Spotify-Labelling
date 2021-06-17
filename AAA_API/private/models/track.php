<?php


class Track {


	// Declare variables
	public Album $album;
	public Artists $artists;
	public string $name;
	public string $spotifyID;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data rom Spotify
	 */
	public function __construct(StdClass $data) {

		$this->album = new Album($data->track->album);
		$this->artists = Artists::create($data->track->artists);
		$this->name = $data->track->name;
		$this->spotifyID = $data->track->id;

	}

}

?>