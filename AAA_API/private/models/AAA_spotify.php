<?php


class Spotify {


	/**
	 * Stores the collection in the database
	 * 
	 * @param		SpotifyCollection	A collection object
	 */
	public static function storeCollection(SpotifyCollection $collection) {

		print(json_encode("joe mama"));

	}
	
}










interface SpotifyCollection {

	public function getData() : array;

	public static function create(array $data) : SpotifyCollection;

}

?>