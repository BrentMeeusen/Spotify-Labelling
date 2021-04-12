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
	 */
	private function setAccountStatus(int $status) : void {
		
		$this->accountStatus = $status;
		switch($status) {
			case 1:
				$accountStatusText = "Created";
				break;
			case 2:
				$accountStatusText = "Verified";
				break;
		}

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
	public function createUser(array $values) : bool {

		// Set variables
		$this->firstName = $values["FirstName"];
		$this->lastName = $values["LastName"];
		$this->username = $values["Username"];
		$this->password = $values["Password"];
		$this->emailAddress = $values["EmailAddress"];
		$this->setAccountStatus(1);


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
		$this->firstName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->firstName))));
		$this->lastName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->lastName))));
		$this->username = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->username))));
		$this->emailAddress = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->emailAddress))));
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		

		// Insert input into SQL statement
		$stmt->bind_param("sssssi", $this->firstName, $this->lastName, $this->username, $this->emailAddress, $this->password, $this->accountStatus);


		// Execute SQL statement and return the result
		$res = $stmt->execute();
		if($res === FALSE) {
			ApiResponse::httpResponse(500, [ "error" => "Something went wrong whilst registering." ]);
		}

		return $res;

	}


}




?>