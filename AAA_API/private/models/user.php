<?php

class User extends Table {


	// Initialise variables
	public int $id;
	public string $publicID;

	public string $firstName;
	public string $lastName;
	public string $emailAddress;

	public string $username;
	public string $password;

	public int $accountStatus;
	public string $accountStatusText;

	public ?string $accessToken;





	/**
	 * User constructor
	 * 
	 * @param		string		Public ID
	 * @param		string		First name
	 * @param		string		Last name
	 * @param		string		Username
	 * @param		string		Password
	 * @param		string		Email address
	 * @param		int			Account status
	 * @param		string		Access token
	 */
	public function __construct(string $publicID, string $firstName, string $lastName, string $username, string $password, string $emailAddress, int $accountStatus, ?string $accessToken = NULL) {

		$this->publicID = $publicID;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->username = $username;
		$this->password = $password;
		$this->emailAddress = $emailAddress;
		$this->accessToken = $accessToken;

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

		$user = new User($values["PublicID"], $values["FirstName"], $values["LastName"], $values["Username"], $values["Password"], $values["EmailAddress"], $values["AccountStatus"], $values["AccessToken"]);
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

		// Find user by username		=> results that's not this? true
		$res = self::findByUsername($this->username);
		if($res !== NULL && $res->publicID !== $this->publicID) { 
			return ["key" => "a username", "value" => $res->username];
		}

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
	 * Sanitizes the inputs
	 */
	public function sanitizeInputs() : void {

		$this->firstName = trim(mysqli_real_escape_string(self::$conn, $this->firstName));
		$this->lastName = trim(mysqli_real_escape_string(self::$conn, $this->lastName));
		$this->username = trim(mysqli_real_escape_string(self::$conn, $this->username));
		$this->emailAddress = trim(mysqli_real_escape_string(self::$conn, $this->emailAddress));

	}










	/**
	 * Creates the payload given on the values in the user
	 * 
	 * @return		array		Associative array of what a user can do
	 */
	public function createPayload() : array {

		// Every user is allowed to find all users, but not to find by ID, by email, or by username, nor can he update or delete other accounts
		$users = ["find" => ["all" => TRUE, "id" => FALSE, "emailAddress" => FALSE, "username" => FALSE], "update" => FALSE, "delete" => FALSE];

		// Every user is allowed to update and delete himself
		$user = ["update" => TRUE, "delete" => TRUE];

		// Every user can create, read, update, delete his own labels
		$label = ["find" => ["available" => TRUE, "id" => FALSE], "create" => TRUE, "update" => TRUE, "delete" => TRUE, "public" => FALSE];

		// Set the current payload
		$payload = [
			"user" => ["id" => $this->publicID, "firstname" => $this->firstName, "lastname" => $this->lastName, "emailAddress" => $this->emailAddress, "username" => $this->username, "accountStatus" => $this->accountStatus, "accountStatusText" => $this->accountStatusText, "accessToken" => $this->accessToken],
			"rights" => ["users" => $users, "user" => $user, "label" => $label]
		];

		// Get all the additional rights of the user
		$stmt = self::prepare("SELECT r.Name, r.Value FROM RIGHTS_TO_USERS AS rtu JOIN RIGHTS AS r ON r.ID = rtu.RightID WHERE rtu.UserID = " . $this->publicID . ";");
		$data = self::getResults($stmt);

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
	 * @param		string		Either username or email address
	 * @param		string		User password
	 * @return		User		The user found with the given credentials
	 */
	public static function login(string $identifier, string $password) : User {

		// Find the user by username and by email if necessary
		$user = self::findByUsername($identifier);
		$user = ($user !== NULL ? $user : self::findByEmailAddress($identifier));

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
	 * Sends a verification email
	 * 
	 * @param		string		The public ID of the user
	 * @param		string		The email address of the user
	 */
	public static function sendVerificationEmail(string $id, string $email) : void {

		$link = "http://spotify-labelling.21webb.nl/verify-account?id=" . $id . "&email=" . $email;

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

		@mail($email, $subject, $body, $headers);

	} 





	/**
	 * Create the user with the given values
	 * 
	 * @param		array		The values to create the user with
	 * @return		User		The user that was created
	 */
	public static function create(array $values) : User {

		// Create a user object
		$user = new User(Database::generateRandomID("USERS"), $values["FirstName"], $values["LastName"], $values["Username"], $values["Password"], $values["EmailAddress"], 1);

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
		$stmt = self::prepare("UPDATE USERS SET FirstName = ?, LastName = ?, Username = ?, EmailAddress = ?, Password = ?, AccountStatus = ?, AccessToken = ? WHERE PublicID = ?;");

		// Hash password if it is updated
		if(array_key_exists("Password", $values)) {
			$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		}

		// Insert input into SQL statement
		$stmt->bind_param("sssssisi", $user->firstName, $user->lastName, $user->username, $user->emailAddress, $user->password, $user->accountStatus, $user->accessToken, $user->publicID);

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
	 * Get all the users by the given username
	 * 
	 * @param		string		The username to search for
	 * @return		null		If the user was not found
	 * @return		User		The user that was found
	 */
	public static function findByUsername(string $username) : ?User {

		$stmt = self::prepare("SELECT * FROM USERS WHERE Username = ?;");
		$username = self::sanitizeArray([$username])[0];
		$stmt->bind_param("s", $username);
		$res = self::getResults($stmt);

		// If no user is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found user as an object
		return User::construct($res[0]);

	}





	/**
	 * Get all the users by the given email address
	 * 
	 * @param		string		The email address to search for
	 * @return		null		If the user was not found
	 * @param		User		The user that was found
	 */
	public static function findByEmailAddress(string $emailAddress) : ?User {

		$stmt = self::prepare("SELECT * FROM USERS WHERE EmailAddress = ?;");
		$email = self::sanitizeArray([$emailAddress])[0];
		$stmt->bind_param("s", $email);
		$res = self::getResults($stmt);

		// If no user is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found user as an object
		return User::construct($res[0]);

	}

}



?>