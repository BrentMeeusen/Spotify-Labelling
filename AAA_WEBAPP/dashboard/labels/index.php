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

				<button class="wide" id="add-label">ADD LABEL</button>

				<div class="table-container">
					<table>
						<tr>
							<td>Rock</td>
							<td>xx songs</td>
							<td>Public</td>
							<td><button class="icon"><img src="../../assets/icons/edit.png"></button></td>
							<td><button class="icon"><img src="../../assets/icons/delete.png"></button></td>
							<td><button class="icon"><img src="../../assets/icons/eye.png"></button></td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>xx songs</td>
							<td>Public</td>
							<td><button class="icon"><img src="../../assets/icons/edit.png"></button></td>
							<td><button class="icon"><img src="../../assets/icons/delete.png"></button></td>
							<td><button class="icon"><img src="../../assets/icons/eye.png"></button></td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>xx songs</td>
							<td>Private</td>
							<td><button class="icon"><img src="../../assets/icons/edit.png"></button></td>
							<td><button class="icon"><img src="../../assets/icons/delete.png"></button></td>
							<td><button class="icon"><img src="../../assets/icons/eye.png"></button></td>
						</tr>
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

		// Add label functionality
		document.getElementById("add-label").addEventListener("click", () => {

			const addLabel = new BigPopup("Add Label", "api/v1/labels/create", "POST");
			addLabel.add("input", "Name", { placeholder: "LABEL NAME" });
			addLabel.show("ADD");

		});

		// Load labels
		window.addEventListener("load", async () => {
			const res = await Api.sendRequest("api/v1/labels/all/" + Api.TOKEN.getPayload().user.id, "GET");
			Api.showLabels(res);
		});

		</script>

	</body>
</html>