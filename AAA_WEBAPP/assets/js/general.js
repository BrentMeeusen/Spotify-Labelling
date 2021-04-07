let _THEME = "dark";

function changeTheme() {

	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];
	_THEME = (_THEME === "dark" ? "light" : "dark");

	for(let i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], 
				getComputedStyle(document.documentElement).getPropertyValue("--" + _THEME + "--" + variables[i]));
	}

}



/**
 * When the website loads
 */
window.addEventListener("load", () => {

	// Load all the HTML/JavaScript
	const forms = document.getElementsByName("html-js-form");
	let index = 0;

	// For each form
	for(form of forms) {

		// When the corresponding submit button is clicked
		const submit = document.getElementsByName("html-js-form-submit")[index++];
		submit.addEventListener("click", () => {

			// Get all inputs and values
			let inputs = [];
			for(input of form.childNodes) {
				if(input.name) {
					if(input.name.includes("input")) {
						inputs.push({ name: input.name.replace("input ", ""), value: input.value });
					}
				}
			}

			// Create an XMLHttpResponse
			requestLabellingApiEndpoint(form.dataset.action, form.dataset.method, inputs);

		});
		
	}
	
});





/**
 * Requests an endpoint from the Spotify Labelling API
 * 
 * @param {string} action 
 * @param {string} method 
 * @param {array[string]} values 
 */
function requestLabellingApiEndpoint(action, method, values) {
	console.log("action", action);
	console.log("method", method);
	console.log("values", values);
}



