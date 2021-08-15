<?php

$ALLOWED_METHOD = "DELETE";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");

// Remove the track/user link
$track = ITrack::findBySpotifyId($_GET["id"]);
$track->removeUser($payload->user->id);

// Return result
ApiResponse::httpResponse(200, [ "message" => "Successfully removed song." ]);


?>