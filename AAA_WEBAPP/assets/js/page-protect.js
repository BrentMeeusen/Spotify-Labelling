class PageProtect {}





/**
 * Protects the page when certain requirements aren't set
 * 
 * @param {object} options An object with all the requirements to meet
 */
PageProtect.protect = (options) => {

	const payload = Api.TOKEN.getPayload();

	// If the user needs to be verified AND if the required level 
	if(options.verifiedLevel && payload.user.accountStatus < options.verifiedLevel) {
		window.location.href = "/Spotify Labelling/AAA_WEBAPP/assets/php/redirect.php?code=403&message=" + encodeURIComponent("Access forbidden.") + "&redirect=" + encodeURIComponent("");
	}

	return true;

}

