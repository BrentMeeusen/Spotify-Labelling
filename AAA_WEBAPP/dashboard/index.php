<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Dashboard</title>
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<link rel="stylesheet" type="text/css" href="../assets/css/general.css">
		<link rel="stylesheet" type="text/css" href="../assets/css/dashboard.css">
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<?php include_once("../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1><a href="">Spotify Labelling</a></h1>

			<!-- Content -->

			<!-- <div class="form register-form" name="html-js-form" data-action="api/v1/login" data-method="POST" data-redirect="dashboard">

			<input name="input Identifier"		type="text"			placeholder="USERNAME OR EMAIL ADDRESS">
			<input name="input Password"		type="password"		placeholder="PASSWORD"	id="password">

			<button type="submit" name="html-js-form-submit" value="submit" id="login-btn">LOGIN</button>

			</div> -->



			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->

		</div>



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");
		?>
		
		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		// Force login
		window.location.href = (`https://accounts.spotify.com/authorize?response_type=code
		&client_id=a209cbda1aaa4f408bd6ae2efc2264fb&redirect_uri=http%3A%2F%2Flocalhost%2FSpotify%20Labelling%2FAAA_WEBAPP%2Fdashboard
		&scope=user-read-email
		%20user-read-private
		%20playlist-modify-public
		%20playlist-modify-private
		%20playlist-read-private
		%20playlist-read-collaborative
		%20user-library-read
		&show_dialog=true`);
		</script>

		<?php
		// Load showing result after redirect
		include_once("../assets/snippets/show-redirect-result.php");
		?>

		<script>
		
		</script>

	</body>
</html>