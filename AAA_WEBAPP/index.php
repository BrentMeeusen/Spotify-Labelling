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
		<?php include_once("assets/snippets/css.php"); ?>
	</head>


	<body>

		<div class="main-wrapper">

			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>
		
			<h1>Spotify Labelling</h1>

			<div class="module text">

				<p>Spotify is a great platform for streaming music, but to me it lacks one key feature: smart playlists. They generate playlists based on a couple of rules that you can set. So I decided to create it myself! </p>

				<ul class="links">
					<li><a href="how-it-works">How it works</a></li>
					<li><a href="login">Login</a></li>
					<li><a href="register">Register</a></li>
				</ul>

			</div>

			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->

			<p class="footer" style="text-transform: lowercase;">v0.10.2-alpha</p>

		</div>



		<?php
		// Load JavaScript
		include_once("assets/snippets/javascript-files.php");

		// Load showing result after redirect
		include_once("assets/snippets/show-redirect-result.php");
		?>

	</body>
</html>