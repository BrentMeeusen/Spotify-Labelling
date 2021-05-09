<?php

$ALLOWED_METHOD = "POST";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If the ID is set, update ID
if(isset($_GET["id"])) {

	// Check whether the current user (JWT) is allowed to update another user (ID)
	if(!isset($payload->rights->users->update) || $payload->rights->users->update !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to update someone else's account."]);
	}
	$updateID = $_GET["id"];
	
	$prefix = "The";

}

// If the ID is not set, update self
else {

	// If the payload doesn't contain "user.id", return an error
	if(!isset($payload->user->id) || !isset($payload->rights->user->update) || $payload->rights->user->update !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to update your account."]);
	}
	$updateID = $payload->user->id;

	$prefix = "Your";

}



// If the user isn't found, return an error
$user = User::findByPublicID($updateID);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "We couldn't find " . strtolower($prefix) . "account."]);
}



// Set values of the payload
$values = [];
foreach($body as $key => $value) {
	if(!empty($value)) {
		$values[$key] = $value;
	}
}


// Update the user
$res = User::updateUser($updateID, $values);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "$prefix account has been successfully updated.", "data" => $res]);


?>