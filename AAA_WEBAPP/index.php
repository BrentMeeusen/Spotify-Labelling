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
		
			<h1>Spotify Labelling</h1>

			<?php
			// Add session message and code to the popup
			session_destroy();
			?>
		
		</div>


		<!-- Load all files -->
		<script src="../assets/js/api.js"></script>
		<script src="../assets/js/html-js-form.js"></script>
		<script src="../assets/js/lazy-loading.js"></script>
		<script src="../assets/js/popup.js"></script>
		<script src="../assets/js/register.js"></script>
		<script src="../assets/js/theme.js"></script>

		<!-- Load general file (which will be a minified version of all the files above that are general) -->
		<script src="../assets/js/general.js"></script>

	</body>
</html>