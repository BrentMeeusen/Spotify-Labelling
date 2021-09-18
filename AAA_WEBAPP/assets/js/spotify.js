class Collection {

	static tracks = [];
	static filters = {};
	static filtered = [];





	/**
	 * Resets the collection
	 */
	static reset() {
		this.tracks = [];
		Track.ID = 1;
	}





	/**
	 * Adds a track to the collection
	 * 
	 * @param {Track} track The track to add
	 */
	static add(track) {
		this.tracks.push(track);
		this.filtered = this.tracks;
	}





	/**
	 * Adds or changes a filter to the list of tracks
	 * 
	 * @param {string} key What to filter on (e.g., artist, track name, date added, ...)
	 * @param {string} value What value to filter on
	 * @returns the filtered array
	 */
	static addFilter(key, value) {

		switch(key) {
			case "artist":
				this.filters.artists = value.toLowerCase();
				break;
			case "label":
				this.filters.labels = value.toLowerCase();
				break;
			case "min-labels":
				this.filters.gteXLabels = value.toLowerCase();
				break;
			case "max-labels":
				this.filters.lteXLabels = value.toLowerCase();
				break;
			case "track":
				this.filters.tracks = value.toLowerCase();
				break;
				case "added-before":
					const addedBefore = (value ? new Date(value) : new Date(9999, 1, 1));
					this.filters.addedBefore = (addedBefore.getFullYear() < 1000 ? new Date(9999, 1, 1) : addedBefore.setHours(23, 59, 59, 999));
					break;
				case "added-after":
					const addedAfter = (value ? new Date(value) : new Date(1000, 1, 1));
					this.filters.addedAfter = (addedAfter.getFullYear() < 1000 ? new Date(1000, 1, 1) : addedAfter.setHours(0, 0, 0, 0));
					break;
				case "released-before":
					const releasedBefore = (value ? new Date(value) : new Date(9999, 1, 1));
					this.filters.releasedBefore = (releasedBefore.getFullYear() < 1000 ? new Date(9999, 1, 1) : releasedBefore.setHours(23, 59, 59, 999));
					break;
				case "released-after":
					const releasedAfter = (value ? new Date(value) : new Date(1000, 1, 1));
					this.filters.releasedAfter = (releasedAfter.getFullYear() < 1000 ? new Date(1000, 1, 1) : releasedAfter.setHours(0, 0, 0, 0));
					break;
		}

		return this.filter();

	}





	/**
	 * Actually filters the list
	 * 
	 * @returns an array of tracks that are filtered
	 */
	static filter() {

		let filtered = this.tracks;
		filtered = this.filters.artists ? filtered.filter(t => t.artists.some(a => a.name.toLowerCase().includes(this.filters.artists))) : filtered;		// Filter artists if filter is set
		filtered = this.filters.labels ? filtered.filter(t => t.labels.some(l => l.name.toLowerCase().includes(this.filters.labels))) : filtered;		// Filter labels if filter is set
		filtered = this.filters.gteXLabels ? filtered.filter(t => t.labels.length >= this.filters.gteXLabels) : filtered;		// Filter at least x labels if filter is set
		filtered = this.filters.lteXLabels ? filtered.filter(t => t.labels.length <= this.filters.lteXLabels) : filtered;		// Filter at most x labels if filter is set
		filtered = this.filters.tracks ? filtered.filter(t => t.name.toLowerCase().includes(this.filters.tracks)) : filtered;		// Filter tracks if filter is set
		filtered = this.filters.addedBefore ? filtered.filter(t => t.addedAt < this.filters.addedBefore) : filtered; // Filter added before if filter is set
		filtered = this.filters.addedAfter ? filtered.filter(t => t.addedAt > this.filters.addedAfter) : filtered; // Filter added after if filter is set
		filtered = this.filters.releasedBefore ? filtered.filter(t => t.releaseDate < this.filters.releasedBefore) : filtered; // Filter released before if filter is set
		filtered = this.filters.releasedAfter ? filtered.filter(t => t.releaseDate > this.filters.releasedAfter) : filtered; // Filter released after if filter is set
		this.filtered = filtered;

		return filtered;

	}

}










class Track {

	privateID = Track.ID++;
	id = "";
	artists = [];
	labels = [];
	name = "";
	addedAt;
	releaseDate;





	/**
	 * Constructor
	 * 
	 * @param {string} id The spotify ID of the track
	 * @param {string} name The name of the tracks
	 * @param {array} artists An array with the artist names
	 * @param {Date} addedAt The date at which the track was added to the application
	 * @param {Date} releaseDate The date at wich the track was released
	 * @param {array} labels An array with all the labels connected to the track
	 */
	constructor(id, name, artists, addedAt, releaseDate, labels = []) {

		this.id = id;
		this.name = name;
		this.artists = artists;
		this.addedAt = addedAt;
		this.releaseDate = releaseDate;
		this.labels = labels;

	}

}
Track.ID = 1;
