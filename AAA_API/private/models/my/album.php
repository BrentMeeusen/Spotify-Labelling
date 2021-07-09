<?php

class IAlbum {


	// Declare variables
	public string $id;
	public string $name;





	/**
	 * Creates an album
	 * 
	 * @param		StdClass	The data
	 */
	public function __construct(StdClass $data) {
		$this->id = $data->id;
		$this->name = $data->name;
	}





	/**
	 * Creates an album from the data that the track returned
	 * 
	 * @param		StdClass	The track data
	 * @return		IAlbum		The created album
	 */
	public static function createFromTrack(StdClass $trackData) : IAlbum {

		@$albumData->id = $trackData->AlbumID;
		$albumData->name = $trackData->AlbumName;
		return new IAlbum($albumData);

	}





	/**
	 * Finds an album from our database given a Spotify ID
	 * 
	 * @param		string		The Spotify ID to search for
	 * @return		IAlbum		If it was found
	 * @return		null		If it was not found
	 */
	public static function findBySpotifyId(string $id) : ?IAlbum {

		$data = Database::find("SELECT * FROM ALBUMS WHERE SpotifyID = ?;", $id);
		return (isset($data[0] ? self::createFromDatabase($data[0]) : NULL);

	}










	/**
	 * Checks whether everything is equal
	 * 
	 * @param		IAlbum		The album to compare to
	 * @return		bool		Whether they're equal or not
	 */
	public function equals(IAlbum $album) : bool {

		return $this->id === $album->id &&
				$this->name === $album->name;

	}

}

?>
