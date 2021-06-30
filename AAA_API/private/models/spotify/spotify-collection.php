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
	 * Creates a collection of artists
	 * 
	 * @param		array				The artists
	 * @return		SpotifyCollection	The collection
	 */
	public static function createArtistCollection(array $data) : SpotifyCollection {

		$tracks = [];
		foreach($data as $track) {
			array_push($tracks, new SpotifyArtist($track));
		}
		return new SpotifyCollection($tracks);

	}





	 /**
	 * Creates a collection of playlists
	 * 
	 * @param		array				The playlists
	 * @return		SpotifyCollection	The collection
	 */
	public static function createPlaylistCollection(array $data) : SpotifyCollection {

		$playlists = [];
		foreach($data as $list) {
			array_push($playlists, new SpotifyPlaylist($list));
		}
		return new SpotifyCollection($playlists);

	}










	/**
	 * Stores the collection
	 * 
	 * @param		string		The ID to work with
	 * @return		bool		Whether it was a success or not
	 */
	public function store(string $id) : bool {

		foreach($this->data as $row) {
			if(!$row->store($id)) {
				return FALSE;
			}
		}
		return TRUE;

	}

}










interface SpotifyData {


	/**
	 * Stores the element
	 * 
	 * @param		string		The user ID who wants to store
	 * @return		bool		Whether it was a success or not
	 */
	public function store(string $userID) : bool;

}

?>
