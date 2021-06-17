<?php


class Tracks implements SpotifyCollection {


	// Declare variables
	public array $data;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Track objects
	 */
	public function __construct(array $tracks) {
		$this->data = $tracks;
	}





	/**
	 * Getter for the array
	 */
	public function getData() : array {
		return $this->data;
	}





	/**
	 * Formats the given tracks in a collection
	 * 
	 * @param		array		A tracks array that Spotify sent
	 * @return		Tracks		A Tracks object with the given tracks
	 */
	public static function create(array $tracks) : Tracks {

		$newTracks = [];
		foreach($tracks as $track) {
			array_push($newTracks, new Track($track));
		}
		return new Tracks($newTracks);

	}


}

?>