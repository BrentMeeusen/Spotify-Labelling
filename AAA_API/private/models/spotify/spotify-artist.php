<?php

class SpotifyArtist implements SpotifyData {


	// Declare variables
	public string $name;
	public string $id;





	/**
	 * Constructor
	 * 
	 * @param		StdClass	The album data
	 */
	public function __construct(StdClass $data) {

		$this->name = $data->name;
		$this->id = $data->id;

	}

}

?>
