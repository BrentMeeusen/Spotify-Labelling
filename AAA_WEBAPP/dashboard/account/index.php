<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Your Account</title>
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<link rel="stylesheet" type="text/css" href="../../assets/css/general.css">
		<link rel="stylesheet" type="text/css" href="../../assets/css/dashboard.css">
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<!-- Menu -->
			<div class="nav-btn" id="nav-open">
				<img src="../../assets/icons/menu/menu-open.png">
			</div>

			<?php include_once("../../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1><a href="">Spotify Labelling</a></h1>
			


			<!-- Update user info -->
			<div class="module">

				<div class="form register-form" name="html-js-form" data-action="api/v1/users/update" data-method="POST" data-id="account-values">

					<input class="small"	name="input FirstName"			type="text"			placeholder="FIRST NAME">
					<input class="small"	name="input LastName"			type="text"			placeholder="LAST NAME">
					<input class="small"	name="input Username"			type="text"			placeholder="USERNAME">
					<input class="small"	name="input EmailAddress"		type="text"			placeholder="EMAIL ADDRESS">

					<button type="submit" name="html-js-form-submit" value="submit">UPDATE ACCOUNT INFORMATION</button>

				</div>

			</div>	<!-- .module -->


			
			<!-- Update password -->
			<div class="module">

				<div class="form register-form" name="html-js-form" data-action="api/v1/users/update" data-method="POST" data-clear-fields="true">

					<input class="small"	name="input Password"			type="password"		placeholder="PASSWORD"					id="password">
					<input class="small"	name="input PasswordRepeat"		type="password"		placeholder="REPEAT PASSWORD"	id="password-repeat">

					<button type="submit" name="html-js-form-submit" value="submit" id="update-password-btn" disabled>UPDATE PASSWORD</button>

				</div>

			</div>	<!-- .module -->

			
			
			<!-- Delete account -->
			<div class="module">

				<div class="form register-form" name="html-js-form" data-action="api/v1/users/delete" data-method="DELETE" data-redirect="logout" data-clear-fields="true">

					<input name="input Password"	type="password"		placeholder="TYPE PASSWORD TO CONFIRM">

					<button class="border--red" type="submit" name="html-js-form-submit" value="submit" id="update-password-btn">DELETE ACCOUNT</button>

				</div>

			</div>	<!-- .module -->

		</div>	<!-- .main-wrapper -->



		<?php
		// Load JavaScript
		include_once("../../assets/snippets/javascript-files.php");
		?>

		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		// Create a password verifier
		const pv = new PasswordVerifier(document.getElementById("password"), document.getElementById("password-repeat"), document.getElementById("update-password-btn"));

		// Fill the "account" form with current values
		window.addEventListener("load", () => {
			HtmlJsForm.findById("account-values").fillValues(Api.TOKEN.getPayload().user);
		});

		</script>

	</body>
</html>