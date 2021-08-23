class Collection {

	static tracks = [];





	/**
	 * Adds a track to the collection
	 * 
	 * @param {Track} track The track to add
	 */
	static add(track) {
		this.tracks.push(track);
	}





	/**
	 * Adds or changes a filter to the list of tracks
	 * 
	 * @param {string} key What to filter on (e.g., artist, track name, date added, ...)
	 * @param {string} equality Whether it's equal, not equal, gte, lte, ...
	 * @param {string} value What value to filter on
	 */
	static filter(key, equality, value) {

		switch(key) {
			case "artist":
				return this.tracks.filter(t => t.artists.some(a => a.name.toLowerCase().includes(value.toLowerCase())));
			case "label":
				return this.tracks.filter(t => t.labels.some(l => l.name.toLowerCase().includes(value.toLowerCase())));
			case "track":
				return this.tracks.filter(t => t.name.toLowerCase().includes(value.toLowerCase()));
		}

	}

}










class Track {

	artists = [];
	labels = [];
	name = "";
	addedAt;
	releaseDate;





	/**
	 * Constructor
	 * 
	 * @param {string} name The name of the tracks
	 * @param {array} artists An array with the artist names
	 * @param {Date} addedAt The date at which the track was added to the application
	 * @param {Date} releaseDate The date at wich the track was released
	 * @param {array} labels An array with all the labels connected to the track
	 */
	constructor(name, artists, addedAt, releaseDate, labels = []) {

		this.name = name;
		this.artists = artists;
		this.addedAt = addedAt;
		this.releaseDate = releaseDate;
		this.labels = labels;

	}

}