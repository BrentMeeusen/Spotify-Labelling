class Api {

}





/**
 * Sends a request to the Spotify Labelling API
 * 
 * @param {string} location The action of the request
 * @param {string} method The method of the request 
 * @param {object} values The values in an object so it will be received as an associative array
 * @returns {object} The return object
 */
Api.sendRequest = async (location, method, values = {}) => {

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

	// Set the token if it's provided
	if(res.jwt) {
		Api.TOKEN = new JWT(res.jwt);
		document.cookie = "jwt=" + res.jwt + "; Expires=" + Date.now() + 3600 + "; Path=/";
	}

	// Return the result
	return res;

}





/**
 * Shows the labels
 */
Api.showLabels = async () => {

	// Sets output element and get the data
	const output = document.getElementById("labels");
	const result = await Api.sendRequest("api/v1/labels/all/" + Api.TOKEN.getPayload().user.id, "GET");

	// Clear the output
	output.innerHTML = "";

	// For every row
	for(const row of result.data) {
		
		// Create the row
		const tr = document.createElement("tr");
		tr.appendChild(Api.createElement("td", { innerHTML: row.name }));
		tr.appendChild(Api.createElement("td", { innerHTML: "xx songs" }));
		tr.appendChild(Api.createElement("td", { innerHTML: (row.isPublic ? "Public" : "Private") }));

		// Create edit button
		const edit = Api.createElement("td");
		edit.appendChild(Api.createIcon("edit", () => {

			const popup = new BigPopup("Edit Label", "api/v1/labels/edit/" + row.publicID, "POST", "edit-label-form");
			popup.add("input", "Name", { value: row.name });
			popup.show("EDIT");
			HtmlJsForm.findById("edit-label-form").addCallback(() => { Api.showLabels(); });

		}));
		tr.appendChild(edit);

		// Create remove button
		const remove = Api.createElement("td");
		remove.appendChild(Api.createIcon("delete", () => {

			const popup = new BigPopup("Remove Label", "api/v1/labels/remove/" + row.publicID, "DELETE", "remove-label-form");
			popup.add("p", "text", { innerHTML: "Are you sure you want to remove \"" + row.name + "\"? All songs affiliated with this label will lose their association, and it cannot be undone." });
			popup.show("REMOVE");
			HtmlJsForm.findById("remove-label-form").addCallback(() => { Api.showLabels(); });
			
		}));
		tr.appendChild(remove);

		// If the user can set it to public/private
		if(Api.TOKEN.getPayload().rights.label.public === true) {

			// If it's public, create private button
			if(row.isPublic) {
				const makePrivate = Api.createElement("td");
				makePrivate.appendChild(Api.createIcon("eye-crossed", () => {
					Api.sendRequest("api/v1/labels/private/" + row.publicID, "POST");
				}));
				tr.appendChild(makePrivate);
			}

			// Else, create make public button
			else {
				const makePublic = Api.createElement("td");
				makePublic.appendChild(Api.createIcon("eye", () => {
					Api.sendRequest("api/v1/labels/public/" + row.publicID, "POST");
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