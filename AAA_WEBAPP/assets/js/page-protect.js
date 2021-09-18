class PageProtect {}





/**
 * Protects the page when certain requirements aren't set
 * 
 * @param {object} options An object with all the requirements to meet
 */
PageProtect.protect = (options) => {

	// If there's no token, or if the token is invalid, redirect to login
	if(!Api.TOKEN || !Api.TOKEN.validate()) {
		window.location.href = VALUES.assets + "php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}
	
	// Get the token
	const payload = Api.TOKEN.getPayload();

	// If verifiedLevel is set AND (payload.user isn't set OR verifiedLevel is higher than accountStatus)
	if(options.verifiedLevel && (!payload.user || options.verifiedLevel > payload.user.accountStatus)) {
		window.location.href = VALUES.assets + "php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}

	// If all page protection went fine, set user Spotify email address and return true
	Api.show.spotifyEmail(payload.user.spotifyEmail || "unkown");
	return true;

}

