<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling</title>
		
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

			<!-- <div class="form register-form" name="html-js-form" data-action="api/v1/login" data-method="POST" data-redirect="dashboard">

			<input name="input Identifier"		type="text"			placeholder="USERNAME OR EMAIL ADDRESS">
			<input name="input Password"		type="password"		placeholder="PASSWORD"	id="password">

			<button type="submit" name="html-js-form-submit" value="submit" id="login-btn">LOGIN</button>

			</div> -->



			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->



		
		</div>


		<!-- Load all files -->
		<script src="../assets/js/api.js"></script>
		<script src="../assets/js/html-js-form.js"></script>
		<script src="../assets/js/lazy-loading.js"></script>
		<script src="../assets/js/popup.js"></script>
		<script src="../assets/js/theme.js"></script>

		<!-- Load general file (which will be a minified version of all the files above that are general) -->
		<script src="../assets/js/general.js"></script>

		<!-- Load code for showing result of the action of the previous page (due to redirect) -->
		<?php include_once("../assets/php/show-redirect-result.php"); ?>

	</body>
</html>