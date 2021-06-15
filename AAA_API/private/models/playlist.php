<?php


class Playlist {


	// Declare variables
	private string $name;
	private int $numTracks;
	private string $spotifyID;





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