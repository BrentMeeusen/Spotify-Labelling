<?php


class Albums implements SpotifyCollection {


	// Declare variables
	public array $albums;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Album objects
	 */
	public function __construct(array $albums) {
		$this->albums = $albums;
	}





	/**
	 * Formats the given albums in a collection
	 * 
	 * @param		array		An album array that Spotify sent
	 * @return		Albums		An Albums object with the given playlists
	 */
	public static function create(array $albums) : Albums {

		$newAlbums = [];
		foreach($albums as $album) {
			array_push($newAlbums, new Album($album));
		}
		return new Albums($newAlbums);

	}
	
}

?>