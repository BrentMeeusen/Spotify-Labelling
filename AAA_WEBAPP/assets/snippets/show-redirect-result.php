<?php
// If there is a message
if(isset($_SESSION["code"])) {

	// Get the message and type
	$message = (isset($_SESSION["message"]) ? $_SESSION["message"] : $_SESSION["error"]);
	$code = $_SESSION["code"];
	$type = ($code >= 200 && $code <= 299 ? "success" : "error");

	// Show the popup
?>

<script> Popup.show(<?php print("'$message', '$type', 5000"); ?>); </script>

<?php 
} 

// Remove the message
session_destroy(); 
?>