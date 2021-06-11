<?php

$ALLOWED_METHOD = "DELETE";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Get the ID
$labelID = $_GET["id"];

// Check whether the current user (JWT) is allowed to delete a label
if(!isset($payload->rights->label->delete) || $payload->rights->label->delete !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to delete this label."]);
}

// If the label isn't found, return an error
$label = Label::findByPublicID($labelID);
if($label === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The label you want to delete was not found."]);
}

// If deleter is not the creator of the label AND the deleter cannot delete any label, return an error
if($label->creator !== $payload->user->id && TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to delete this label."]);
}

// Delete the label
$res = Label::deleteLabel($labelID);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The label was successfully deleted.", "data" => $res]);


?>