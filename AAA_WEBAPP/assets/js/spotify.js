class Collection {

	static tracks = [];
	static filters = {};





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
	 * @param {string} value What value to filter on
	 */
	static filter(key, value) {

		switch(key) {
			case "artist":
				this.filters.artists = value.toLowerCase();
				break;
			case "label":
				this.filters.labels = value.toLowerCase();
				break;
			case "x-labels":
				this.filters.gteXLabels = value.toLowerCase();
				break;
			case "track":
				this.filters.tracks = value.toLowerCase();
				break;
		}

		let filtered = this.tracks;
		filtered = this.filters.artists ? filtered.filter(t => t.artists.some(a => a.name.toLowerCase().includes(this.filters.artists))) : filtered;		// Filter artists if filter is set
		filtered = this.filters.labels ? filtered.filter(t => t.labels.some(l => l.name.toLowerCase().includes(this.filters.labels))) : filtered;		// Filter labels if filter is set
		filtered = this.filters.gteXLabels ? filtered.filter(t => t.labels.length >= this.filters.gteXLabels) : filtered;		// Filter at least x labels if filter is set
		filtered = this.filters.tracks ? filtered.filter(t => t.name.toLowerCase().includes(this.filters.tracks)) : filtered;		// Filter tracks if filter is set
		return filtered;

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