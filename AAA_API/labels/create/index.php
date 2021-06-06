<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Check whether the current user (JWT) is allowed to create a label
if(!isset($payload->rights->labels->create) || $payload->rights->labels->create !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to create a label."]);
}

// If a label already exists, return an error
if(Label::findByName($body["Name"]) !== NULL) {
	ApiResonse::httpResponse(400, ["error" => "You already have a label with the name \"" . $body["Name"] . "\"."]);
}

// Create the label
$res = Label::createLabel($payload->user->publicID, $body["Name"]);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "$prefix account has been successfully deleted.", "data" => $res]);


?>