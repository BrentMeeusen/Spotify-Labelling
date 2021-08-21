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
	 * @return		ICollection	The created artist
	 */
	public static function createFromTrack(StdClass $trackData) : ICollection {

		$artists = [];
		$ids = explode(",", $trackData->ArtistIDs);
		$names = explode(",", $trackData->ArtistNames);

		for($i = 0; $i < count($ids); $i++) {
			@$data->id = $ids[$i];
			$data->name = $names[$i];
			array_push($artists, new IArtist($data));
		}
		
		return new ICollection($artists);

	}





	/**
	 * Creates an artist from the data that the database returned
	 * 
	 * @param		StdClass	The artist data
	 * @return		IArtist		The created artist
	 */
	public static function createFromDatabase(StdClass $artist) : IArtist {

		@$data->id = $artist->SpotifyID;
		$data->name = $artist->Name;
		return new IArtist($data);

	}










	/**
	 * Finds an artist from our database given a Spotify ID
	 * 
	 * @param		string		The Spotify ID to search for
	 * @return		IArtist		If it was found
	 * @return		null		If it was not found
	 */
	public static function findBySpotifyId(string $id) : ?IArtist {

		$data = Database::find("SELECT * FROM ARTISTS WHERE SpotifyID = ?;", $id);
		return (isset($data[0]) ? self::createFromDatabase($data[0]) : NULL);

	}










	/**
	 * Checks whether everything is equal
	 * 
	 * @param		IArtist		The artist to compare to
	 * @return		bool		Whether they're equal or not
	 */
	public function equals(IArtist $artist) : bool {

		return $this->id === $artist->id &&
				$this->name === $artist->name;

	}

}

?>
