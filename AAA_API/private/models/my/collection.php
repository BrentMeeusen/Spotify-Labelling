<?php

class ICollection {


	// Declare variables
	public array $data;





	/**
	 * Creates a collection
	 * 
	 * @param		array		The data to create a collection from
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}










	/**
	 * Merges all tracks where only the artist collection is different
	 * 
	 * @return		ICollection	The new collection
	 * @return		null		If something went wrong
	 */
	public function merge() : ?ICollection {

		$newTracks = [];

		// For each element
		for($i = 0; $i < count($this->data);) {

			// If it's not a track, return NULL
			$track = $this->data[$i];
			if(!($track instanceof ITrack)) { return NULL; }

			// Get all artists from this track
			$offset = 0;
			$artists = $track->artists->data;

			while(($i + ++$offset) < count($this->data)) {
				$next = $this->data[$i + $offset];
				if($track->equalsExceptArtist($next)) {
					array_push($artists, $next->artists->data[0]);
				} else { break; }
			}

			// Save the track
			$newTrack = $track;
			$newTrack->setArtists(new ICollection($artists));
			array_push($newTracks, $newTrack);

			// Skip the merged tracks
			$i += $offset;

		}

		return new ICollection($newTracks);

	}










	/**
	 * Creates a collection of tracks
	 * 
	 * @param		array		The tracks
	 * @return		ICollection	The collection
	 * @return		null		If something went wrong
	 */
	public static function createTrackCollection(array $data) : ?ICollection {

		$tracks = [];
		foreach($data as $row) {
			if(!($row instanceof ITrack)) {
				return NULL;
			}
			array_push($tracks, $row);
		}
		return new ICollection($tracks);

	}





	/**
	 * Creates a collection of artists
	 * 
	 * @param		array		The artists
	 * @return		ICollection	The collection
	 * @return		null		If something went wrong
	 */
	public static function createArtistCollection(array $data) : ?ICollection {

		$artists = [];
		foreach($data as $row) {
			if(!($row instanceof IArtist)) {
				return NULL;
			}
			array_push($artists, $row);
		}
		return new ICollection($artists);

	}

}

?>
