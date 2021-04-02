<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Register</title>

		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../assets/css/general.css">

	</head>


	<body>

		<div class="main-wrapper">

			<h1>Spotify Labelling</h1>

			<div class="form register-form" name="html-js-form" data-action="users/create" data-method="POST">

				<input class="small"	name="input first-name"			type="text"			placeholder="FIRST NAME">
				<input class="small"	name="input last-name"			type="text"			placeholder="LAST NAME">
				<input class="small"	name="input username"			type="text"			placeholder="USERNAME">
				<input class="small"	name="input email-address"		type="text"			placeholder="EMAIL ADDRESS">
				<input class="small"	name="input password"			type="password"		placeholder="PASSWORD">
				<input class="small"	name="input password-repeat"	type="password"		placeholder="REPEAT PASSWORD">

				<button type="submit" name="html-js-form-submit" value="submit">REGISTER</button>

			</div>


			<button onclick="changeTheme()">
				Toggle theme!
			</button>



			<p class="footer">Already have an account? Click <a href="../">here</a>!</p>

		</div>


		<script src="../assets/js/general.js"></script>
		
	</body>
</html>