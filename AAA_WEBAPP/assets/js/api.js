class Api {


	/**
	 * Sends the request and shows the result
	 * 
	 * @param {string} url The URL of the request
	 * @param {string} method The method of the request
	 */
	static async request(url, method) {
		const res = await Api.sendRequest(url, method);
		console.log(res);
		Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
	}





	/**
	 * Formats a date into a given format
	 * 
	 * @param {string} format The format to make the date adhere to
	 * @param {Date} date The date to format
	 * @return {string} The formatted date
	 */
	static formatDate(format, date = Date.now()) {

		let result = format.replace("d", this.append0(date.getDate()));
		result = result.replace("m", this.append0(date.getMonth() + 1));
		result = result.replace("Y", this.append0(date.getFullYear()));
		result = result.replace("H", this.append0(date.getHours()));
		result = result.replace("i", this.append0(date.getMinutes()));
		result = result.replace("s", this.append0(date.getSeconds()));
		return result;

	}





	/**
	 * Appends a 0 if needed
	 * 
	 * @param {any} value The value to append a 0 to
	 * @return {string} The value with a 0 if needed
	 */
	static append0(value) {
		return (value.toString().length < 2 ? "0" + value : value);
	}

}





Api.isSending = false;
/**
 * Sends a request to the Spotify Labelling API
 * 
 * @param {string} location The action of the request
 * @param {string} method The method of the request 
 * @param {object} values The values in an object so it will be received as an associative array
 * @returns {object} The return object
 */
Api.sendRequest = async (location, method, values = {}) => {

	// If we're already sending, return false
	if(Api.isSending === true) { return false; }
	Api.isSending = true;

	// Send a request and return the result
	const response = await fetch(encodeURI(VALUES.api + location), {
		method,
		headers: {
			"Content-Type": "application/json",
			"Authorization": (Api.TOKEN && Api.TOKEN.jwt ? "Bearer " + Api.TOKEN.jwt : "")
		},
		body: ((values && method !== "GET") ? JSON.stringify(values) : null)
	});

	// Get the response
	const res = await response.json();
	Api.isSending = false;

	// Show error popup for rate limiting
	if(res.code === 429) {
		Popup.show(res.error, "error");
	}



	// If the token is expired, redirect to login screen with error
	if(res.error && res.error.includes("expired")) {
		window.location.href = VALUES.assets + "php/redirect.php?redirect=&code=400&message=Your%20session%20expired.%20Please%20login%20again%20to%20continue.";
	}

	// Set the token if it's provided
	if(res.jwt) {
		Api.TOKEN = new JWT(res.jwt);
		document.cookie = "jwt=" + res.jwt + "; Expires=" + Date.now() + 3600 + "; Path=/";
	}

	// Return the result
	return res;

}





/**
 * Shows the tracks
 * 
 * @param {array} tracks The tracks to show
 */
Api.showTracks = async (tracks) => {

	console.log(tracks);

	const output = document.getElementById("tracks");
	output.innerHTML = "";

	// Add header
	const header = Api.createElement("tr");
	header.appendChild(Api.createElement("th", { innerHTML: "Song" }));
	header.appendChild(Api.createElement("th", { innerHTML: "Released at" }));
	header.appendChild(Api.createElement("th", { innerHTML: "Added at" }));
	output.appendChild(header);

	// For each track
	for(const track of tracks) {

		// Create row, add name, releaseDate, addedAt
		const row = Api.createElement("tr");
		row.appendChild(Api.createElement("td", { innerHTML: track.name }));
		row.appendChild(Api.createElement("td", { innerHTML: Api.formatDate("d-m-Y", new Date(track.releaseDate)) }));
		row.appendChild(Api.createElement("td", { innerHTML: Api.formatDate("d-m-Y H:i:s", new Date(track.addedAt)) }));
		output.appendChild(row);

	}

}





/**
 * Shows the playlists for import
 * 
 * @param {array} playlists The playlists which can be imported
 */
