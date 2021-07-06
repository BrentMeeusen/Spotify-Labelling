<?php

class IArtist {


	// Declare variables
	public string $id;
	public string $name;





	/**
	 * Creates an artist
	 * 
	 * @param		StdClass	The data
	 */
	public function __construct(StdClass $data) {
		$this->id = $data->id;
		$this->name = $data->name;
	}





	/**
	 * Creates an artist from the data that the track returned
	 * 
	 * @param		StdClass	The track data
	 * @return		IArtist		The created artist
	 */
	public static function createFromTrack(StdClass $trackData) : IArtist {

		@$artistData->id = $trackData->ArtistID;
		$artistData->name = $trackData->ArtistName;
		return new IArtist($artistData);

	}

}

?>
