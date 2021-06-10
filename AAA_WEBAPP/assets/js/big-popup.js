class BigPopup {


	/**
	 * Constructor
	 * 
	 * @param {string} title The title of the popup
	 * @param {string} action The resulting action of a successful submission
	 * @param {string} method The method of that action
	 * @param {string} id The ID of the form
	 */
	constructor(title, action, method, id = null) {

		this.title = title;
		this.action = action;
		this.method = method;
		this.id = id;

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

		const el = Api.createElement(element, options);
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
		this.popup.appendChild(Api.createElement("h2", { innerHTML: this.title }));

		// Create form
		const form = Api.createElement("div");
		form.classList.add("form");
		form.dataset.action = this.action;
		form.dataset.method = this.method;
		form.dataset.clearFields = true;
		form.setAttribute("name", "html-js-form");
		(this.id !== null ? form.id = this.id : true);


		// Add elements
		for(const el of this.elements) {
			form.appendChild(el);
		}
		

		// Add buttons
		const buttonCancel = Api.createElement("button", { innerHTML: "CANCEL", type: "submit", value: "submit", classList: "border--red small" });
		buttonCancel.addEventListener("click", () => {
			this.hide();
		});
		form.appendChild(buttonCancel);

		const buttonSave = Api.createElement("button", { innerHTML: success, type: "submit", value: "submit", classList: "small" });
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