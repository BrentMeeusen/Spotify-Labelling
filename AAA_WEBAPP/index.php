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
		<link rel="stylesheet" type="text/css" href="assets/css/general.css">
		<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css">
	</head>


	<body>

		<div class="main-wrapper">

			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>
		
			<h1>Spotify Labelling</h1>

			<div class="module text">

				<p>Spotify is a great platform for streaming music, but to me it lacks one key feature: smart playlists. They generate playlists based on a couple of rules that you can set. So I decided to create it myself! </p>

				<h2>How it works</h2>
				<p>First, you <a href="register">create an account</a>. After verifying your account by clicking the link in the email, you can <a href="login">login</a>. This is when you give the application access to your Spotify account which is required for this to work.</p>
				<p>Once you've logged in, you can now import songs from your playlists into the app, and create your labels. When you got your songs and labels, you can now start connecting them together.</p>
				<p>Now you can create the actual playlists by selecting the corresponding rules. All that is left now is to click the "Update Spotify playlists" button, which will update existing linked playlists and create the new playlists if you created new playlists.</p>

			</div>

			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->

			<p class="footer" style="text-transform: lowercase;">v0.10.0-alpha</p>

		</div>



		<?php
		// Load JavaScript
		include_once("assets/snippets/javascript-files.php");

		// Load showing result after redirect
		include_once("assets/snippets/show-redirect-result.php");
		?>

	</body>
</html>