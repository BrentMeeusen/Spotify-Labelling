<?php

// If the playlist isn't found, return an error
$playlist = IPlaylist::findById($id);
if($playlist === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find the playlist."]);
}



// Set values of the payload
$newValues = [];
foreach($values as $key => $value) {
	if(!empty($value)) {
		$newValues[$key] = $value;
	}
}

// Update the user
$res = IPlaylist::update($playlist, $newValues);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The playlist has been successfully updated.", "data" => $res]);

?>