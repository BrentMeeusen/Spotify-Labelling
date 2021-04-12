<?php

class User {

	// Initialise variables
	private static mysqli $conn;

	private int $id;
	private string $firstName;
	private string $lastName;
	private string $emailAddress;

	private string $username;
	private string $password;

	private int $accountStatus;
	private string $accountStatusText;




	/**
	 * Sets the account status, both the integer and the text value
	 * 
	 * @param	int		The account status
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
		return ["status" => $status, "statusText" => $text];

	}





	/**
	 * User constructor
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
	public static function createUser(array $values) : bool {

		// Set variables
		$firstName = $values["FirstName"];
		$lastName = $values["LastName"];
		$username = $values["Username"];
		$password = $values["Password"];
		$emailAddress = $values["EmailAddress"];
		$status = self::setAccountStatus(1);


		// TODO: check for duplicate values that should be unique (username, email address)


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
		$firstName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $firstName))));
		$lastName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $lastName))));
		$username = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $username))));
		$emailAddress = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $emailAddress))));
		$password = password_hash($password, PASSWORD_DEFAULT);
		

		// Insert input into SQL statement
		$stmt->bind_param("sssssi", $firstName, $lastName, $username, $emailAddress, $password, $status["status"]);


		// Execute SQL statement and return the result
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst registering." ]);
		}

		return $res;

	}


}




?>