<?php


class Artists implements SpotifyCollection {


	// Declare variables
	public array $data;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Artist objects
	 */
	public function __construct(array $artists) {
		$this->data = $artists;
	}





	/**
	 * Stores the collection
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {

		// For all artists
		foreach($this->data as $artist) {

			// Store the artist
			$res = $artist->store();
			if($res === FALSE) { return $res; }

		}

		// Return TRUE because everything went right
		return TRUE;
	}





	/**
	 * Getter for the array
	 */
	public function getData() : array {
		return $this->data;
	}





	/**
	 * Formats the given artists in a collection
	 * 
	 * @param		array		An artist array that Spotify sent
	 * @return		Artists		An Artists object with the given artists
	 */
	public static function create(array $artists) : Artists {

		$newArtists = [];
		foreach($artists as $artist) {
			array_push($newArtists, new Artist($artist));
		}
		return new Artists($newArtists);

	}

}

?>