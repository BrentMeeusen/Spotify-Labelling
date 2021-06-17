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