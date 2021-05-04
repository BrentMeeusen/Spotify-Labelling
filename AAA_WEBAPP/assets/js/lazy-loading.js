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

		const d = document.documentElement;
		const top = (window.pageYOffset || d.scrollTop)  - (d.clientTop || 0);
		
		for(let i = 0; i < allImages.length; i++) {
			var imgTop = allImages[i].offsetTop;
			if(imgTop < top + window.innerHeight  * 1.1) {
				loadImage(allImages[i]);
			}
		}

	}

}


class LazyImage {

	constructor(image) {

		this.domElement = image;
		this.src = image.dataset.mainSrc;
		this.extension = image.dataset.extension;
		
	}

}