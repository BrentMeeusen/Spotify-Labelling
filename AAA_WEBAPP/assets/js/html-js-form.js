class HtmlJsForm {

	/**
	 * HtmlJsForm constructor
	 * 
	 * @param {HTMLElement} form The form element
	 * @param {HTMLElement} submit The submit button
	 */
	constructor(form, submit) {

		// Set parameters
		this.form = form;
		this.inputs = form.children;
		this.submit = submit;

		// Set parameters based on HTMLElements
		this.action = this.form.dataset.action;
		this.method = this.form.dataset.method;
		this.redirect = this.form.dataset.redirect;

		// Set the submit button action
		this.submit.addEventListener("click", async () => {
			this.sendForm();
		});

	}	// constructor()





	/**
	 * Adds a callback method to run after the response is received
	 * 
	 * @param {Function} callback The method to run
	 */
	addCallback(callback) {
		this.callback = callback;
	}





	/**
	 * Sends the form using the action, method, and values provided
	 */
	async sendForm() {

		// Disable the button so the user can't send the same request over and over
		this.submit.disabled = true;

		// Send the request
		const res = await Api.sendRequest(this.action, this.method, this.getValues());
		(this.callback ? this.callback() : true);

		// Redirect if necessary
		if(res.code === 200 && this.redirect !== undefined) {
			window.location.href = VALUES.assets + "php/redirect.php?code=200&message=" + encodeURIComponent(res.message) + "&redirect=" + encodeURIComponent(this.redirect) + "&jwt=" + encodeURIComponent(res.jwt);
		}

		// Show the popup with the result of the API otherwise
		else {
			Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
		}

		// Enable submit button again
		this.submit.disabled = false;

		// If the form should be cleared, clear all values
		if(this.form.dataset.clearFields == "true") {
			for(const field of this.inputs) {
				field.value = "";
			}
		}

		// If there's an autofill method set, use it
		if(this.autofill) {
			this.autofill(res);
		}

		console.log(res, this.inputs);
		
	}





	/**
	 * Gets the values from the form children
	 * 
	 * @returns An object with the input names as keys, and the values as the values (that's not too surprising, is it?)
	 */
	getValues() {

		let values = {};

		for(const input of this.inputs) {
			if(input.name && input.name.includes("input")) {
				values[input.name.split(" ")[1]] = input.value;
			}
		}

		return values;

	}





	/**
	 * Fills the inputs with the values given
	 * 
	 * @param {object} values The values to fill in
	 * @param {array} path The path as to what part of the result should be filled in 
	 */
	fillValues(values, ...path) {

		// For every entry
		for(const [key, value] of Object.entries(values)) {

			// For every input
			for(const input of this.inputs) {

				// If the key is the input name
				const names = input.name.split(" ");
				if(names[1] && names[1].toLowerCase() === key.toLowerCase()) {
					input.value = value;		// Set the value
				}

			}	// For every input

		}	// For every value


		// Redo this every time a request is found
		this.autofill = (res) => {

			// Get the field
			let obj = res;
			for(const p of path) {
				if(!obj[p]) { break; }
				obj = obj[p];
			}
			
			// Fill the values
			this.fillValues(obj, path);

		}


	}	// fillValues()

}





HtmlJsForm.FORMS = [];





/**
 * Gets all forms
 */
HtmlJsForm.getForms = () => {

	// Clear array
	HtmlJsForm.FORMS = [];

	// Get forms
	let forms = document.getElementsByName("html-js-form");
	let index = 0;

	// For all forms
	for(const form of forms) {

		// Get the submit button and create the form
		const submitButton = document.getElementsByName("html-js-form-submit")[index++];
		HtmlJsForm.FORMS.push(new HtmlJsForm(form, submitButton));

	}

}





/**
 * Finds the form by ID if it exists
 * 
 * @param {string} id The data-id of the form to find
 * @returns HtmlJsForm object if found, null if none found
 */
HtmlJsForm.findById = (id) => {

	for(const form of HtmlJsForm.FORMS) {
		if(form.form.dataset.id === id) {
			return form;
		}
	}

	return null;

}





/**
 * When the page loads, get all the custom forms
 */
window.addEventListener("load", () => {
	HtmlJsForm.getForms();
});
