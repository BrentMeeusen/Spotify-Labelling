<?php


class Playlists {


	// Declare variables
	public array $playlists;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Playlist objects
	 */
	public function __construct(array $playlists) {
		$this->playlists = $playlists;
	}





	/**
	 * Stores the collection
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool {
		return FALSE;
	}





	/**
	 * Getter for the array
	 */
	public function getData() : array {
		return $this->playlists;
	}





	/**
	 * Formats the given playlists in a collection
	 * 
	 * @param		array		A playlist array that Spotify sent
	 * @return		Playlists	A playlists object with the given playlists
	 */
	public static function create(array $playlists) : Playlists {

		$lists = [];
		foreach($playlists as $list) {
			array_push($lists, new Playlist($list));
		}
		return new Playlists($lists);

	}

}

?>