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
	public function merge(string $field) : ?ICollection {

		$newTracks = [];

		// For each element
		for($i = 0; $i < count($this->data);) {

			// If it's not a track, return NULL
			$track = $this->data[$i];
			if(!($track instanceof ITrack)) { return NULL; }

			// Get all fields from this track
			$offset = 0;
			$fields = $track->{$field}->data;

			while(($i + ++$offset) < count($this->data)) {
				$next = $this->data[$i + $offset];
				if($track->equalsExceptArtist($next)) {
					array_push($fields, $next->{$field}->data[0]);
				} else { break; }
			}

			// Save the track
			$newTrack = $track;
			if($field === "artists") { $newTrack->setArtists(new ICollection($fields)); }
			else if($fields === "labels") { $newTrack->setLabels(new ICollection($fields)); }
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





	/**
	 * Creates a collection of labels
	 * 
	 * @param		array		The labels
	 * @return		ICollection	The collection
	 * @return		null		If something went wrong
	 */
	public static function createLabelCollection(array $data) : ?ICollection {

		$labels = [];
		foreach($data as $row) {
			if(!($row instanceof Label) && $row !== NULL) {
				return NULL;
			}
			array_push($labels, $row);
		}
		return new ICollection($labels);

	}

}

?>
