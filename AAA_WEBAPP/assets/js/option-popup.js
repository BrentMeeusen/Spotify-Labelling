class OptionPopup {

	
	// Set variables
	static popup = document.getElementById("option-popup");





	/**
	 * Opens a track and its options
	 * 
	 * @param {string} spotifyID The track ID
	 */
	static async openTrack(spotifyID) {

		const track = await Api.sendRequest("api/v1/tracks/get/" + spotifyID, "GET");
		this.popup.classList.add("open");

	}

}