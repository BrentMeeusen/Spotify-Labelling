class JWT {

	constructor(jwt) {
		this.jwt = jwt;
	}

	validate() {

		// Split into header and payload
		const parts = this.jwt.split(".");
		if(parts.length !== 3) { return false; }

		// Check if the token is still valid

		// Return whether it's valid

	}

	getPayload() {

		// Validate token
		if(!this.validate()) {
			return false;
		}

		// Return the payload as an object


	}

}