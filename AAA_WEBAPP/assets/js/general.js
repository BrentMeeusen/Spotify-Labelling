var IMAGES = [];
var TOKEN;

/**
 * When the website loads
 */
window.addEventListener("load", async () => {


	// REFACTORING ====


	const lazy = new LazyLoading("lazy-image");



	// END REFACTORING ==== 




	// DARK THEME ===================================================================
	// Set the variables to update
	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];
	const theme = "dark"; // Will be set as a cookie!

	// Loop over all variables and change their values
	for(let i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], getComputedStyle(document.documentElement).getPropertyValue("--" + theme + "--" + variables[i]));
	}
	
});



