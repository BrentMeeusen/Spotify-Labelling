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


	createElement(el, options) {

		const elem = document.createElement(el);
		for(const [key, value] of Object.entries(options)) {
			elem.key = value;
		}

	}


	show() {
		
		const popup = document.getElementById("popup-big");
		this.createElement("h2", { innerHTML: "xyz", id: "69" });

	}


}

const bp = new BigPopup("abc", []);
bp.show();