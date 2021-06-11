<?php

$ALLOWED_METHOD = "DELETE";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If the ID is set, delete ID
if(isset($_GET["id"])) {

	// Check whether the current user (JWT) is allowed to delete another user (ID)
	if(!isset($payload->rights->users->delete) || $payload->rights->users->delete !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to delete someone else's account."]);
	}
	$userID = $_GET["id"];

	$prefix = "The";

}

// If the ID is not set, delete self
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
	ApiResponse::httpResponse(404, ["error" => "$prefix account was not found."]);
}


// If the password isn't correct, return an error
if(!password_verify($body["Password"], $user->password)) {
	ApiResponse::httpResponse(400, ["error" => "The given password is incorrect."]);
}



// Delete the user
$res = User::delete($user);

// Properly return the results
ApiResponse::httpResponse(200, ["message" => "$prefix account has been successfully deleted.", "data" => $res]);


?>