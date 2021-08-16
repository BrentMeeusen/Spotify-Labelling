<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Get the ID
$labelID = $_GET["id"];

// Check whether the current user (JWT) is allowed to update a label
if(!isset($payload->rights->label->update) || $payload->rights->label->update !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to update a label."]);
}



// Check if inputs are set and not empty
if(setAndEmpty($body, "Name")) {
	ApiResponse::httpResponse(400, ["error" => "Not all required fields were filled in."]);
}



// If the label isn't found, return an error
$label = Label::findByPublicID($labelID);
if($label === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find the label."]);
}



// Set values of the payload
$values = [];
foreach($body as $key => $value) {
	if(!empty($value)) {
		$values[$key] = $value;
	}
}

// Update the user
$res = Label::update($label, $values);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The label has been successfully updated.", "data" => $res]);


?>