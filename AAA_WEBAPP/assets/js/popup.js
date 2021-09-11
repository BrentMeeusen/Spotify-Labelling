class Popup {}
Popup.container = document.getElementById("popup");
Popup.text = document.getElementById("popup-text");





/**
 * When the window is loaded, add "Click to dismiss" button
 */
window.addEventListener("load", () => {
	Popup.container.appendChild(Api.createElement("p", { classList: "click-to-dismiss", innerHTML: "Click/tap to dismiss" }));
});





/**
 * When the popup is clicked, close it
 */
Popup.container.addEventListener("click", () => {
	Popup.hide();
});





/**
 * Show the popup
 * 
 * @param {string} message The message to show in the popup
 * @param {string} type The type of the message
 * @param {string} dur The duration of the popup
 */
Popup.show = (message, type, dur = 5000) => {

	// Show message
	Popup.text.innerHTML = message;

	// Set border type
	Popup.container.style.top = 0;
	Popup.container.style.borderBottomColor = getComputedStyle(document.documentElement).getPropertyValue("--current--" + type);

	clearTimeout(Popup.timeout);
	Popup.timeout = setTimeout(() => {
		Popup.hide();
	}, dur);

}





/**
 * Hide the popup
 */
Popup.hide = () => {
	Popup.container.style.top = (-1 * Popup.container.offsetHeight) - 10 + "px";
}