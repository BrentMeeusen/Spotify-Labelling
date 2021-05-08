class PageProtect {}





/**
 * Protects the page when certain requirements aren't set
 * 
 * @param {object} options An object with all the requirements to meet
 */
PageProtect.protect = (options) => {

	// If there's no token, or if the token is invalid, redirect to login
	if(!Api.TOKEN || !Api.TOKEN.validate()) {
		window.location.href = "/Spotify Labelling/AAA_WEBAPP/assets/php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}
	
	// Get the token
	const payload = Api.TOKEN.getPayload();

	// If the user needs to be verified AND if the account status is less than the required level, redirect to login 
	if(options.verifiedLevel && payload.user.accountStatus < options.verifiedLevel) {
		window.location.href = "/Spotify Labelling/AAA_WEBAPP/assets/php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}

	// If all page protection went fine, return true
	return true;

}

