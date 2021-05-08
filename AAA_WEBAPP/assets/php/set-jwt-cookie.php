<?php

if(!empty($_GET["jwt"]) && $_GET["jwt"] != "undefined") {
	
	// Set the token in the session and create a cookie for it
	$_SESSION["jwt"] = $_GET["jwt"];
	setcookie("jwt", "", 1, "/");
	setcookie("jwt", $_GET["jwt"], time() + (60 * 60), "/", "", FALSE, TRUE);

}

?>