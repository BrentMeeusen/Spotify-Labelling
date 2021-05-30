class BigPopup {


	/**
	 * Constructor
	 * 
	 * @param {string} title The title of the popup
	 * @param {Array<HTMLElement>} elements The elements to add
	 * @param {string} action The resulting action of a successful submission
	 * @param {string} method The method of that action
	 */
	constructor(title, elements, action, method) {

		this.title = title;
		this.elements = elements;

		this.action = action;
		this.method = method;

		this.popup = document.getElementById("popup-big");

	}





	/**
	 * Creates an element
	 * 
	 * @param {string} el Element type
	 * @param {object} options The values to add to the element
	 * @returns {HTMLElement} The element that was created
	 */
	createElement(el, options = {}) {

		const elem = document.createElement(el);
		for(const [key, value] of Object.entries(options)) {
			elem[key] = value;
		}
		return elem;

	}





	/**
	 * Hides the popup again
	 */
	hide() {

		this.popup.classList.remove("opened");
		setTimeout(() => {
			this.popup.style.display = "none";
		}, 200);

	}





	/**
	 * Shows the popup
	 * 
	 * @param {string} success The text on the success button
	 */
	show(success) {

		// Clear everything first
		this.popup.innerHTML = "";

		// Add title
		this.popup.appendChild(this.createElement("h2", { innerHTML: this.title }));

		// Create form
		const form = this.createElement("div");
		form.classList.add("form");
		form.dataset.action = this.action;
		form.dataset.method = this.method;
		form.dataset.clearFields = true;
		form.setAttribute("name", "html-js-form");


		// Add elements
		form.appendChild(this.createElement("p", { innerHTML: "Test" }));


		// Add buttons
		const buttonCancel = this.createElement("button", { innerHTML: "CANCEL", type: "submit", value: "submit", classList: "border--red small" });
		form.appendChild(buttonCancel);

		const buttonSave = this.createElement("button", { innerHTML: success, type: "submit", value: "submit", classList: "small" });
		buttonSave.setAttribute("name", "html-js-form-submit");
		form.appendChild(buttonSave);




		this.popup.appendChild(form);

		// Create the forms
		HtmlJsForm.getForms();
		
		// Open popup
		this.popup.style.display = "block";
		setTimeout(() => {
			this.popup.classList.add("opened");
		}, 1);

	}


}

const bp = new BigPopup("abc", [], "api/v1/labels/create", "POST");
bp.show("TEST");