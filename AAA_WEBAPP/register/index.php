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

			<div class="form register-form" data-type="form" data-action="users/create">

				<input class="small"	name="first-name"		type="text"			placeholder="FIRST NAME">
				<input class="small"	name="last-name"		type="text"			placeholder="LAST NAME">
				<input class="small"	name="username"			type="text"			placeholder="USERNAME">
				<input class="small"	name="email-address"	type="text"			placeholder="EMAIL ADDRESS">
				<input class="small"	name="password"			type="password"		placeholder="PASSWORD">
				<input class="small"	name="password-repeat"	type="password"		placeholder="REPEAT PASSWORD">

				<button type="submit" name="submit" value="submit">REGISTER</button>

			</div>


			<button onclick="changeTheme()">
				Toggle theme!
			</button>



			<p class="footer">Already have an account? Click <a href="../">here</a>!</p>

		</div>


		<script src="../assets/js/general.js"></script>
		
	</body>
</html>