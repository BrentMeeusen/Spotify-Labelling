<?php


class Playlists implements SpotifyCollection {


	// Declare variables
	public array $data;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Playlist objects
	 */
	public function __construct(array $playlists) {
		$this->data = $playlists;
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