<?php

class ICollection {


	// Declare variables
	public array $data;





	/**
	 * Creates a collection
	 * 
	 * @param		array		The data to create a collection from
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}










	/**
	 * Creates a collection of tracks
	 * 
	 * @param		array		The tracks
	 * @return		ICollection	The collection
	 * @return		null		If something went wrong
	 */
	public static function createTrackCollection(array $data) : ?ICollection {

		$tracks = [];
		foreach($data as $row) {
			if(!($row instanceof ITrack)) {
				return NULL;
			}
			array_push($tracks, $row);
		}
		return new ICollection($tracks);

	}





	/**
	 * Creates a collection of artists
	 * 
	 * @param		array		The artists
	 * @return		ICollection	The collection
	 * @return		null		If something went wrong
	 */
	public static function createArtistCollection(array $data) : ?ICollection {

		$artists = [];
		foreach($data as $row) {
			if(!($row instanceof IArtist)) {
				return NULL;
			}
			array_push($artists, $row);
		}
		return new ICollection($artists);

	}

}

?>
