<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Your Labels</title>
		
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

				<button class="wide" id="add-label">ADD LABEL</button>

				<div class="table-container">
					<table>
						<tbody id="labels">
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

		// Add "Add label" button functionality
		document.getElementById("add-label").addEventListener("click", () => {

			const addLabel = new BigPopup("Add Label", "api/v1/labels/create", "POST", "create-label-form");
			addLabel.add("input", "Name", { placeholder: "LABEL NAME", type: "text", maxLength: "100" });
			addLabel.show("ADD");
			HtmlJsForm.findById("create-label-form").addCallback(() => { Api.showLabels(); });

		});

		// Load labels
		window.addEventListener("load", async () => {
			Api.showLabels();
		});

		</script>

	</body>
</html>