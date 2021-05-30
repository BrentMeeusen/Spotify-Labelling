class BigPopup {


	/**
	 * Constructor
	 * 
	 * @param {string} title The title of the popup
	 * @param {Array<HTMLElement>} elements The elements to add
	 */
	constructor(title, elements) {
		this.title = title;
		this.elements = elements;
		this.popup = document.getElementById("popup-big");
	}





	/**
	 * Creates an element
	 * 
	 * @param {string} el Element type
	 * @param {object} options The values to add to the element
	 * @returns {HTMLElement} The element that was created
	 */
	createElement(el, options) {

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
	 */
	show() {

		// Add title
		this.popup.appendChild(this.createElement("h2", { innerHTML: this.title }));

		// Add elements

		
		// Open popup
		this.popup.style.display = "block";
		setTimeout(() => {
			this.popup.classList.add("opened");
		}, 1);

	}


}

const bp = new BigPopup("abc", []);