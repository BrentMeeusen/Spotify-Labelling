class JWT {

	/**
	 * Constructor
	 * 
	 * @param {string} jwt JSON Web Token
	 */
	constructor(jwt) {
		this.jwt = jwt;
	}


	


	/**
	 * Validates the token
	 * 
	 * @returns true if the JWT is valid
	 */
	validate() {

		// Split into header and payload
		const parts = this.jwt.split(".");
		if(parts.length !== 3) { return false; }

		// Check if the token is still valid
		const header = JSON.parse(JWT.base64ToString(parts[0]));
		const payload = JSON.parse(JWT.base64ToString(parts[1]));

		// If it's not a JWT OR if it's not encoded as HS256, not valid
		if(header.typ !== "JWT" || header.alg !== "HS256") { return false; }

		// If the token is not valid yet, return false

		// If we're before the issued at date, return false

		// If the token has expired, return false

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





/**
 * Decodes a JWT Base64 string
 * 
 * @param {string} str The string to decode
 * @returns The decoded string
 */
JWT.base64ToString = (str) => {

	const remainder = str.length % 4;
	if(remainder > 0) {
		str += "=".repeat(4 - remainder);
	}

	str = str.replace("+", "-").replace("/", "_");
	return atob(str);

}