<?php

class User extends Database {


	// Initialise variables
	public int $id;
	public string $publicID;

	public string $emailAddress;
	public string $password;

	public int $accountStatus;
	public string $accountStatusText;

	public ?string $accessToken;
	public ?string $spotifyEmail;





	/**
	 * User constructor
	 * 
	 * @param		string		Public ID
	 * @param		string		Password
	 * @param		string		Email address
	 * @param		int			Account status
	 * @param		string		Access token
	 * @param		string		Spotify email address
	 */
	public function __construct(string $publicID, string $password, string $emailAddress, int $accountStatus, ?string $accessToken = NULL, $spotifyEmail = NULL) {

		$this->publicID = $publicID;
		$this->password = $password;
		$this->emailAddress = $emailAddress;
		$this->accessToken = $accessToken;
		$this->spotifyEmail = $spotifyEmail;

		$status = $this->setAccountStatus($accountStatus);
		$this->accountStatus = $status["status"];
		$this->accountStatusText = $status["status-text"];

	}





	/**
	 * User constructor when the data comes from the database
	 * 
	 * @param		array		An associative array with the database values
	 * @return		User		The created user
	 */
	public static function construct(array $values) : User {

		$user = new User($values["PublicID"], $values["Password"], $values["EmailAddress"], $values["AccountStatus"], $values["AccessToken"], $values["SpotifyEmail"]);
		$user->id = $values["ID"];
		return $user;

	}










	/**
	 * Checks the user for any duplicate values in the database
	 * 
	 * @return		bool		False if no errors are found
	 * @return		array		[key => Property, value => Duplicate value]
	 */
	public function hasDuplicates() {

		// Find user by email address	=> results that's not this? true
		$res = self::findByEmailAddress($this->emailAddress);
		if($res !== NULL && $res->publicID !== $this->publicID) {
			return ["key" => "an email address", "value" => $res->emailAddress];
		}

		// Both no results? false
		return FALSE;

	}










	/**
	 * Sets the account status, both the integer and the text value
	 * 
	 * @param		int			The account status
	 * @return		array		The account status in integer and text form
	 */
	private function setAccountStatus(int $status) : array {
		
		switch($status) {
			case 1:
				$text = "Created";
				break;
			case 2:
				$text = "Verified";
				break;
			default:
				$text = "Unknown";
				break;
		}
		return ["status" => $status, "status-text" => $text];

	}





	/**
	 * Verifies the user account by setting the account status
	 * 
	 * @return		User		The new user object
	 */
	public function verify() : User {
		return self::update($this, ["accountStatus" => 2]);
	}





	/**
	 * Sets the new password
	 * 
	 * @param		string		The new password
	 * @return		User		The updated user
	 */
	public function setPassword(string $newPassword) : User {
		return User::update($this, ["Password" => $newPassword]);
	}





	/**
	 * Sets the user Spotify email
	 * 
	 * @return		User		The upated user
	 */
	public function setSpotifyEmail() : User {
		return User::update($this, ["SpotifyEmail" => SpotifyApi::getEmailAddress()]);
	}










	/**
	 * Sanitizes the inputs
	 */
	public function sanitizeInputs() : void {
		$this->emailAddress = parent::sanitize($this->emailAddress);
	}










