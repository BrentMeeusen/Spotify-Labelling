<?php

class SpotifyCollection {


	/**
	 * Creates a collection of playlists
	 * 
	 * @param		array				The playlists
	 * @return		SpotifyCollection	The collection
	 */
	public static function createPlaylists(array $playlists) : SpotifyCollection {

		$lists = [];
		foreach($playlists as $list) {
			array_push($lists, new Playlist($list));
		}
		return new SpotifyCollection($lists);

	}

}










interface SpotifyData {

}

?>
