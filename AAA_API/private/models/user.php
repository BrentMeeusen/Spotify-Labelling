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
	public function __construct($firstName, $lastName, $username, $password, $emailAddress, $accountStatus) {

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
	 * Sanitizes the inputs
	 */
	private function sanitizeInputs() : void {

		$this->firstName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->firstName))));
		$this->lastName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->lastName))));
		$this->username = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->username))));
		$this->emailAddress = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->emailAddress))));

	}





	/**
	 * Checks the user for any duplicate values
	 * 
	 * @return	bool	true if any unwanted duplicates are found, false if not
	 */
	private function hasDuplicates() {

		// Find user by username		=> results? true
		// Find user by email address	=> results? true
		// Both no results? False

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


		// TODO: check for duplicate values that should be unique (username, email address)
		$user->hasDuplicates();


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
	 */
	public static function getAll() : array {

	}





	/**
	 * Get all the users by the given ID
	 * 
	 * @param	int		the user ID to search for
	 */
	public static function getByID(int $userID) : User {

	}





	/**
	 * Get all the users by the given username
	 * 
	 * @param	string	the username to search for
	 */
	public static function getByUsername(string $username) : array {

	}





	/**
	 * Get all the users by the given email address
	 * 
	 * @param	string	the email address to search for
	 */
	public static function getByEmailAddress(string $emailAddress) : array {

	}


}




?>