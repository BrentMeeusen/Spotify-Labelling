class PageProtect {}





/**
 * Protects the page when certain requirements aren't set
 * 
 * @param {object} options An object with all the requirements to meet
 */
PageProtect.protect = (options) => {

	// Get the token
	const payload = Api.TOKEN.getPayload();

	// If the token is invalid, redirect to login
	if(!payload) {
		window.location.href = "/Spotify Labelling/AAA_WEBAPP/assets/php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}

	// If the user needs to be verified AND if the account status is less than the required level, redirect to login 
	if(options.verifiedLevel && payload.user.accountStatus < options.verifiedLevel) {
		window.location.href = "/Spotify Labelling/AAA_WEBAPP/assets/php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}

	// If all page protection went fine, return true
	return true;

}

