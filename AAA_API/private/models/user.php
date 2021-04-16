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
		$user->password = "";
		$user->id = $values["ID"];
		return $user;

	}




	




	
	/**
	 * Checks the user for any duplicate values in the database
	 * 
	 * @return	bool	False if no errors are found
	 * @return	array	[key => Property, value => Duplicate value]
	 */
	private function hasDuplicates() {

		// TODO

		// Find user by username		=> results? true
		// Find user by email address	=> results? true
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
	 * Set the connection for the user object
	 * 
	 * @param		mysqli		The connection with the database
	 */
	public static function setConnection(mysqli $conn) {
		self::$conn = $conn;
	}










	/**
	 * Create the user with the given values
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
		$query = "INSERT INTO USERS (FirstName, LastName, Username, EmailAddress, Password, AccountStatus) 
					VALUES ( ?, ?, ?, ?, ?, ? );";
		$stmt = self::$conn->prepare($query);

		// If something went wrong whilst preparing, throw an error
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst preparing the registration statement." ]);
			exit();
		}

		
		// Sanitize input and create password hash
		$user->sanitizeInputs();
		$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		

		// Insert input into SQL statement
		$stmt->bind_param("sssssi", $user->firstName, $user->lastName, $user->username, $user->emailAddress, $user->password, $user->accountStatus);


		// Execute SQL statement and return the result
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst registering." ]);
		}

		return $user;

	}










	/**
	 * Get all the users
	 * 
	 * @return	array	All users as User objects
	 */
	public static function getAll() : array {

		// Prepare the statement
		$stmt = self::$conn->prepare("SELECT * FROM USERS;");

		// Run the query and get the result if nothing went wrong
		$stmt->execute();
		
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst requesting the user."]);
		}
		$res = $stmt->get_result();

		// If there are no rows, return a 404
		if($res->num_rows === 0) {
			ApiResponse::httpResponse(404, ["error" => "No users were found."]);
		}

		// Create all users
		$dbUsers = $res->fetch_all(1);

		$users = [];
		foreach($dbUsers as $user) {
			array_push($users, User::construct($user));
		}

		// Return the user array
		return $users;

	}





	/**
	 * Get all the users with the given ID
	 * 
	 * @param	int		The user ID to search for
	 * @return	null	If the user was not found
	 * @return	User	The user that was found
	 */
	public static function getByID(int $userID) : ?User {

		// Prepare the statement
		$stmt = self::$conn->prepare("SELECT * FROM USERS WHERE ID = ?;");

		// Sanitize the user ID that is requested
		$userID = self::sanitizeArray([$userID])[0];

		// Insert the ID into the statement
		$stmt->bind_param("i", $userID);

		// Run the query and get the result if nothing went wrong
		$stmt->execute();
		
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst requesting the user."]);
		}
		$res = $stmt->get_result();

		// If there are no rows, return a 404
		if($res->num_rows === 0) {
			return NULL;
		}

		// Create a user
		$user = User::construct($res->fetch_assoc());

		// Return the user
		return $user;

	}





	/**
	 * Get all the users by the given username
	 * 
	 * @param	string	The username to search for
	 * @return	null	If the user was not found
	 * @return	User	The user that was found
	 */
	public static function getByUsername(string $username) : ?User {

		// Prepare the statement
		$stmt = self::$conn->prepare("SELECT * FROM USERS WHERE Username = ?;");

		// Sanitize the user ID that is requested
		$username = self::sanitizeArray([$username])[0];

		// Insert the ID into the statement
		$stmt->bind_param("s", $username);

		// Run the query and get the result if nothing went wrong
		$stmt->execute();
		
		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst requesting the user."]);
		}
		$res = $stmt->get_result();

		// If there are no rows, return a 404
		if($res->num_rows === 0) {
			return NULL;
		}

		// Create a user
		$user = User::construct($res->fetch_assoc());

		// Return the user
		return $user;

	}





	/**
	 * Get all the users by the given email address
	 * 
	 * @param	string	The email address to search for
	 * @return	null	If the user was not found
	 * @param	User	The user that was found
	 */
	public static function getByEmailAddress(string $emailAddress) : ?User {

		$stmt = Table::prepare("SELECT * FROM USERS WHERE EmailAddress = ?;");
		$email = Table::sanitizeArray([$emailAddress])[0];
		$stmt->bind_param("s", $email);
		$res = Table::getResults($stmt);

		// If no user is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Return the found user in an object
		return User::construct($res[0]);
		
	}


}




?>