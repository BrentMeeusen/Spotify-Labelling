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

				<button class="wide" id="import-songs">IMPORT SONGS</button>

				<div class="table-container">
					<table>
						<tbody id="songs">
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
		document.getElementById("import-songs").addEventListener("click", async () => {


			// Create popup
			const importSongs = new BigPopup("Import Songs", "api/v1/spotify/import", "POST", "import-songs-form");

			const tableContainer = Api.createElement("div", {classList: "table-container limit-height"});
			const table = Api.createElement("table");
			
			// Show all playlists for the user to choose from
			const res = await Api.sendRequest("api/v1/spotify/playlists", "GET");
			for(const list of res.data.playlists) {
				
				const row = Api.createElement("tr");
				row.appendChild(Api.createElement("td", { innerHTML: list.name }));
				row.appendChild(Api.createElement("td", { innerHTML: list.numTracks + " songs" }));
				row.appendChild(Api.createElement("input", { type: "checkbox", name: "lists[]" }));
				table.appendChild(row);

			}

			tableContainer.appendChild(table);
			importSongs.addElement(tableContainer);
			importSongs.show("IMPORT");
			HtmlJsForm.findById("import-songs-form").addCallback(() => {  });

		});

		</script>

	</body>
</html>