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





	/**
	 * Stores the artist in our database
	 * 
	 * @param		string		The user ID that wants to store
	 * @return		bool		Whether it was a success or not
	 */
	public function store(string $userID) : bool {

		return FALSE;

	}

}

?>
