<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Your Playlists</title>
		
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

				<button class="wide" id="add-playlist">ADD PLAYLIST</button>

				<div class="table-container" id="playlists"></div>

			</div>

		</div>	<!-- .main-wrapper -->



		<?php
		// Load JavaScript
		include_once("../../assets/snippets/javascript-files.php");
		?>

		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		// Add "Add playlist" button functionality
		document.getElementById("add-playlist").addEventListener("click", () => {

			const addLabel = new BigPopup("Add Playlist", "api/v1/playlists/create", "POST", "create-playlist-form");
			addLabel.add("input", "Name", { placeholder: "PLAYLIST NAME", type: "text", maxLength: "100" });
			addLabel.show("ADD");
			HtmlJsForm.findById("create-playlist-form").addCallback(async () => { Api.show.playlists(await Api.get.playlists()); });

		});

		// Load playlists
		window.addEventListener("load", async () => {
			Api.show.playlists(await Api.get.playlists());
		});

		</script>

	</body>
</html>