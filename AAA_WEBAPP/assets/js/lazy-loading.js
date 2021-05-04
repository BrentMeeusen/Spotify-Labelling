class LazyLoading {

	allImages = [];
	loadBiggest = !(window.innerWidth < 992);





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

		// Check from big to small whether it's loaded

	}

}