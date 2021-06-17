<?php


class Spotify {

}










interface SpotifyData {


	/**
	 * Stores the data in the database
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool;

}





interface SpotifyCollection {

	
	/**
	 * Gets the data from the collection
	 * 
	 * @return		array		The data
	 */
	public function getData() : array;


	/**
	 * Stores the entire collection in the database
	 * 
	 * @return		bool		Whether it was a success or not
	 */
	public function store() : bool;


	/**
	 * Creates a collection object from the given data
	 * 
	 * @param		array		The objects to create from Spotify's data
	 */
	public static function create(array $data) : SpotifyCollection;

}

?>