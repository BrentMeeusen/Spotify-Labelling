class OptionPopup {

	
	// Set variables
	static popup = document.getElementById("option-popup");





	/**
	 * Closes the popup
	 */
	static close() {
		this.popup.style.bottom = -(this.popup.clientHeight + 32) + "px";
	}





	/**
	 * Opens a track and its options
	 * 
	 * @param {object} track The track object
	 */
	static async openTrack(track) {

		// Open popup
		this.popup.innerHTML = "";
		this.popup.style.bottom = 0;
		this.popup.style.height ="auto";

		// Add "delete track" row
		const deleteTrack = Api.createElement("div", { classList: "row" });
		deleteTrack.addEventListener("click", async () => {
			this.close();
			await Api.request("api/v1/tracks/" + track.id + "/delete", "DELETE");
			const tracks = await Api.sendRequest("api/v1/tracks/get", "GET");
			Api.showTracks(tracks.data);
		});
		deleteTrack.appendChild(Api.createIcon("delete"));
		deleteTrack.appendChild(Api.createElement("p", { innerHTML: "Remove song" }));
		this.popup.appendChild(deleteTrack);

		// Add "close popup" row
		const closePopup = Api.createElement("div", { classList: "row" });
		closePopup.addEventListener("click", () => {
			this.close();
		});
		closePopup.appendChild(Api.createIcon("menu/menu-close"));
		closePopup.appendChild(Api.createElement("p", { innerHTML: "Close" }));
		this.popup.appendChild(closePopup);

		// Set height
		this.popup.style.height = (this.popup.clientHeight + 16) + "px";

	}

}