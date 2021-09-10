<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Login</title>
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<?php include_once("../assets/snippets/css.php"); ?>
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Notification popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<!-- Popup -->
			<div class="popup-big" id="popup-big"></div>
		
			<h1><a href="../">Spotify Labelling</a></h1>

			<div class="form register-form" name="html-js-form" data-action="api/v1/login" data-method="POST" data-redirect="assets/php/auth.php">

				<input name="input Identifier"		type="text"			placeholder="EMAIL ADDRESS">
				<input name="input Password"		type="password"		placeholder="PASSWORD"	id="password">

				<button type="submit" name="html-js-form-submit" value="submit" id="login-btn">LOGIN</button>

			</div>

			<p class="forgot-password">Forgot your password? Click <span id="forgot-password">here</span> to reset your password.</p>



			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->



			<p class="footer">Don't have an account yet? Click <a href="../register">here</a>!</p>

		</div>



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");
		
		// Load showing result after redirect
		include_once("../assets/snippets/show-redirect-result.php");
		?>

		<script>

		// Add functionality for "forgot password" link
		document.getElementById("forgot-password").addEventListener("click", () => {

			const popup = new BigPopup("Forgot password?", "api/v1/user/forgot-password", "POST");
			popup.add("p", "", { innerHTML: "This will send an email to the email address you fill in with a link where you can reset your password." });
			popup.add("input", "email-address", { placeholder: "Email address" });
			popup.show("Send email");

		});
		
		// Create a token
		window.addEventListener("load", async () => {
			await Api.sendRequest("api/v1/register", "POST");
		});
		
		</script>

	</body>
</html>