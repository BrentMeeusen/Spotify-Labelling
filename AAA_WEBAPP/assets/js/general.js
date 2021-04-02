let theme = "dark";

function test() {

	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];

	theme = (theme === "dark" ? "light" : "dark");

	for(const i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], 
				getComputedStyle(document.documentElement.getPropertyValue("--" + theme + "--" + variables[i])));
	}
	
	getComputedStyle(document.documentElement).getPropertyValue('--my-variable-name');


	document.documentElement.style.setProperty('--my-variable-name', 'pink');

}