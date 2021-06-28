<?php

// WARNING: HARDCODED
$VARIABLES = json_decode(file_get_contents("../../../../SpotifyLabelling.json"));
$url = $VARIABLES->BASE->API . "api/v1/users/verify/" . $_GET["id"] . "/" . $_GET["email"];
$res = json_decode(@file_get_contents($url, false, stream_context_create(["http" => ["ignore_errors" => true]])));

if($res === NULL) {
	@$res->code = 400;
	$res->message = "Something went wrong whilst verifying the account.";
}

header("Location: ../assets/php/redirect.php?code=" . $res->code . "&message=" . (isset($res->message) ? $res->message : $res->error) . "&redirect=");

?>

