<?php

$ALLOWED_METHOD = "GET";
$REQUIRE_TOKEN = TRUE;

include_once("../../private/include_all.php");



// If the payload doesn't contain "register", return an error
if(!isset($payload->rights->users->find)) {
	ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get users."]);
}



// Write a selector that chooses whether to get all users, get by ID, username, or email adddress (use GET properties)
User::setConnection(Database::connect());
if(isset($_GET["id"])) {
	if(!isset($payload->rights->users->find->id) || $payload->rights->users->find->id !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get users."]);
	}
	$res = User::findByPublicID(intval($_GET["id"]));
}

else if(isset($_GET["username"])) {
	if(!isset($payload->rights->users->find->username) || $payload->rights->users->find->username !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get users."]);
	}
	$res = User::findByUsername(strval($_GET["username"]));
}
else if(isset($_GET["email-address"])) {
	if(!isset($payload->rights->users->find->emailAddress) || $payload->rights->users->find->emailAddress !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get users."]);
	}
	$res = User::findByEmailAddress(strval($_GET["email-address"]));
}
else {
	if(!isset($payload->rights->users->find->all) || $payload->rights->users->find->all !== TRUE) {
		ApiResponse::httpResponse(401, ["error" => "The given JSON Web Token cannot be used to get users."]);
	}
	$res = User::findAll();
}

if($res === NULL) {
	ApiResponse::httpResponse(404, ["error" => "The requested user could not be found."]);
}

// Properly return the results
ApiResponse::httpResponse(200, [ "message" => "User found.", "data" => $res ]);

?>