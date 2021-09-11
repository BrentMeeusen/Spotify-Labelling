<?php

class IPlaylist {


	// Declare variables
	public string $publicID;
	public string $name;
	public string $creator;





	/**
	 * Playlist constructor
	 * 
	 * @param		string		The public ID of the playlist
	 * @param		string		The name of the playlist
	 * @param		string		The public ID of the creator
	 */
	public function __construct(string $publicID, string $name, string $creator) {

		$this->publicID = $publicID;
		$this->name = $name;
		$this->creator = $creator;

	}

}


?>
