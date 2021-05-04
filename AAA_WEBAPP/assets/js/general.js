var IMAGES = [];

/**
 * When the website loads
 */
window.addEventListener("load", () => {

	// HTML JAVASCRIPT FORMS ===================================================================
	// Load all the HTML/JavaScript
	const forms = document.getElementsByName("html-js-form");
	let index = 0;

	// For each form
	for(form of forms) {

		// When the corresponding submit button is clicked
		const submit = document.getElementsByName("html-js-form-submit")[index++];
		submit.addEventListener("click", () => {

			// Get all inputs and values
			let inputs = [];
			for(input of form.childNodes) {
				if(input.name) {
					if(input.name.includes("input")) {
						inputs.push({ name: input.name.replace("input ", ""), value: input.value });
					}
				}
			}

			// Create an XMLHttpResponse
			requestLabellingApiEndpoint(form.dataset.action, form.dataset.method, inputs);

		});
		
	}


	// LAZY LOADING ===================================================================
	// Initialise lazy loading
	const ALL_IMAGES = document.getElementsByClassName("lazy");
    var neverLoadMax = (window.innerWidth < 992 ? true : false);

	// Get all images
    for(let i = 0; i < ALL_IMAGES.length; i++) {
        if(neverLoadMax) { ALL_IMAGES[i].dataset.loadMax = "false"; }
        IMAGES[i] = ALL_IMAGES[i];
    }

	// Load images
    loadImages();



	// DARK THEME ===================================================================
	// Set the variables to update
	const variables = ["background", "text", "placeholder", "link", "background-highlight", "highlight"];
	const theme = "dark"; // Will be set as a cookie!

	// Loop over all variables and change their values
	for(let i = 0 ; i < variables.length; i++) {
		document.documentElement.style.setProperty("--current--" + variables[i], getComputedStyle(document.documentElement).getPropertyValue("--" + theme + "--" + variables[i]));
	}
	
});






/**
 * When the user scrolls
 */
window.addEventListener("scroll", function() {
    loadImages();
});



/**
 * Loads all the images and stores them in a variable
 */
function loadImages() {

    var d = document.documentElement;
    var top = (window.pageYOffset || d.scrollTop)  - (d.clientTop || 0);
    
    for(let i = 0; i < IMAGES.length; i++) {
        var imgTop = IMAGES[i].offsetTop;
        if(imgTop < top + window.innerHeight  * 1.1) {
            loadImage(IMAGES[i]);
        }
    }

}


/**
 * Loads the image in the correct size
 * 
 * @param {HTMLElement} img 
 */
function loadImage(img) {

    if(img.dataset.loadedMedium && img.dataset.loadMax === "true") {
        img.src = img.dataset.src + "." + img.dataset.extension;
        img.addEventListener("load", function() {
            img.dataset.loaded = true;
        });
    }

    else if(img.dataset.loadedSmall) {
        img.src = img.dataset.src + "-medium." + img.dataset.extension;
        img.addEventListener("load", function() {
            if(img.dataset.loadedMedium) { return false; }
            img.classList.remove("lazy--small");
            img.dataset.loadedMedium = true;
            if(img.dataset.loadMax === "true") {
                loadImage(img);
            }
        });
    }

    else if(!img.src) {
        img.src = img.dataset.src + "-small." + img.dataset.extension;
        img.addEventListener("load", function() {
            if(img.dataset.loadedSmall) { return false; }
            img.classList.add("lazy--small");
            img.dataset.loadedSmall = true;
            loadImage(img);
        });
    }
	
}














/**
 * Requests an endpoint from the Spotify Labelling API
 * 
 * @param {string} action What address the form is going to call
 * @param {string} method Request method
 * @param {array[string]} values The POST values to send to the address
 * @return {Object} The response of the endpoint
 */
function requestLabellingApiEndpoint(action, method, values = null) {
	console.log("action", action);
	console.log("method", method);
	console.log("values", values);

	const endpoint = "http://localhost/Spotify Labelling/AAA_API/" + action;
	const postParameters = "";
	let toReturn = {};

	const xml = new XMLHttpRequest();
	xml.addEventListener("load", () => {
		console.log(this);
	});
	xml.open(method, endpoint);
	xml.send(postParameters);

	return toReturn;

}



