class PageProtect {}





/**
 * Protects the page when certain requirements aren't set
 * 
 * @param {object} options An object with all the requirements to meet
 */
PageProtect.protect = (options) => {

	const payload = Api.TOKEN.getPayload();
	console.log(payload);

	if(options.verified && payload.user) {

	}

}

