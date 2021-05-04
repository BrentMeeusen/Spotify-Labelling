window.addEventListener("load", () => {
	const res = requestLabellingApiEndpoint("api/v1/register/", "POST");
	console.log(res);
});