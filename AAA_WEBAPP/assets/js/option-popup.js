class OptionPopup {

	
	// Set variables
	static popup = document.getElementById("option-popup");





	/**
	 * Opens a track and its options
	 * 
	 * @param {object} track The track object
	 */
	static async openTrack(track) {

		// Add "delete track" row
		const deleteTrack = Api.createElement("div", { classList: "row" });
		deleteTrack.addEventListener("click", () => {
			Api.request("api/v1/tracks/" + track.data.id + "/delete", "DELETE");
		});
		deleteTrack.appendChild(Api.createIcon("delete"));
		deleteTrack.appendChild(Api.createElement("p", { innerHTML: "Remove song" }));
		this.popup.appendChild(deleteTrack);

		// Open popup
		this.popup.classList.add("open");
	

	}

}