<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Import Songs</title>
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap">
		<link rel="stylesheet" type="text/css" href="../../assets/css/general.css">
		<link rel="stylesheet" type="text/css" href="../../assets/css/dashboard.css">
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

				<div class="table-container">
					<table>
						<tbody id="playlists">
						</tbody>
					</table>
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

		// Add "Import songs" button functionality
		const res = await Api.sendRequest("api/v1/spotify/playlists", "GET");
		Api.showPlaylistsForImport(res.data.playlists);

		</script>

	</body>
</html>