<?php


class Spotify {

}










interface SpotifyData {

}





interface SpotifyCollection {

	
	/**
	 * Gets the data from the collection
	 * 
	 * @return		array		The data
	 */
	public function getData() : array;


	/**
	 * Creates a collection object from the given data
	 * 
	 * @param		array				The objects to create from Spotify's data
	 * @return		SpotifyCollection	A Spotify Collection
	 */
	public static function create(array $data) : SpotifyCollection;

}

?>