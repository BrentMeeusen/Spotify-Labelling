class Api {


	// Set variables
	static isSending = false;





	/**
	 * Sends a request to the Spotify Labelling API
	 * 
	 * @param {string} location The action of the request
	 * @param {string} method The method of the request 
	 * @param {object} values The values in an object so it will be received as an associative array
	 * @returns {object} The return object
	 */
	static async sendRequest(location, method, values) {

		// If we're already sending, return an object that can be read by the Popup
		if(Api.isSending === true) { return { code: 1, error: "There is still a request that is not processed, please wait..." }; }
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
		Api.isSending = false;
		const res = await response.json();

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





	/**
	 * Creates an element
	 * 
	 * @param {string} el Element type
	 * @param {object} options The values to add to the element
	 * @returns {HTMLElement} The element that was created
	 */
	 static createElement (el, options = {}) {

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
	static createIcon (icon, event = () => {}) {

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

}





Api.get = {

	/**
	 * Gets the tracks from the database
	 * 
	 * @returns {array} The tracks
	 */
	tracks: async () => {
		const res = await Api.sendRequest("api/v1/tracks/get/", "GET");

		Collection.reset();
		for(const track of res.data) {
			const t = new Track(track.id, track.name, track.artists, new Date(track.addedAt), new Date(track.releaseDate), track.labels);
			Collection.add(t);
		}

		return (res.data ? Collection.tracks : res);
	},





	/**
	 * Gets the labels from the database
	 * 
	 * @returns {array} The labels
	 */
	labels: async () => {
		const res = await Api.sendRequest("api/v1/labels/" + Api.TOKEN.getPayload().user.id, "GET");
		return (res.data ? res.data : res);
	},





	playlists: {

		/**
		 * Gets the playlists to import
		 * 
		 * @returns {array} The playlists that are importable
		 */
		import: async () => {
			const res = await Api.sendRequest("api/v1/spotify/playlists", "GET");
			return (res.data ? res.data : res);
		},



		/**
		 * Gets the labels from the database
		 * 
		 * @returns {array} The labels
		 */
		mine: async () => {
			const res = await Api.sendRequest("api/v1/playlists/", "GET");
			return (res.data ? res.data : res);
		},

	}

}





Api.show = {

	/**
	 * Displays the tracks on the page
	 * 
	 * @param {array} tracks The tracks to display
	 */
	tracks: (tracks) => {

		const output = document.getElementById("tracks");
		output.innerHTML = "";

		// For each track
		for(const track of tracks) {

			// Create row and text container
			const row = Api.createElement("div", { classList: "row" });
			const textContainer = Api.createElement("div", { classList: "text" });

			// Add track title
			textContainer.appendChild(Api.createElement("p", { innerHTML: track.privateID + ". " + track.name, classList: "title" }));

			// Add artists
			const artists = [];
			for(const a of (track.artists.data ? track.artists.data : track.artists)) { artists.push(a.name); }
			textContainer.appendChild(Api.createElement("p", { innerHTML: artists.join(", "), classList: "small" }));

			// Add date added 
			textContainer.appendChild(Api.createElement("p", { innerHTML: Api.formatDate("d-m-Y", new Date(track.addedAt)), classList: "small" }));

			// Add text container and "more" button to row
			row.appendChild(textContainer);
			row.appendChild(Api.createIcon("more_horiz", () => {
				OptionPopup.openTrack(track);
			}));

			// Append row
			output.appendChild(row);

		}

	},	// Api.show.tracks





	/**
	 * Displays the labels
	 * 
	 * @param {array} labels The labels array
	 */
	labels: (labels) => {

		// Clears the output and gets the data
		const output = document.getElementById("labels");
		output.innerHTML = "";

		// For every row
		for(const label of labels) {

			// Create the row and text container
			const row = Api.createElement("div", { classList: "row" });
			const textContainer = Api.createElement("div", { classList: "text" });

			textContainer.appendChild(Api.createElement("p", { innerHTML: label.name }));
			textContainer.appendChild(Api.createElement("p", { innerHTML: label.numTracks + " song" + (label.numTracks !== 1 ? "s" : ""), classList: "right" }));

			row.appendChild(textContainer);

			// Create edit button
			row.appendChild(Api.createIcon("edit", () => {

				const popup = new BigPopup("Edit Label", "api/v1/labels/" + label.publicID + "/update", "POST", "edit-label-form");
				popup.add("input", "Name", { value: label.name });
				popup.show("EDIT");
				HtmlJsForm.findById("edit-label-form").addCallback(async () => { Api.show.labels(await Api.get.labels()); });

			}));

			// Create remove button
			row.appendChild(Api.createIcon("delete", () => {

				const popup = new BigPopup("Remove Label", "api/v1/labels/" + label.publicID + "/delete", "DELETE", "remove-label-form");
				popup.add("p", "text", { innerHTML: "Are you sure you want to remove \"" + label.name + "\"? All songs affiliated with this label will lose their association, and it cannot be undone." });
				popup.show("REMOVE");
				HtmlJsForm.findById("remove-label-form").addCallback(async () => { Api.show.labels(await Api.get.labels()); });

			}));

			// append row to HTML
			output.appendChild(row);

		}

	},	// Api.show.labels





	playlists: {

		/**
		 * The playlists that are importable
		 * 
		 * @param {array} playlists The playlists to show
		 */
		import: (playlists) => {

			const output = document.getElementById("playlists");
			output.innerHTML = "";
		
			for(const list of playlists) {

				// Create row, add name, number of tracks, import button
				const row = Api.createElement("div", { classList: "row" });

				const textContainer = Api.createElement("div", { classList: "text" });
				textContainer.appendChild(Api.createElement("p", { innerHTML: list.name, classList: "max65" }));
				textContainer.appendChild(Api.createElement("p", { innerHTML: list.numTracks + " song" + (list.numTracks === 1 ? "" : "s"), classList: "right" }));
				row.appendChild(textContainer);

				// If the number of tracks is more than 2000, disable button
				if(list.numTracks > 2000) {
					row.appendChild(Api.createIcon("import", async() => { Popup.show("Cannot import playlists with more than 2000 songs.", "error"); }));
				}
				else {
					row.appendChild(Api.createIcon("import", async () => {
						const res = await Api.sendRequest("api/v1/spotify/import/" + (list.spotifyID || ""), "POST");
						Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
					}));
				}

				output.appendChild(row);

			}	// for list of playlists

		},	// Api.show.playlists.import



		/**
		 * Displays the playlists
		 * 
		 * @param {array} playlists The playlists array
		 */
		mine: (playlists) => {
	
			// Clears the output and gets the data
			const output = document.getElementById("playlists");
			output.innerHTML = "";
	
			// For every row
			for(const playlist of playlists) {
	
				// Create the row and text container
				const row = Api.createElement("div", { classList: "row" });
				const textContainer = Api.createElement("div", { classList: "text" });
				textContainer.appendChild(Api.createElement("p", { innerHTML: playlist.name }));
	
				row.appendChild(textContainer);
	
				// Create edit button
				row.appendChild(Api.createIcon("edit", () => {
	
					const popup = new BigPopup("Edit Playlist", "api/v1/playlists/" + playlist.publicID + "/update", "POST", "edit-playlist-form");
					popup.add("input", "Name", { value: playlist.name });
					popup.show("EDIT");
					HtmlJsForm.findById("edit-playlist-form").addCallback(async () => { Api.show.playlists.mine(await Api.get.playlists.mine()); });
	
				}));
	
				// Create remove button
				row.appendChild(Api.createIcon("delete", () => {
	
					const popup = new BigPopup("Remove Playlist", "api/v1/playlists/" + playlist.publicID + "/delete", "DELETE", "remove-playlist-form");
					popup.add("p", "text", { innerHTML: "Are you sure you want to remove \"" + playlist.name + "\"? All songs affiliated with this playlist will lose their association, and it cannot be undone." });
					popup.show("REMOVE");
					HtmlJsForm.findById("remove-playlist-form").addCallback(async () => { Api.show.playlists.mine(await Api.get.playlists.mine()); });
	
				}));
	
				// append row to HTML
				output.appendChild(row);
	
			}

		}	// Api.show.playlists.mine

	},	// Api.show.playlists





	/**
	 * Shows the email address of the user
	 * 
	 * @param {string} email The email address
	 */
	spotifyEmail(email) {
		document.getElementById("spotify-email").innerHTML = email;
	}

}	// Api.show