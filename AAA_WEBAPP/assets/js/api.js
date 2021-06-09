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
 * 
 * @param {object[]} result 
 */
Api.showLabels = (result) => {

	// Sets output element
	const output = document.getElementById("labels");

	console.log(result);

	// For every row
	for(const row of result) {
		
		// Create the row
		const tr = document.createElement("tr");
		tr.appendChild(Api.createElement("td", { innerHTML: row.name }));
		tr.appendChild(Api.createElement("td", { innerHTML: "xx songs" }));
		tr.appendChild(Api.createElement("td", { innerHTML: (row.isPublic ? "Public" : "Private") }));
		tr.appendChild(Api.createIcon("edit", () => { console.log("Hey there, sexy ;)"); }));

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