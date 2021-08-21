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

		// Add "add label" row
		const addLabel = Api.createElement("div", { classList: "row" });
		addLabel.addEventListener("click", async () => {

			this.close();
			const popup = new BigPopup("Choose a label", "api/v1/tracks/add-label/[TRACK_ID]/[LABEL_IDS]/", "POST");
			const labels = await Api.get.labels();

			let i = 1;
			for(const l of labels) {
				const el = Api.createElement("div", { innerHTML: l.name, classList: "add-label", value: l.publicID });
				el.setAttribute("name", "input");
				el.dataset.selected = "false";
				el.dataset.item = "label-" + i++;
				el.addEventListener("click", () => { el.dataset.selected = (el.dataset.selected === "true" ? "false" : "true"); });
				popup.addElement(el);
			}
			popup.show("Add");


			// const res = await Api.sendRequest("api/v1/tracks/" + track.id + "/labels/" + "LABEL_ID");
			// Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);

			// Api.show.tracks(await Api.get.tracks());

		});
		addLabel.appendChild(Api.createIcon("add"));
		addLabel.appendChild(Api.createElement("p", { innerHTML: "Add labels" }));
		this.popup.appendChild(addLabel);

		// Add "delete track" row
		const deleteTrack = Api.createElement("div", { classList: "row" });
		deleteTrack.addEventListener("click", async () => {

			this.close();
			const res = await Api.sendRequest("api/v1/tracks/" + track.id + "/delete", "DELETE");
			Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);

			Api.show.tracks(await Api.get.tracks());

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