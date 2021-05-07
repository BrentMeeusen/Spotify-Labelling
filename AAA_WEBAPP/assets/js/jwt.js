class JWT {

	constructor(jwt) {
		this.jwt = jwt;
	}

	validate() {

		// Split into header and payload

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