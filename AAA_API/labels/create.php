<?php

// Check whether the current user (JWT) is allowed to create a label
if(!isset($payload->rights->label->create) || $payload->rights->label->create !== TRUE) {
	ApiResponse::httpResponse(401, ["error" => "You are not allowed to create a label."]);
}

// If the label name is empty, return an error
if(!my_isset($name)) {
	ApiResponse::httpResponse(400, ["error" => "You have to give the label a name."]);
}

// If a label already exists, return an error
if(Label::findByName($name, $payload->user->id) !== NULL) {
	ApiResponse::httpResponse(400, ["error" => "You already have a label with the name \"$name\"."]);
}



// Set values of the payload
$newValues = ["Creator" => $payload->user->id];
foreach($body as $key => $value) {
	$newValues[$key] = $value;
}

// Create the label
$res = Label::create($newValues);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "The label was successfully created.", "data" => $res]);


?>