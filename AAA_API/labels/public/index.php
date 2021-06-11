<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Get the ID and new state
$labelID = $_GET["id"];
$newState = (isset($_GET["state"]) ? "private" : "public");

// Check whether the current user (JWT) is allowed to make a label public/private
if(!isset($payload->rights->label->public) || $payload->rights->label->public != TRUE) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to make a label $newState."]);
}



// If the label isn't found, return an error
$label = Label::findByPublicID($labelID);
if($label === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find the label."]);
}



// Set values of the payload
$values = ["IsPublic" => ($newState === "private" ? 0 : 1)];

// Update the user
$res = Label::update($label, $values);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The label has been successfully made $newState.", "data" => $res]);


?>