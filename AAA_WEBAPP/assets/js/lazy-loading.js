class LazyLoading {

	allImages = [];





	/**
	 * LazyLoading constructor
	 *  
	 * @param {string} name The class name that all images have
	 */
	constructor(name) {
		
		// Set the name and allImages
		this.name = name;
		this.allImages = this.getAllImagesByClassName();

		// Add scroll EventListener
		window.addEventListener("scroll", () => {
			this.loadImages();
		});

	}





	/**
	 * Gets all images with the class name given in the constructor
	 */
	getAllImagesByClassName() {

		const images = document.getElementsByClassName(this.name);
		
		for(const img of images) {
			this.allImages.push(new LazyImage(img));
		}

	}





	/**
	 * Lazy loads all images found, should be triggered on scroll
	 */
	loadImages() {

		// Get the current page offset
		const d = document.documentElement;
		const top = (window.pageYOffset || d.scrollTop)  - (d.clientTop || 0);
		
		// Check for every image if it's (almost) in view
		for(let i = 0; i < this.allImages.length; i++) {

			var imgTop = allImages[i].image.offsetTop;
			if(imgTop < top + window.innerHeight  * 1.1) {
				allImages[i].load();
			}

		}

	}	// loadImages()

}	// class LazyLoading


class LazyImage {

	loadBig = !(window.innerWidth < 992);
	loaded = { small: false, medium: false, big: false };





	/**
	 * LazyImage constructor
	 * 
	 * @param {HTMLElement} image The image element
	 */
	constructor(image) {

		this.image = image;
		this.src = image.dataset.mainSrc;
		this.extension = image.dataset.extension;
		
	}





	/**
	 * Lazy load image
	 */
	load() {

		// If medium is loaded, load big
		if(this.loaded.medium) {

			this.image.src = this.src + "." + this.extension;
			this.image.addEventListener("load", () => {
				this.loaded.medium = true;
			});

		}

		// If small is loaded, load medium
		else if(this.loaded.small) {

			this.image.src = this.src + "-medium." + this.extension;
			this.image.addEventListener("load", () => {
				if(this.loaded.medium) { return false; }
				this.image.classList.remove("lazy--small");
				this.loaded.medium = true;
				if(this.loadBig) { this.load(); }
			});

		}

		// If small is not loaded, load small
		else if(!this.loaded.small) {

			this.image.src = this.src + "-small." + this.extension;
			this.image.addEventListener("load", () => {
				if(this.loaded.small) { return false; }
				this.image.classList.add("lazy--small");
				this.loaded.small = true;
				this.load();
			});

		}

	}	// load()

}	// LazyImage