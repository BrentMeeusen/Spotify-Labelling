class Popup {}
Popup.container = document.getElementById("popup");
Popup.text = document.getElementById("popup-text");

/**
 * Show the popup
 * 
 * @param {string} message The message to show in the popup
 * @param {string} type The type of the message
 * @param {string} dur The duration of the popup
 */
Popup.show = (message, type, dur = 5000) => {

	Popup.text.innerHTML = message;
	Popup.container.style.top = 0;
	Popup.container.style.borderBottomColor = getComputedStyle(document.documentElement).getPropertyValue("--current--" + type);

	clearTimeout(Popup.timeout);
	Popup.timeout = setTimeout(() => {
		Popup.container.style.top = (-1 * Popup.container.offsetHeight) - 10 + "px";
	}, dur);

}