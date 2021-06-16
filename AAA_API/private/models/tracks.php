<?php


class Tracks {


	// Declare variables
	public array $tracks;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Track objects
	 */
	public function __construct(array $tracks) {
		$this->tracks = $tracks;
	}





	/**
	 * Formats the given tracks
	 * 
	 * @param		array		A tracks array that Spotify sent
	 * @return		Tracks		A Tracks object with the given tracks
	 */
	public static function create(array $tracks) : Tracks {

		$tracks = [];
		foreach($tracks as $track) {
			array_push($tracks, new Track($track));
		}
		return new Tracks($tracks);

	}


}

?>