class Collection {

}










class Track {

	artists = [];
	labels = [];
	name = "";
	dateAdded;
	releaseDate;





	/**
	 * Constructor
	 * 
	 * @param {string} name The name of the tracks
	 * @param {array} artists An array with the artist names
	 * @param {Date} dateAdded The date at which the track was added to the application
	 * @param {Date} releaseDate The date at wich the track was released
	 * @param {array} labels An array with all the labels connected to the track
	 */
	constructor(name, artists, dateAdded, releaseDate, labels = []) {

		this.name = name;
		this.artists = artists;
		this.dateAdded = dateAdded;
		this.releaseDate = releaseDate;
		this.labels = labels;

	}

}