window.addEventListener("load", async () => {
	
	// Get JWT for registering an account
	const res = await Api.sendRequest("api/v1/register", "POST");
	console.log(res);

	// Passwords must match
	const password = document.getElementById("password");
	const passwordRepeat = document.getElementById("password-repeat");
	const button = document.getElementById("register-btn");


	// Password match checking method
	const setRegisterButton = (p1, p2) => {
		if(p1 === p2 && p1 !== "" && p2 !== "") {
			button.disabled = false;
		} else {
			button.disabled = true;
		}
	}

	// Try to check the passwords
	try {
		password.addEventListener("input", () => {
			try { setRegisterButton(password.value, passwordRepeat.value); } 
			catch(e) {}
		});
		passwordRepeat.addEventListener("input", () => {
			try { setRegisterButton(password.value, passwordRepeat.value); }
			catch(e) {}
		});
	} catch(e) {}

});
