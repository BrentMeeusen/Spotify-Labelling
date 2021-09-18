<?php
session_start();

$email = @$_GET["email"];
$publicUserID = @$_GET["id"];
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

			<div class="module">
				<p>Here you can reset the password for <?php print($email); ?>.</p>
			</div>

			<div class="form register-form" name="html-js-form" data-action="api/v1/users/set-password" data-method="POST" data-redirect="">

				<input name="input Password"		type="password"		placeholder="PASSWORD" 			id="password">
				<input name="input PasswordRepeat"	type="password"		placeholder="REPEAT PASSWORD"	id="password-repeat">
				<input name="input EmailAddress" value="<?php print($email); ?>" type="text" hidden>
				<input name="input UserID" value="<?php print($publicUserID); ?>" type="text" hidden>

				<button type="submit" name="html-js-form-submit" value="submit" id="submit" disabled>SET PASSWORD</button>

			</div>


			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->

		</div>



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");
		
		// Load showing result after redirect
		include_once("../assets/snippets/show-redirect-result.php");
		?>

		<script>

		// Create a password verifier
		const pv = new PasswordVerifier(
			document.getElementById("password"),
			document.getElementById("password-repeat"),
			document.getElementById("submit")
		);
		
		// Create a token
		window.addEventListener("load", async () => {
			await Api.sendRequest("api/v1/register", "POST");
		});
		
		</script>

	</body>
</html>