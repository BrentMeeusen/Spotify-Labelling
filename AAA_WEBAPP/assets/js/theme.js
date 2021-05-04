class Theme {}





/**
 * Setting the theme of the website
 * @param {string} theme The theme to set (light or dark)
 */
Theme.setTheme = (theme) => {

	// Set the variables to update
	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];
	const theme = (["light", "dark"].includes(theme) ? theme : "dark"); // Will be set as a cookie!

	// Loop over all variables and change their values
	for(let i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], getComputedStyle(document.documentElement).getPropertyValue("--" + theme + "--" + variables[i]));
	}
	
}