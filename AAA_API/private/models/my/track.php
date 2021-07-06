<?php

class ITrack {


	// Declare variables
	public IAlbum $album;
	public IArtist $artist;
	public string $id;
	public string $name;
	public string $releaseDate;
	public string $addedAt;





	/**
	 * Creates a track
	 * 
	 * @param		StdClass	The data
	 */
	public function __construct(StdClass $data) {

		$this->album = IAlbum::createFromTrack($data);
		$this->artist = IArtist::createFromTrack($data);

		$this->id = $data->SpotifyID;
		$this->name = $data->Name;
		$this->releaseDate = $data->ReleaseDate;
		$this->addedAt = $data->AddedAt;

	}

}

?>
