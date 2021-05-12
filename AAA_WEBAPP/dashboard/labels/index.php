<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Spotify Labelling | Your Account</title>
		
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
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
						</tr>
						<tr>
							<td>Rock</td>
							<td>Edit button</td>
							<td>Remove button</td>
							<td>public/private button</td>
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

		</script>

	</body>
</html>