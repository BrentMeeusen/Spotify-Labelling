<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | How It Works</title>
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<?php include_once("../assets/snippets/css.php"); ?>
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<!-- Title -->
			<h1><a href="../dashboard">Spotify Labelling</a></h1>



			<!-- Content -->
			<div class="module text">

				<p>First, you <a href="../register">create an account</a>. After verifying your account by clicking the link in the email, you can <a href="../login">login</a>. This is when you give the application access to your Spotify account which is required for this to work.</p>

				<p>Once you've logged in, you can now import songs from your playlists into the app, and create your labels. When you got your songs and labels, you can now start connecting them together.</p>

				<p>Now you can create the actual playlists by selecting the corresponding rules. All that is left now is to click the "Update Spotify playlists" button, which will update existing linked playlists and create the new playlists if you created new playlists.</p>

			</div>	<!-- .module -->

			<p class="footer"><a onclick="window.history.go(-1);">Back</a></p>
			
		</div>	<!-- .main-wrapper -->



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");
		?>

	</body>
</html>