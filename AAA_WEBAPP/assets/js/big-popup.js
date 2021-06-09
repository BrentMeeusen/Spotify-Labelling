class BigPopup {


	/**
	 * Constructor
	 * 
	 * @param {string} title The title of the popup
	 * @param {string} action The resulting action of a successful submission
	 * @param {string} method The method of that action
	 */
	constructor(title, action, method) {

		this.title = title;
		this.action = action;
		this.method = method;

		this.popup = document.getElementById("popup-big");
		this.elements = [];

	}





	/**
 	 * Adds an element to the popup
	 * 
	 * @param {string} element The element type
	 * @param {string} name The name of the input field
	 * @param {object} options The options 
	 */
	add(element, name, options) {

		const el = BigPopup.createElement(element, options);
		el.setAttribute("name", element + " " + name);
		this.elements.push(el);

	}





	/**
	 * Hides the popup again
	 */
	hide() {

		// Close popup
		this.popup.classList.remove("opened");
		setTimeout(() => {
			this.popup.style.display = "none";
		}, 200);

		// Tell the program that it's no longer opened
		BigPopup.isOpen = false;

	}





	/**
	 * Shows the popup
	 * 
	 * @param {string} success The text on the success button
	 */
	show(success) {

		// If it's already open, don't let it open another one
		if(BigPopup.isOpen) {
			return false;
		}
		BigPopup.isOpen = true;

		// Clear everything first
		this.popup.innerHTML = "";

		// Add title
		this.popup.appendChild(BigPopup.createElement("h2", { innerHTML: this.title }));

		// Create form
		const form = BigPopup.createElement("div");
		form.classList.add("form");
		form.dataset.action = this.action;
		form.dataset.method = this.method;
		form.dataset.clearFields = true;
		form.setAttribute("name", "html-js-form");


		// Add elements
		for(const el of this.elements) {
			form.appendChild(el);
		}
		

		// Add buttons
		const buttonCancel = BigPopup.createElement("button", { innerHTML: "CANCEL", type: "submit", value: "submit", classList: "border--red small" });
		buttonCancel.addEventListener("click", () => {
			this.hide();
		});
		form.appendChild(buttonCancel);

		const buttonSave = BigPopup.createElement("button", { innerHTML: success, type: "submit", value: "submit", classList: "small" });
		buttonSave.addEventListener("click", () => {
			this.hide();
		});
		buttonSave.setAttribute("name", "html-js-form-submit");
		form.appendChild(buttonSave);


		// Add form to popup
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





/**
 * Creates an element
 * 
 * @param {string} el Element type
 * @param {object} options The values to add to the element
 * @returns {HTMLElement} The element that was created
 */
BigPopup.createElement = (el, options = {}) => {

	const elem = document.createElement(el);
	for(const [key, value] of Object.entries(options)) {
		elem[key] = value;
	}
	return elem;

}