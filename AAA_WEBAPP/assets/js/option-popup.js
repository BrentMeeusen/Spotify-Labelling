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
		this.popup.innerHTML = "";
		console.log(track);

		const deleteTrack = Api.createElement("div", { classList: "row" });
		deleteTrack.addEventListener("click", () => {
			Api.request("api/v1/tracks/delete/" + track.data.id, "DELETE");
		});
		deleteTrack.appendChild(Api.createIcon("delete"));
		deleteTrack.appendChild(Api.createElement("p", { innerHTML: "Remove song" }));
		this.popup.appendChild(deleteTrack);
	

	}

}