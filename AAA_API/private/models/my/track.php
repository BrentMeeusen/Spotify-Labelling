<?php

class ITrack {


	// Declare variables
	public IAlbum $album;
	public ICollection $artists;
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
		$this->artists = ICollection::createArtistCollection([IArtist::createFromTrack($data)]);

		$this->id = $data->SpotifyID;
		$this->name = $data->Name;
		$this->releaseDate = $data->ReleaseDate;
		$this->addedAt = $data->AddedAt;

	}










	/**
	 * Checks whether everything except the artist is equal
	 * 
	 * @param		ITrack		A track to compare to
	 * @return		bool		Whether they're equal or not
	 */
	public function equalExceptArtist(ITrack $track) : bool {

		return $this->album->equals($track->album) &&
				$this->id === $track->id &&
				$this->name === $track->name &&
				$this->releaseDate === $track->releaseDate &&
				$this->addedAt === $track->addedAt;

	}





	/**
	 * Checks whether everything is equal
	 * 
	 * @param		ITrack		A track to compare to
	 * @return		bool		Whether they're equal or not
	 */
	public function equals(ITrack $track) : bool {

		return $this->equalExceptArtist($track) &&
				$this->artists->equals($track->artists);

	}

}

?>
