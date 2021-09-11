<?php

// Check whether the current user (JWT) is allowed to create a playlist
if(!isset($payload->rights->playlist->create) || $payload->rights->playlist->create !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to create a playlist."]);
}

// If the playlist name is empty, return an error
if(!my_isset($name)) {
	ApiResponse::httpResponse(400, ["error" => "You have to give the playlist a name."]);
}

// If a playlist already exists, return an error
if(Playlist::findByName($name, $payload->user->id) !== NULL) {
	ApiResponse::httpResponse(400, ["error" => "You already have a playlist with the name \"$name\"."]);
}



// Create the playlist
$res = Playlist::create(["Creator" => $payload->user->id, "Name" => $name]);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The playlist was successfully created.", "data" => $res]);


?>