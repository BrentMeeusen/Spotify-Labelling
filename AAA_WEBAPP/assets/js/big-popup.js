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
	 * Shows the popup
	 */
	show() {
		
		const popup = document.getElementById("popup-big");
		popup.appendChild(this.createElement("h2", { innerHTML: this.title }));

	}


}

const bp = new BigPopup("abc", []);
bp.show();