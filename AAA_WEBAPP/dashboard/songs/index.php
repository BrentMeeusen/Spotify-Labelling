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
							<input type="text" class="filter-input" data-search="track">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Artist is</p>
							<input type="text" class="filter-input" data-search="artist">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Has label</p>
							<input type="text" class="filter-input" data-search="label">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>At least ? labels</p>
							<input type="number" class="filter-input" data-search="min-labels">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>At most ? labels</p>
							<input type="number" class="filter-input" data-search="max-labels">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Added before</p>
							<input type="date" class="filter-input" data-search="added-before">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Added after</p>
							<input type="date" class="filter-input" data-search="added-after">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Released before</p>
							<input type="date" class="filter-input" data-search="released-before">
						</div>
					</div>
					<div class="row">
						<div class="text">
							<p>Released after</p>
							<input type="date" class="filter-input" data-search="released-after">
						</div>
					</div>
				</div>

			</div>

			<?php include_once("../../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1><a href="../">Spotify Labelling</a></h1>

			<!-- Content -->
			<div class="module">

				<div class="controls">
					<button class="icon add" id="add-to-visible"><img src="../../assets/icons/add.png"></button>
					<button class="icon filter" id="filter"><img src="../../assets/icons/filter.png"></button>
				</div>

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

			// Set "add to all visible" click event
			document.getElementById("add-to-visible").addEventListener("click", async () => {

				const popup = new BigPopup("Choose labels", "api/v1/tracks/add-labels/", "POST", "add-labels-to-all");
				const labels = await Api.get.labels();

				let i = 1;
				for(const l of labels) {
					const el = Api.createElement("div", { innerHTML: l.name, classList: "add-label", value: l.publicID });
					el.setAttribute("name", "input");
					el.dataset.selected = "false";
					el.dataset.item = "label-" + i++;
					el.addEventListener("click", () => { el.dataset.selected = (el.dataset.selected === "true" ? "false" : "true"); });
					popup.addElement(el);
				}
				popup.show("Add to all visible", async () => { Api.show.tracks(await Api.get.tracks()); });
				HtmlJsForm.findById("add-labels-to-all").setValues({ tracks: Collection.filtered.map(t => t.id) });
				HtmlJsForm.findById("add-labels-to-all").addCallback(async () => { await Api.get.tracks(); Api.show.tracks(Collection.filter()); });

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