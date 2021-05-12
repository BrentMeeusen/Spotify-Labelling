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
		<link rel="stylesheet" type="text/css" href="../assets/css/general.css">
		<link rel="stylesheet" type="text/css" href="../assets/css/dashboard.css">
	</head>


	<body>

		<div class="main-wrapper">

			<!-- Popup -->
			<div class="popup" id="popup">
				<p id="popup-text"></p>
			</div>

			<!-- Menu -->
			<div class="nav-btn" id="nav-open">
				<img src="../assets/icons/menu/menu-open.png">
			</div>

			<?php include_once("../assets/snippets/navigation.php"); ?>

			<!-- Title -->
			<h1><a href="">Spotify Labelling</a></h1>



			<!-- Content -->
			<div class="module text">

				<p>An extensive explanation about how it works.</p>

				<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit non eveniet eos dolores laborum sed molestiae sint maiores sequi! Dolorum accusantium sapiente quisquam consequatur voluptates distinctio pariatur neque ab facere, libero voluptatem saepe totam nihil dicta ea maxime incidunt quo. Amet unde placeat, illum blanditiis perspiciatis quisquam. Quam, labore sequi.</p>
				<p>Fugiat reiciendis dicta iusto saepe sapiente nemo earum veniam ea rerum recusandae, maxime facere culpa minima eos facilis cupiditate. Qui consequatur excepturi provident accusamus, fugiat error dolorum rem tenetur at tempora aspernatur maxime. Rem explicabo commodi ab placeat quam vitae quod illum ratione perspiciatis nostrum repellendus, quasi est corporis temporibus?</p>
				<p>Ipsa consequuntur dignissimos doloribus temporibus, eius nobis! Blanditiis placeat aliquam rerum eveniet praesentium labore veniam quaerat. Eum, reprehenderit sapiente est veritatis corporis nesciunt voluptatibus! Numquam fugiat ipsam aut ipsa? Quo saepe maiores quisquam, illo dolores impedit ut nisi dolorem sunt sint voluptatibus consectetur alias, similique necessitatibus sapiente ea veniam! Recusandae?</p>
				<p>Voluptatibus vitae laudantium tenetur ab adipisci porro dicta molestiae repudiandae. Tenetur reprehenderit ex, sint in vitae dicta numquam illo et nihil non, deleniti autem? Voluptatem fugit animi error minus voluptatum eaque, ea molestias quia modi vel nisi asperiores accusamus assumenda illum. Ex odit qui tempore placeat recusandae sequi, pariatur magnam.</p>
				<p>Illum modi excepturi voluptatem ut quaerat minus mollitia inventore repudiandae, ratione illo molestiae ipsum nam, facere odio laudantium, laboriosam suscipit. Illo, et! Illo, repellat earum porro beatae voluptates accusantium reiciendis magni. Deleniti ad sequi, hic quidem dicta est quo quibusdam repellat dolorum optio quisquam fugit dolor enim, vel, ratione earum!</p>

			</div>	<!-- .module -->
			
		</div>	<!-- .main-wrapper -->



		<?php
		// Load JavaScript
		include_once("../assets/snippets/javascript-files.php");
		?>

		<script>

		// Protect the page
		PageProtect.protect({ verifiedLevel: 2 });

		</script>

	</body>
</html>