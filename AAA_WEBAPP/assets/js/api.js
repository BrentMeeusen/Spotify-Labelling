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
Api.sendRequest = (location, method, values) => {

	// Send a request and return the result
	const response = await fetch(encodeURI("http://localhost/Spotify Labelling/AAA_API/" + location), {
		method,
		headers: {
			"Content-Type": "application/json",
			"Authorization": (Api.TOKEN ? "Bearer " + Api.TOKEN : "")
		},
		body: ((values && method !== "GET") ? JSON.stringify(values) : null)
	});

	// Get the response
	const res = await response.json();

	// Set the token if it's provided
	if(res.jwt) { Api.TOKEN = res.jwt; }

}