Api.showPlaylistsForImport = async (playlists) => {

	console.log(playlists);

	const output = document.getElementById("playlists");
	output.innerHTML = "";

	for(const list of playlists) {

		// Create row, add name, number of tracks, import button
		const row = Api.createElement("tr");
		row.appendChild(Api.createElement("td", { innerHTML: list.name }));
		row.appendChild(Api.createElement("td", { innerHTML: list.numTracks + " song" + (list.numTracks === 1 ? "" : "s") }));

		// If the number of tracks is more than 2000, disable button
		if(list.numTracks > 2000) {
			row.appendChild(Api.createIcon("import", async() => { Popup.show("Cannot import playlists with more than 2000 songs.", "error"); }));
		}
		else {
			row.appendChild(Api.createIcon("import", async () => { Api.request("api/v1/spotify/import/" + list.spotifyID, "POST"); }));
		}

		// Output the row
		output.appendChild(row);

	}

}





/**
 * Shows the labels
 */
Api.showLabels = async () => {

	// Clears the output and gets the data
	const output = document.getElementById("labels");
	output.innerHTML = "";

	const result = await Api.sendRequest("api/v1/labels/all/" + Api.TOKEN.getPayload().user.id, "GET");


	// If no labels are found, return
	if(result.data === undefined || result.data === null) {
		return;
	}

	// For every row
	for(const row of result.data) {
		
		// Create the row
		const tr = document.createElement("tr");
		tr.appendChild(Api.createElement("td", { innerHTML: row.name }));
		tr.appendChild(Api.createElement("td", { innerHTML: "xx songs" }));
		tr.appendChild(Api.createElement("td", { innerHTML: (row.isPublic ? "Public" : "Private") }));



		// If the label is not ours, do not show the buttons
		if(row.creator !== Api.TOKEN.getPayload().user.id) {
			output.appendChild(tr);
			continue;
		}



		// Create edit button
		const edit = Api.createElement("td");
		edit.appendChild(Api.createIcon("edit", () => {

			const popup = new BigPopup("Edit Label", "api/v1/labels/" + row.publicID + "/update", "POST", "edit-label-form");
			popup.add("input", "Name", { value: row.name });
			popup.show("EDIT");
			HtmlJsForm.findById("edit-label-form").addCallback(() => { Api.showLabels(); });

		}));
		tr.appendChild(edit);

		// Create remove button
		const remove = Api.createElement("td");
		remove.appendChild(Api.createIcon("delete", () => {

			const popup = new BigPopup("Remove Label", "api/v1/labels/" + row.publicID + "/delete", "DELETE", "remove-label-form");
			popup.add("p", "text", { innerHTML: "Are you sure you want to remove \"" + row.name + "\"? All songs affiliated with this label will lose their association, and it cannot be undone." });
			popup.show("REMOVE");
			HtmlJsForm.findById("remove-label-form").addCallback(() => { Api.showLabels(); });

		}));
		tr.appendChild(remove);

		// If the user can set it to public/private
		const maySetPublic = Api.TOKEN.getPayload().rights.label.public;
		if(maySetPublic == true) {

			// If it's public, create private button
			if(row.isPublic) {

				const makePrivate = Api.createElement("td");

				makePrivate.appendChild(Api.createIcon("eye-crossed", async () => {
					const res = await Api.sendRequest("api/v1/labels/" + row.publicID + "/private", "POST");
					Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
					Api.showLabels();
				}));

				tr.appendChild(makePrivate);

			}

			// Else, create make public button
			else {

				const makePublic = Api.createElement("td");

				makePublic.appendChild(Api.createIcon("eye", async () => {
					const res = await Api.sendRequest("api/v1/labels/" + row.publicID + "/public", "POST");
					Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
					Api.showLabels();
				}));
				
				tr.appendChild(makePublic);

			}

		}

		output.appendChild(tr);

	}

}





/**
 * Creates an element
 * 
 * @param {string} el Element type
 * @param {object} options The values to add to the element
 * @returns {HTMLElement} The element that was created
 */
 Api.createElement = (el, options = {}) => {

	const elem = document.createElement(el);
	for(const [key, value] of Object.entries(options)) {
		elem[key] = value;
	}
	return elem;

}





/**
 * Creates a button with an icon inside
 * 
 * @param {string} icon The icon name
 * @param {Function} event The click event
 * @returns {HTMLElement} The button with icon
 */
Api.createIcon = (icon, event) => {

	// Create button
	const button = Api.createElement("button");
	button.classList.add("icon");
	button.addEventListener("click", () => {
		event();
	});

	// Add icon to the button
	const iconElement = Api.createElement("img", { src: VALUES.assets + "icons/" + icon + ".png" });
	button.appendChild(iconElement);
	return button;

}