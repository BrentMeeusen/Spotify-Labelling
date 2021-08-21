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
		<link rel="stylesheet" href="../../assets/css/option-popup.css">
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<!-- Track option popup -->
			<div class="option-popup" id="option-popup"></div>

			<!-- Filter menu -->
			<div class="filters" id="filters">

				<div class="nav-btn" id="close-filters"><img src="../../assets/icons/menu/menu-close.png"></div>

				<div class="table-container">
					<div class="row">
						<div class="text">
							<p>Track is</p>
							<input type="text" class="filter-input" data-search="track" data-equality="=">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Artist is</p>
							<input type="text" class="filter-input" data-search="artist" data-equality="=">
						</div>
					</div>
				</div>

			</div>

			<?php include_once("../../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1><a href="../">Spotify Labelling</a></h1>

			<!-- Content -->
			<div class="module">

				<button class="icon filter" id="filter"><img src="../../assets/icons/filter.png"></button>

				<div class="table-container" id="tracks"></div>

			</div>

		</div>	<!-- .main-wrapper -->



		<?php
		// Load JavaScript
		include_once("../../assets/snippets/javascript-files.php");
		?>

		<script src="../../assets/js/option-popup.js"></script>

		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		// On load
		window.addEventListener("load", async () => {

			// Set filter click event
			document.getElementById("filter").addEventListener("click", () => {
				document.getElementById("filters").classList.toggle("open");
			});

			// Set close filter click event
			document.getElementById("close-filters").addEventListener("click", () => {
				document.getElementById("filters").classList.toggle("open");
			});

			// Load tracks
			document.getElementById("tracks").innnerHTML = "Loading...";
			const res = await Api.get.tracks();
			if(res && res.code && (res.code < 200 || res.code > 299)) {
				Popup.show(res.error, "error", 5000);
			}
			console.log(res);
			Api.show.tracks(res);

		});

		</script>

	</body>
</html>