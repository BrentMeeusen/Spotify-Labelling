class OptionPopup {


	/**
	 * Opens a track and its options
	 * 
	 * @param {string} spotifyID The track ID
	 */
	static async openTrack(spotifyID) {

		const track = await Api.sendRequest("api/v1/tracks/get", "GET", { spotifyID });
		console.log(track);

	}

}