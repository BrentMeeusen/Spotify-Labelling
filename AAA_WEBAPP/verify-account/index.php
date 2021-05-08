<?php

// WARNING: HARDCODED LOCATION; CHANGE TO "http://spotify-labelling-api.21webb.nl/api/v1/users/verify"
$url = "http://localhost/Spotify%20Labelling/AAA_API/api/v1/users/verify/" . $_GET["id"] . "/" . $_GET["email"];
$res = json_decode(file_get_contents($url));
header("Location: ../assets/php/redirect.php?code=" . $res->code . "&message=" . $res->message . "&redirect=");

?>

