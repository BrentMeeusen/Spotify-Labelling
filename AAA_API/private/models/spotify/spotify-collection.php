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
	 * Creates a collection of tracks
	 * 
	 * @param		array				The tracks
	 * @return		SpotifyCollection	The collection
	 */
	public static function createTrackCollection(array $data) : SpotifyCollection {

		$tracks = [];
		foreach($data as $track) {
			array_push($tracks, new SpotifyTrack($track));
		}
		return new SpotifyCollection($tracks);

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


	/**
	 * Stores the element
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	// public function store() : bool;

}

?>
