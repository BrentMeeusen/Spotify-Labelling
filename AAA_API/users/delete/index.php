<?php

$ALLOWED_METHOD = "DELETE";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If the ID is set, update ID
if(isset($_GET["id"])) {

	// Check whether the current user (JWT) is allowed to update another user (ID)
	if(!isset($payload->rights->users->delete) || $payload->rights->users->delete !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to delete someone else's account."]);
	}
	$userID = $_GET["id"];

	$prefix = "The";

}

// If the ID is not set, update self
else {

	// If the payload doesn't contain "user.id", return an error
	if(!isset($payload->user->id) || !isset($payload->rights->user->delete) || $payload->rights->user->delete !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to delete your account."]);
	}
	$userID = $payload->user->id;

	$prefix = "Your";

}



// If the user isn't found, return an error
$user = User::findByPublicID($userID);
if($user === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The requested user was not found."]);
}



// Update the user
$res = User::deleteUser($userID);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "$prefix account has been successfully deleted.", "data" => $res]);


?>