	/**
	 * Creates the payload given on the values in the user
	 * 
	 * @return		array		Associative array of what a user can do
	 */
	public function createPayload() : array {

		// Set the current payload
		$payload = [
			"user" => ["id" => $this->publicID, "emailAddress" => $this->emailAddress, "accountStatus" => $this->accountStatus, "accountStatusText" => $this->accountStatusText, "accessToken" => $this->accessToken, "spotifyEmail" => $this->spotifyEmail],
			"rights" => [
				// Rights for all users
				"users" => ["find" => ["all" => TRUE, "id" => FALSE, "emailAddress" => FALSE, "username" => FALSE], "update" => FALSE, "delete" => FALSE],
				// Rights for user of its own
				"user" => ["update" => TRUE, "delete" => TRUE],
				// Rights for all labels
				"label" => ["find" => ["available" => TRUE, "id" => FALSE], "create" => TRUE, "update" => TRUE, "delete" => TRUE],
				// Rights for all playlists
				"playlist" => ["create" => TRUE, "update" => TRUE, "delete" => TRUE]
			]
		];

		$data = self::find("SELECT r.Name, r.Value FROM RIGHTS_TO_USERS AS rtu JOIN RIGHTS AS r ON r.ID = rtu.RightID WHERE rtu.UserID = ?;", $this->publicID);

		// Loop over all data
		foreach($data as $row) {

			// Get the value to change
			$pos = &$payload["rights"];
			foreach(explode(".", $row["Name"]) as $cat) {
				if(!isset($pos[$cat])) { $pos[$cat] = []; }
				$pos = &$pos[$cat];
			}

			// Set the new value
			$pos = $row["Value"];

		}

		return $payload;

	}















	/**
	 * Logs the user in 
	 * 
	 * @param		string		Email address
	 * @param		string		User password
	 * @return		User		The user found with the given credentials
	 */
	public static function login(string $identifier, string $password) : User {

		// Find the user by email
		$user = self::findByEmailAddress($identifier);

		// If no user is found, throw an error
		if($user === NULL) {
			ApiResponse::httpResponse(400, ["error" => "Login credentials are incorrect."]);
		}

		// If a user is found, check whether the password is correct
		$passCheck = password_verify($password, $user->password);
		if($passCheck !== TRUE) {
			ApiResponse::httpResponse(400, ["error" => "Login credentials are incorrect."]);
		}

		// If the password is correct, return the user
		return $user;

	}










	/**
	 * Sends an email
	 * 
	 * @param		string		The email address to send it to
	 * @param		string		The subject of the email
	 * @param		string		The body of the email
	 */
	private static function sendMail(string $to, string $subject, string $email) : void {

		$body = "<html><head></head><body>$email</body></html>";
		$headers = "Return-Path: Spotify Labelling <no-reply@21webb.nl\r\n" . 
				"From: Spotify Labelling <no-reply@21webb.nl>\r\n" .
				"Organization: Spotify Labelling\r\n" . 
				"MIME-Version: 1.0\r\n" . 
				"Content-type: text/html; charset: utf8\r\n" . 
				"X-Priority: 3\r\n" . 
				"X-Mailer: PHP" . phpversion() ." \r\n";

		@mail($to, $subject, $body, $headers);

	}





	/**
	 * Sends an email to request a new password
	 */
	public function requestNewPassword() : void {

		$link = "http://spotify-labelling.21webb.nl/request-password?email=" . $this->emailAddress . "&id=" . $this->publicID;

		$body = "<h2>Forgot password</h2><p>Click <a href='$link'>here</a> to reset your password. If you did not request this, you can ignore this email.</p><p>Cannot click the link? Then paste the following URL in your browser: $link</p>";

		self::sendMail($this->emailAddress, "Spotify Labelling | Forgot password", $body);

	}





	 /**
	 * Sends a verification email
	 * 
	 * @param		string		The public ID of the user
	 * @param		string		The email address of the user
	 */
	public static function sendVerificationEmail(string $id, string $email) : void {

		$link = "http://spotify-labelling.21webb.nl/verify-account?id=" . $id . "&email=" . $email;
		
		$body = "<html><head></head><body>";
		$body .= "<h2>Verify your account</h2><p>In order to verify your account, please click <a href='$link'>here</a>.</p><p>If the link does not work, paste the following URL in your browser: $link</p>";
		$body .= "</body></html>";

		self::sendMail($email, "Spotify Labelling | Verify Your Account", $body);

	} 










