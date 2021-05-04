<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Register</title>

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

			<div class="form register-form" name="html-js-form" data-action="api/v1/users/create" data-method="POST" data-redirect="">

				<input class="small"	name="input FirstName"			type="text"			placeholder="FIRST NAME">
				<input class="small"	name="input LastName"			type="text"			placeholder="LAST NAME">
				<input class="small"	name="input Username"			type="text"			placeholder="USERNAME">
				<input class="small"	name="input EmailAddress"		type="text"			placeholder="EMAIL ADDRESS">
				<input class="small"	name="input Password"			type="password"		placeholder="PASSWORD"					id="password">
				<input class="small"	name="input PasswordRepeat"		type="password"		placeholder="REPEAT PASSWORD"	id="password-repeat">

				<button type="submit" name="html-js-form-submit" value="submit" id="register-btn" disabled>REGISTER</button>

			</div>


			
			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->



			<p class="footer">Already have an account? Click <a href="../">here</a>!</p>

		</div>


		<script src="../assets/js/api.js"></script>
		<script src="../assets/js/lazy-loading.js"></script>
		<script src="../assets/js/html-js-form.js"></script>
		<script src="../assets/js/popup.js"></script>
		<script src="../assets/js/general.js"></script>
		<script src="../assets/js/register.js"></script>
		
	</body>
</html>