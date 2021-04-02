let theme = "dark";

function changeTheme() {

	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];

	theme = (theme === "dark" ? "light" : "dark");

	for(let i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], 
				getComputedStyle(document.documentElement).getPropertyValue("--" + theme + "--" + variables[i]));
	}

}