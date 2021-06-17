<?php


class Album implements SpotifyData {


	// Declare variables
	public string $name;
	public string $spotifyID;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The data from Spotify
	 */
	public function __construct(StdClass $data) {

		$this->name = $data->name;
		$this->spotifyID = $data->id;

	}





	/**
	 * Stores the track AND the links to album, artists, and user
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {


		return FALSE;
	}

}

?>