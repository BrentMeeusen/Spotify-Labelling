<?php

// WARNING: HARDCODED LOCATION; CHANGE TO "http://spotify-labelling-api.21webb.nl/api/v1/users/verify/"
$url = "http://localhost/Spotify%20Labelling/AAA_API/api/v1/users/verify/" . $_GET["id"] . "/" . $_GET["email"];
$res = json_decode(@file_get_contents($url, false, stream_context_create(["http" => ["ignore_errors" => true]])));

if($res === NULL) {
	@$res->code = 400;
	$res->message = "Something went wrong whilst verifying the account.";
}

header("Location: ../assets/php/redirect.php?code=" . $res->code . "&message=" . (isset($res->message) ? $res->message : $res->error) . "&redirect=");

?>

