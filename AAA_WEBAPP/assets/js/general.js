var IMAGES = [];
var TOKEN;

/**
 * When the website loads
 */
window.addEventListener("load", async () => {


	// REFACTORING ====


	const lazy = new LazyLoading("lazy-image");


	// END REFACTORING ==== 



	// HTML JAVASCRIPT FORMS ===================================================================
	// Load all the HTML/JavaScript
	const forms = document.getElementsByName("html-js-form");
	let index = 0;

	// For each form
	for(form of forms) {

		// When the corresponding submit button is clicked
		const submit = document.getElementsByName("html-js-form-submit")[index++];
		submit.addEventListener("click", async () => {

			// Get all inputs and values
			let inputs = {};
			for(input of form.childNodes) {
				if(input.name) {
					if(input.name.includes("input")) {
						inputs[input.name.split(" ")[1]] = input.value;
					}
				}
			}

			// Create an XMLHttpResponse
			const res = await Api.sendRequest(form.dataset.action, form.dataset.method, inputs);
			console.log(res);

		});
		
	}


	// DARK THEME ===================================================================
	// Set the variables to update
	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];
	const theme = "dark"; // Will be set as a cookie!

	// Loop over all variables and change their values
	for(let i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], getComputedStyle(document.documentElement).getPropertyValue("--" + theme + "--" + variables[i]));
	}
	
});



