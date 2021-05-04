window.addEventListener("load", async () => {
	const res = await requestLabellingApiEndpoint("api/v1/register/", "POST");
	console.log(res);
});