	/**
	 * Create the user with the given values
	 * 
	 * @param		array		The values to create the user with
	 * @return		User		The user that was created
	 */
	public static function create(array $values) : User {

		// Create a user object
		$user = new User(Database::generateRandomID("USERS"), $values["Password"], $values["EmailAddress"], 1);

		// Check for duplicate values that should be unique (username, email address)
		$dupes = $user->hasDuplicates();
		if($dupes !== FALSE) {
			ApiResponse::httpResponse(400, ["error" => "There already exists " . $dupes["key"] . " with the value \"" . $dupes["value"] . "\"."]);
		}

		// Prepare SQL statement
		$stmt = self::prepare("INSERT INTO USERS (PublicID, EmailAddress, Password, AccountStatus) 
		VALUES ( ?, ?, ?, ? );");

		// Sanitize input and create password hash
		$user->sanitizeInputs();
		$user->password = password_hash($user->password, PASSWORD_DEFAULT);

		// Insert input into SQL statement
		$stmt->bind_param("sssi", $user->publicID, $user->emailAddress, $user->password, $user->accountStatus);

		// Execute SQL statement
		self::execute($stmt);

		// Send an email to the user to verify their account
		self::sendVerificationEmail($user->publicID, $user->emailAddress);

		// Return the result
		return $user;

	}





	/**
	 * Updates the user with the given ID
	 * 
	 * @param		User		The user to update
	 * @param		array		The new values in an associative array
	 * @return		User		The updated user
	 */
	public static function update($user, array $values) : User {

		// Check whether object is of type Label
		if(!($user instanceof User)) { throw new InvalidArgumentException; }

		// Prepare the update process
		$user = parent::prepareUpdate($user, $values);

		// Prepare SQL statement
		$stmt = self::prepare("UPDATE USERS SET EmailAddress = ?, Password = ?, AccountStatus = ?, AccessToken = ?, SpotifyEmail = ? WHERE PublicID = ?;");

		// Hash password if it is updated
		if(array_key_exists("Password", $values)) {
			$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		}

		// Insert input into SQL statement
		$stmt->bind_param("ssissi", $user->emailAddress, $user->password, $user->accountStatus, $user->accessToken, $user->spotifyEmail, $user->publicID);

		// Execute SQL statement and return the result
		self::execute($stmt);
		return $user;

	}





	/**
	 * Deletes the user with the given ID
	 * 
	 * @param		User		The user to delete
	 * @return		bool		Whether it was a success deleting
	 */
	public static function delete($user) : bool {

		// Check whether object is of type Label
		if(!($user instanceof User)) { throw new InvalidArgumentException; }

		// Get all tracks that the user has and delete them
		$ids = Database::find("SELECT TTU.TrackID AS trackID FROM TRACKS_TO_USERS AS TTU WHERE UserID = ?;", $user->publicID);

		foreach($ids as $id) {
			ITrack::findBySpotifyId($id->trackID)->removeUser($user->publicID);
		}
		
		// Delete the user
		return parent::deleteEntry($user, "USERS");

	}










	/**
	 * Get all the users
	 * 
	 * @return		array		All users as User objects
	 */
	public static function findAll() : array {

		// Return an array of Users
		$found = Database::find("SELECT * FROM USERS WHERE ID > ?;", -1);
		$users = [];
		foreach($found as $user) {
			array_push($users, User::construct((array) $user));
		}
		return $users;
	}





	/**
	 * Get all the users with the given ID
	 * 
	 * @param		string		The public user ID to search for
	 * @return		null		If the user was not found
	 * @return		User		The user that was found
	 */
	public static function findByPublicID(string $userID) : ?User {

		// If no user is found, return NULL
		$res = Database::find("SELECT * FROM USERS WHERE PublicID = ?;", $userID);
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found user as an object
		return User::construct((array) $res[0]);

	}





	/**
	 * Get all the users by the given email address
	 * 
	 * @param		string		The email address to search for
	 * @return		null		If the user was not found
	 * @param		User		The user that was found
	 */
	public static function findByEmailAddress(string $emailAddress) : ?User {

		// If no user is found, return NULL
		$res = Database::find("SELECT * FROM USERS WHERE EmailAddress = ?;", Database::sanitize($emailAddress));
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found user as an object
		return User::construct((array) $res[0]);

	}

}



?>