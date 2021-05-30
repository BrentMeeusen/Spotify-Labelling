class Navigation {

	/**
	 * Constructor for navigation
	 * 
	 * @param {HTMLElement} nav The navigation menu
	 * @param {HTMLElement} open The opening button
	 * @param {HTMLElement} close The closing button
	 */
	constructor(nav, open, close) {

		// Set variables
		this.nav = nav;
		this.openBtn = open;
		this.closeBtn = close;

		// Set events
		this.openBtn.addEventListener("click", () => {
			this.open();
		});
		this.closeBtn.addEventListener("click", () => {
			this.close();
		});

	}





	/**
	 * Opens the navigation menu given that no popup is opened
	 */
	open() {
		if(!BigPopup.isOpen) {
			this.nav.classList.add("open");
		}
	}





	/**
	 * Closes the menu
	 */
	close() {
		this.nav.classList.remove("open");
	}

}