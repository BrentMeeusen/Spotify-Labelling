class PasswordVerifier {

	/**
	 * If both are given, check whether the two passwords are valid and enable the submit button accordingly
	 * 
	 * @param {HTMLElement} pwd The <input> of the password field
	 * @param {HTMLElement} pwdRep The <input> of the password-repeat field
	 * @param {HTMLElement} submit The <button> of the submit button
	 */
	constructor(pwd, pwdRep, submit) {

		// Set variables
		this.password = pwd;
		this.passwordRepeat = pwdRep;
		this.submit = submit;

		// Set the EventListeners
		this.password.addEventListener("input", () => {
			this.verify();
		});
		this.passwordRepeat.addEventListener("input", () => {
			this.verify();
		});
		
	}



	

	/**
	 * Verifies whether the passwords are fine
	 */
	verify() {

		// Set variables
		const p1 = this.password.value;
		const p2 = this.passwordRepeat.value;

		// Logics
		if(p1 !== "" && p2 !== "" && p1 === p2) {
			this.enable();
		} else {
			this.disable();
		}

	}

}





window.addEventListener("load", async () => {
	
	// Get JWT for registering an account
	const res = await Api.sendRequest("api/v1/register", "POST");

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
