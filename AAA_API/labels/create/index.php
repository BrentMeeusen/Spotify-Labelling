<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// Check whether the current user (JWT) is allowed to create a label
if(!isset($payload->rights->label->create) || $payload->rights->label->create !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to create a label."]);
}

// If a label already exists, return an error
if(Label::findByName($body["Name"]) !== NULL) {
	ApiResponse::httpResponse(400, ["error" => "You already have a label with the name \"" . $body["Name"] . "\"."]);
}



// Set values of the payload
$values = ["Name" => NULL, "IsPublic" => FALSE];
foreach($body as $key => $value) {
	$values[$key] = $value;
}

// Create the label
$res = Label::createLabel($payload->user->id, $values);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The label was successfully created.", "data" => $res]);


?>