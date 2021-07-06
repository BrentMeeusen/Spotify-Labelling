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
