<?php

// If ID is set
if(isset($labelID)) {
	if(!isset($payload->rights->label->find->id) || $payload->rights->label->find->id !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "You are not allowed to get labels by ID."]);
	}
	$res = Label::findByPublicID(strval($labelID));
}

// If available is set
else if(isset($userID)) {
	if(!isset($payload->rights->label->find->available) || $payload->rights->label->find->available !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "You are not allowed to get all available."]);
	}
	$res = Label::findAvailable(strval($userID));
}





// If there's no result, return an error
if($res === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The requested label could not be found."]);
}

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "Label(s) found.", "data" => $res ]);

?>