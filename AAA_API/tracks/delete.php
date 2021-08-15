<?php

// Remove the track/user link
$track = ITrack::findBySpotifyId($trackID);
$track->removeUser($payload->user->id);

// Return result
ApiResponse::httpResponse(200, [ "message" => "Successfully removed song." ]);


?>