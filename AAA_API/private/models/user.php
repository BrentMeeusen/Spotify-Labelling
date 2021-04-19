<?php

class User extends Table {

	// Initialise variables
	public int $id;
	public string $firstName;
	public string $lastName;
	public string $emailAddress;

	public string $username;
	public string $password;

	public int $accountStatus;
	public string $accountStatusText;





	/**
	 * User constructor
	 * 
	 * @param	string	First name
	 * @param	string	Last name
	 * @param	string	Username
	 * @param	string	Password
	 * @param	string	Email address
	 * @param	int		Account status
	 */
	public function __construct(string $firstName, string $lastName, string $username, string $password, string $emailAddress, int $accountStatus) {

		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->username = $username;
		$this->password = $password;
		$this->emailAddress = $emailAddress;

		$status = $this->setAccountStatus($accountStatus);
		$this->accountStatus = $status["status"];
		$this->accountStatusText = $status["status-text"];

	}





	/**
	 * User constructor when the data comes from the database
	 * 
	 * @param	array	An associative array with the database values
	 * @return	User	The created user
	 */
	public static function construct(array $values) : User {

		$user = new User($values["FirstName"], $values["LastName"], $values["Username"], $values["Password"], $values["EmailAddress"], $values["AccountStatus"]);
		$user->id = $values["ID"];
		return $user;

	}









	
	/**
	 * Checks the user for any duplicate values in the database
	 * 
	 * @param	int		User ID that may not be unique (because it's this entry)
	 * @return	bool	False if no errors are found
	 * @return	array	[key => Property, value => Duplicate value]
	 */
	private function hasDuplicates(int $userID = NULL) {

		// Find user by username		=> results that's not this? true
		$res = self::findByUsername($this->username);
		if($res !== NULL && $res->id !== $userID) { 
			return ["key" => "a username", "value" => $res->username];
		}

		// Find user by email address	=> results that's not this? true
		$res = self::findByEmailAddress($this->emailAddress);
		if($res !== NULL && $res->id !== $userID) {
			return ["key" => "an email address", "value" => $res->emailAddress];
		}

		// Both no results? false
		return FALSE;

	}










	/**
	 * Sets the account status, both the integer and the text value
	 * 
	 * @param	int		The account status
	 * @return	array	The account status in integer and text form
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
	 * Sanitizes the inputs
	 */
	private function sanitizeInputs() : void {

		$this->firstName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->firstName))));
		$this->lastName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->lastName))));
		$this->username = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->username))));
		$this->emailAddress = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->emailAddress))));

	}










	/**
	 * Creates the payload given on the values in the user
	 * 
	 * @return	array	Associative array of what a user can do
	 */
	public function createPayload() : array {

		// Every user is allowed to find all users, but not to find by ID, by email, or by username
		$users = ["find" => ["all" => TRUE, "id" => FALSE, "emailAddress" => FALSE, "username" => FALSE]];

		return [
			"user" => ["id" => $this->id, "firstname" => $this->firstName, "lastname" => $this->lastName, "emailAddress" => $this->emailAddress, "username" => $this->username, "accountStatus" => $this->accountStatus, "accountStatusText" => $this->accountStatusText],
			"rights" => ["users" => $users]
		];

	}















	/**
	 * Logs the user in 
	 * 
	 * @param	string	Either username or email address
	 * @param	string	User password
	 * @return	User	The user found with the given credentials
	 */
	public static function login(string $identifier, string $password) : User {

		// Find the user by username and by email if necessary
		$user = self::findByUsername($identifier);
		$user = ($user ? $user : self::findByEmailAddress($identifier));

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
	 * Create the user with the given values
	 * 
	 * @param		array		The values to create the user with
	 * @return		User		The user that was created
	 */
	public static function createUser(array $values) : User {

		// Create a user object
		$user = new User($values["FirstName"], $values["LastName"], $values["Username"], $values["Password"], $values["EmailAddress"], 1);


		// Check for duplicate values that should be unique (username, email address)
		$dupes = $user->hasDuplicates();
		if($dupes !== FALSE) {
			ApiResponse::httpResponse(400, ["error" => "There already exists " . $dupes["key"] . " with the value \"" . $dupes["value"] . "\"."]);
		}


		// Prepare SQL statement
		$stmt = self::prepare("INSERT INTO USERS (FirstName, LastName, Username, EmailAddress, Password, AccountStatus) 
		VALUES ( ?, ?, ?, ?, ?, ? );");

		// Sanitize input and create password hash
		$user->sanitizeInputs();
		$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		
		// Insert input into SQL statement
		$stmt->bind_param("sssssi", $user->firstName, $user->lastName, $user->username, $user->emailAddress, $user->password, $user->accountStatus);

		// Execute SQL statement and return the result
		self::execute($stmt);
		return $user;

	}





	/**
	 * Updates the user with the given ID
	 * 
	 * @param	int		ID of the user to update
	 * @param	array	User object to update
	 * @return	User	The updated user
	 */
	public static function updateUser(int $id, array $values) : User {

		// Get the current user
		$user = self::findByID($id);
		if($user === NULL) {
			ApiResponse::httpResponse(404, ["error" => "The requested user was not found."]);
		}


		// Update its values with the newest values
		foreach($values as $key => $value) {
			$user->{lcfirst($key)} = $value;
		}

		// Check for duplicate values that should be unique BUT don't error on the current user
		$dupes = $user->hasDuplicates($id);
		if($dupes !== FALSE) {
			ApiResponse::httpResponse(400, ["error" => "There already exists " . $dupes["key"] . " with the value \"" . $dupes["value"] . "\"."]);
		}
		
		
		
		// Prepare SQL statement
		$stmt = self::prepare("UPDATE USERS SET FirstName = ?, LastName = ?, Username = ?, EmailAddress = ?, Password = ?, AccountStatus = ? WHERE ID = ?;");

		// Sanitize input
		$user->sanitizeInputs();

		// Hash password if it is updated
		if(array_key_exists("Password", $values)) {
			$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		}

		// Insert input into SQL statement
		$stmt->bind_param("sssssii", $user->firstName, $user->lastName, $user->username, $user->emailAddress, $user->password, $user->accountStatus, $id);

		// Execute SQL statement and return the result
		self::execute($stmt);
		return $user;


	}










	/**
	 * Get all the users
	 * 
	 * @return	array	All users as User objects
	 */
	public static function findAll() : array {

		$stmt = self::prepare("SELECT * FROM USERS;");
		$res = self::getResults($stmt);

		// Return an array of Users
		$users = [];
		foreach($res as $user) {
			array_push($users, User::construct($user));
		}
		return $users;
	}





	/**
	 * Get all the users with the given ID
	 * 
	 * @param	int		The user ID to search for
	 * @return	null	If the user was not found
	 * @return	User	The user that was found
	 */
	public static function findByID(int $userID) : ?User {

		$stmt = self::prepare("SELECT * FROM USERS WHERE ID = ?;");
		$userID = self::sanitizeArray([$userID])[0];
		$stmt->bind_param("s", $userID);
		$res = self::getResults($stmt);

		// If no user is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found user as an object
		return User::construct($res[0]);

	}





	/**
	 * Get all the users by the given username
	 * 
	 * @param	string	The username to search for
	 * @return	null	If the user was not found
	 * @return	User	The user that was found
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
	 * @param	string	The email address to search for
	 * @return	null	If the user was not found
	 * @param	User	The user that was found
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