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
	 * Formats the found playlists in this collection
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