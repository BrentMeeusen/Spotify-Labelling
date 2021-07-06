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
	 * @param		ICollection	The collection
	 */
	public static function createTrackCollection(array $data) : ICollection {

	}





	/**
	 * Creates a collection of artists
	 * 
	 * @param		array		The artists
	 * @param		ICollection	The collection
	 */
	public static function createArtistCollection(array $data) : ICollection {

	}

}

?>
