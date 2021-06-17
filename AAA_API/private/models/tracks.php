<?php


class Tracks implements SpotifyCollection {


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
	 * Stores the collection
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {

		// For all tracks
		foreach($this->tracks as $track) {

			// Store the album
			$res = $track->album->store();
			if($res === FALSE) { return $res; }

			// Store the artists
			$res = $track->artists->store();
			if($res === FALSE) { return $res; }

			// Store the track which will also store the links to the album, artists, and user
			$res = $track->store();
			if($res === FALSE) { return $res; }

		}

		// Return TRUE because everything went right
		return TRUE;
	}





	/**
	 * Getter for the array
	 */
	public function getData() : array {
		return $this->tracks;
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