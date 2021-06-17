<?php


class Spotify {


	/**
	 * Stores the collection in the database
	 * 
	 * @param		SpotifyCollection	A collection object
	 */
	public static function storeCollection(SpotifyCollection $collection) {

	}
	
}










interface SpotifyCollection {

	public array $data;

	public static function create(array $data);

}

?>