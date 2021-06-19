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
		<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css">
		<link rel="stylesheet" type="text/css" href="assets/css/general.css">
	</head>


	<body>

		<div class="main-wrapper">

			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>
		
			<h1>Spotify Labelling</h1>

			<div class="module text">

				<p></p>

			</div>

			<!-- <img class="lazy-image" style="margin-top: 100vh;" data-src="../assets/images/test-image", data-extension="png"> -->

		</div>



		<?php
		// Load JavaScript
		include_once("assets/snippets/javascript-files.php");

		// Load showing result after redirect
		include_once("assets/snippets/show-redirect-result.php");
		?>

	</body>
</html>