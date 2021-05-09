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

			// Send the request
			const res = await Api.sendRequest(this.action, this.method, this.getValues());

			// Redirect if necessary
			if(res.code === 200 && this.redirect !== undefined) {
				// WARNING: HARDCODED LOCATION; CHANGE TO "/assets/..."
				window.location.href = "/Spotify Labelling/AAA_WEBAPP/assets/php/redirect.php?code=200&message=" + encodeURIComponent(res.message) + "&redirect=" + encodeURIComponent(this.redirect) + "&jwt=" + encodeURIComponent(res.jwt);
			}

			// Show the popup with the result of the API otherwise
			else {
				Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
			}

			// If the form should be cleared, clear all values
			if(this.form.dataset.clearFields == "true") {
				for(const field of this.inputs) {
					field.value = "";
				}
			}

			console.log(res, this.inputs);
			
		});

	}	// constructor()





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

}





HtmlJsForm.FORMS = [];





/**
 * Gets all forms
 */
HtmlJsForm.getForms = () => {

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
 * When the page loads, get all the custom forms
 */
window.addEventListener("load", () => {
	HtmlJsForm.getForms();
});
