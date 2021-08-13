<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Your Songs</title>
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<?php include_once("../../assets/snippets/css.php"); ?>
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<?php include_once("../../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1><a href="../">Spotify Labelling</a></h1>
			
			<!-- Content -->
			<div class="module">

				<div class="table-container" id="tracks">

					<div class="row">
						<div class="text">
							<p class="title">This is a very long track title that must be truncated</p>
							<p class="artist">Artist name</p>
							<p class="added">14/08/2021</p>
						</div>
						<button class="icon"><img src="../../assets/icons/more_horiz.png"></button>
					</div>

				</div>

			</div>

		</div>	<!-- .main-wrapper -->



		<?php
		// Load JavaScript
		include_once("../../assets/snippets/javascript-files.php");
		?>

		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		// Load tracks
		window.addEventListener("load", async () => {
			
			document.getElementById("tracks").innnerHTML = "Loading...";
			const res = await Api.sendRequest("api/v1/tracks/get", "GET");
			if(res && res.code && (res.code < 200 || res.code > 299)) {
				Popup.show(res.error, "error", 5000);
			}
			console.log(res);
			Api.showTracks(res.data);

		});

		</script>

	</body>
</html>