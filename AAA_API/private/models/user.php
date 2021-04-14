<?php

class User {

	// Initialise variables
	public static mysqli $conn;

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

		$status = User::setAccountStatus($accountStatus);
		$this->accountStatus = $status["status"];
		$this->accountStatusText = $status["status-text"];

	}





	/**
	 * User constructor when the data comes from the database
	 * 
	 * @param	array	An associative array with the database values
	 */
	public static function construct(array $values) : User {

		$user = new User($values["FirstName"], $values["LastName"], $values["Username"], $values["Password"], $values["EmailAddress"], $values["AccountStatus"]);
		$user->password = "";
		$user->id = $values["ID"];
		return $user;

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
	 * Sanitizes the array
	 * 
	 * @param	array	All values to be sanitized
	 * @return	array	Sanitized array
	 */
	private static function sanitizeArray(array $inputs) : array {

		$sanitized = [];
		foreach($inputs as $input) {
			array_push($sanitized, htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $input)))));
		}
		return $sanitized;

	}





	/**
	 * Checks the user for any duplicate values
	 * 
	 * @return	bool	false if no errors are found
	 * @return	array	[key => Property, value => Duplicate value]
	 */
	private function hasDuplicates() : mixed {

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
	private static function setAccountStatus(int $status) : array {
		
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
	 * Set the connection for the user object
	 * 
	 * @param		mysqli		connection
	 */
	public static function setConnection(mysqli $conn) {
		self::$conn = $conn;
	}





	/**
	 * Create the user with the given values
	 * @param		array		the values to create the user with
	 * @return		bool		whether it was successful or not
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
	 * @return	array	of all users in User objects
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
	 * @param	int		the user ID to search for
	 * @return	User	if the user was found
	 * @return	null	if the user wasn't found
	 */
	public static function getByID(int $userID) : User {

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
			ApiResponse::httpResponse(404, ["error" => "The requested user could not be found"]);
		}

		// Create a user
		$user = User::construct($res->fetch_assoc());

		// Return the user
		return $user;

	}





	/**
	 * Get all the users by the given username
	 * 
	 * @param	string	the username to search for
	 */
	public static function getByUsername(string $username) : array {
		// TODO
	}





	/**
	 * Get all the users by the given email address
	 * 
	 * @param	string	the email address to search for
	 */
	public static function getByEmailAddress(string $emailAddress) : array {
		// TODO
	}


}




?>