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
		<?php include_once("../assets/snippets/css.php"); ?>

	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<?php include_once("../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1>Spotify Labelling</h1>

			<!-- Content -->



			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->

		</div>





		<?php

		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");

		// Load showing result after redirect
		include_once("../assets/snippets/show-redirect-result.php");

		?>



		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		// When the page loads
		window.addEventListener("load", async () => {
			
			// Get the Spotify email address if user has just logged in
			const message = <?php print(isset($message) ? "\"$message\"" : "null"); ?>;
			if(message === "Logged in successfully.") {
				const res = await Api.sendRequest("api/v1/users/set-spotify-email/", "POST");
				Api.show.spotifyEmail(res.data);
			}

		});

		</script>

	</body>
</html>