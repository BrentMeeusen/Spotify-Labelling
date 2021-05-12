/**
 * When the website loads
 */
window.addEventListener("load", async () => {

	// Create a lazy loading object
	const lazy = new LazyLoading("lazy-image");
	Theme.setTheme("dark");

	// Create menu object
	const nav = document.getElementById("nav-menu");
	const open = document.getElementById("nav-open");
	const close = document.getElementById("nav-close");

	if(nav && open && close) {
		const navigation = new Navigation(nav, open, close);
	}

});
