class HtmlJsForm {


	constructor(form, inputs, submit) {

		console.log("HTML JS FORM", form, inputs, submit);

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

		// Get the submit button...
		const submitButton = document.getElementsByName("html-js-form-submit")[index++];
		let inputs = {};

		// ...get all inputs...
		for(const input of form.children) {
			if(input.name && input.name.includes("input")) {
				inputs[input.name.split(" ")[1]] = input.value;
			}
		}

		// ...and create the form
		HtmlJsForm.FORMS.push(new HtmlJsForm(form, inputs, submitButton));

	}

}





/**
 * When the page loads, get all the custom forms
 */
window.addEventListener("load", () => {
	HtmlJsForm.getForms();
});




/*
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
			const redirect = form.dataset.redirect;
			
			if(res.code !== 200 && redirect !== undefined) {
				window.location.href = "../assets/php/redirect.php?code=200&message=" + encodeURIComponent(res.message) + "&redirect=" + encodeURIComponent(redirect);
			}
			else {
				Popup.show(res.message || res.error, (res.code >= 200 && res.code <= 299 ? "success" : "error"), 5000);
			}
			
			
			console.log(res);

		});
		
	}

*/