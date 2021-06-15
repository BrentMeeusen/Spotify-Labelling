<?php


class Playlist {


	// Declare variables
	public string $name;
	public int $numTracks;
	public string $spotifyID;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		$this->name = $data->name;
		$this->numTracks = $data->tracks->total;
		$this->spotifyID = $data->id;

	}

}


?>