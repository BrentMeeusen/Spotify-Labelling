<?php

$ALLOWED_METHOD = "GET";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");





// If ID is set
if(isset($_GET["id"])) {
	if(!isset($payload->rights->labels->find->id) || $payload->rights->labels->find->id !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get labels by ID."]);
	}
	$res = Label::findByPublicID(intval($_GET["id"]));
}

// If available is set
else if(isset($_GET["available"])) {
	if(!isset($payload->rights->labels->find->available) || $payload->rights->labels->find->available !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get all available."]);
	}
	$res = Label::findAvailable(intval($_GET["available"]));
}





// If there's no result, return an error
if($res === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The requested user could not be found."]);
}

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "User found.", "data" => $res ]);

?>