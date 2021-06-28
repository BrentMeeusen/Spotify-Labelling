<?php

class SpotifyPlaylist implements SpotifyData {


	// Declare variables
	public string $name;
	public int $numTracks;
	public string $id;





	/**
	 * Creates a playlist based on a JSON decoded Spotify response
	 * 
	 * @param		StdClass	The playlist data
	 */
	public function __construct(StdClass $data) {

		$this->name = $data->name;
		$this->numTracks = $data->tracks->total;
		$this->spotifyID = $data->id;

	}

}

?>
