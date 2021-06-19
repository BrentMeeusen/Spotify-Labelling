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
		<link rel="stylesheet" type="text/css" href="../assets/css/general.css">
	</head>


	<body>

		<div class="main-wrapper">

			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>
		
			<h1>Spotify Labelling</h1>

			<div class="form register-form" name="html-js-form" data-action="api/v1/login" data-method="POST" data-redirect="../assets/php/auth.php">

			<input name="input Identifier"		type="text"			placeholder="USERNAME OR EMAIL ADDRESS">
			<input name="input Password"		type="password"		placeholder="PASSWORD"	id="password">

			<button type="submit" name="html-js-form-submit" value="submit" id="login-btn">LOGIN</button>

			</div>



			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->



			<p class="footer">Don't have an account yet? Click <a href="register">here</a>!</p>

		</div>



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");
		
		// Load showing result after redirect
		include_once("../assets/snippets/show-redirect-result.php");
		?>

		<script>
		
		// Create a token
		window.addEventListener("load", async () => {
			await Api.sendRequest("api/v1/register", "POST");
		});
		
		</script>

	</body>
</html>