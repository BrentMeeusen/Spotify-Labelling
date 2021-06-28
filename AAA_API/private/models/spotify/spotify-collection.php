<?php

class SpotifyCollection {


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
	 * Creates a collection of playlists
	 * 
	 * @param		array				The playlists
	 * @return		SpotifyCollection	The collection
	 */
	public static function createPlaylistCollection(array $playlists) : SpotifyCollection {

		$lists = [];
		foreach($playlists as $list) {
			array_push($lists, new SpotifyPlaylist($list));
		}
		return new SpotifyCollection($lists);

	}

}










interface SpotifyData {

}

?>
