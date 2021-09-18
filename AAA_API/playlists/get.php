<?php

// Get the playlists
$res = IPlaylist::findByCreator(strval($userID));

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "Label(s) found.", "data" => ($res === NULL ? [] : $res) ]);

?>