<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Register</title>

		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<?php include_once("../assets/snippets/css.php"); ?>

	</head>


	<body>

		<div class="main-wrapper">

			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<h1><a href="../">Spotify Labelling</a></h1>

			<div class="form register-form" name="html-js-form" data-action="api/v1/users/create" data-method="POST" data-redirect="">

				<input class=""	name="input EmailAddress"		type="text"			placeholder="EMAIL ADDRESS">
				<input class="small"	name="input Password"			type="password"		placeholder="PASSWORD"					id="password">
				<input class="small"	name="input PasswordRepeat"		type="password"		placeholder="REPEAT PASSWORD"	id="password-repeat">

				<button type="submit" name="html-js-form-submit" value="submit" id="register-btn" disabled>REGISTER</button>

			</div>


			
			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->



			<p class="footer">Already have an account? Click <a href="../login">here</a>!</p>

		</div>



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php"); 
		?>

		<script>

		// Get a token
		window.addEventListener("load", async () => {
			await Api.sendRequest("api/v1/register", "POST");
		});

		// Create a password verifier
		const pv = new PasswordVerifier(document.getElementById("password"), document.getElementById("password-repeat"), document.getElementById("register-btn"));

		</script>
		
	</body>
</html>