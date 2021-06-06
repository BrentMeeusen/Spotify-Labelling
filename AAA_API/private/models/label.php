<?php

class Label extends Table {


	// Initialise variables
	public static int $id;
	public static string $publicID;

	public static string $name;
	public static bool $isPublic;





	/**
	 * Label constructor
	 * 
	 * @param		string		Label name
	 * @param		bool		Whether the label is public or not
	 */
	public function __construct(string $publicID, string $creator, string $name, bool $isPublic) {

		$this->name = $name;
		$this->isPublic = $isPublic;

	}





	/**
	 * Label constructor when the data comes from the database
	 * 
	 * @param		array		An associative array with the database values
	 * @return		Label		The created user
	 */
	public static function construct(array $values) : Label {

		$label = new Label($values["PublicID"], $values["Creator"], $values["Name"], $values["IsPublic"]);
		return $label;

	}










	/**
	 * Create the user with the given values
	 * 
	 * @param		string		
	 * @param		array		The values to create the user with
	 * @return		Label		The user that was created
	 */
	public static function createLabel(array $values) : Label {

		// Create a user object
		$user = new User(self::generateRandomID("USERS"), $values["FirstName"], $values["LastName"], $values["Username"], $values["Password"], $values["EmailAddress"], 1);


		// Check for duplicate values that should be unique (username, email address)
		$dupes = $user->hasDuplicates();
		if($dupes !== FALSE) {
			ApiResponse::httpResponse(400, ["error" => "There already exists " . $dupes["key"] . " with the value \"" . $dupes["value"] . "\"."]);
		}


		// Prepare SQL statement
		$stmt = self::prepare("INSERT INTO USERS (PublicID, FirstName, LastName, Username, EmailAddress, Password, AccountStatus) 
		VALUES ( ?, ?, ?, ?, ?, ?, ? );");

		// Sanitize input and create password hash
		$user->sanitizeInputs();
		$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		
		// Insert input into SQL statement
		$stmt->bind_param("ssssssi", $user->publicID, $user->firstName, $user->lastName, $user->username, $user->emailAddress, $user->password, $user->accountStatus);

		// Execute SQL statement
		self::execute($stmt);

		// Send an email to the user to verify their account
		$link = "http://spotify-labelling.21webb.nl/verify-account?id=" . $user->publicID . "&email=" . $user->emailAddress;

		$subject = "Verify Your Account";
		
		$body = "<html><head></head><body>";
		$body .= "<h2>Verify your account</h2><p>Click <a href='$link'>here</a> to verify your account.</p><p>If the link not works, paste the following in your browser: <a href='$link'>$link</a></p>";
		$body .= "</body></html>";

		$headers = "Return-Path: Spotify Labelling <no-reply@21webb.nl\r\n" . 
				"From: Spotify Labelling <no-reply@21webb.nl>\r\n" .
				"Organization: Spotify Labelling\r\n" . 
				"MIME-Version: 1.0\r\n" . 
				"Content-type: text/html; charset: utf8\r\n" . 
				"X-Priority: 3\r\n" . 
				"X-Mailer: PHP" . phpversion() ." \r\n";

		@mail($user->emailAddress, $subject, $body, $headers);

		// Return the result
		return $user;

	}










	/**
	 * Finds the label by name
	 * 
	 * @param		string		The name of the label
	 * @return		Label		If it was found
	 * @return		null		If no label was found
	 */
	public static function findByName(string $name) : ?Label {

		$stmt = self::prepare("SELECT * FROM LABELS WHERE Name = ?;");
		$name = self::sanitizeArray([$name])[0];
		$stmt->bind_param("s", $name);
		$res = self::getResults($stmt);

		// If no user is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found label as an object
		return Label::construct($res[0]);

	}


}

?>