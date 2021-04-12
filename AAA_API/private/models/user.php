<?php

class User {

	// Initialise variables
	private mysqli $conn;

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
	public function __construct(mysqli $conn) {
		$this->conn = $conn;
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
		$query = "INSERT INTO USERS
			(FirstName, LastName, Username, EmailAddress, Password, AccountStatus)
			VALUES (':FirstName', ':LastName', ':Username', ':EmailAddress', ':Password', ':AccountStatus' );";
		$stmt = $this->conn->prepare($query);

		if($stmt === FALSE) {
			ApiResponse::httpResponse(500, [ "message" => "Something went wrong whilst preparing the registration statement." ]);
			exit();
		}

		// Sanitize input
		$this->firstName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string($this->conn, $this->firstName))));
		$this->lastName = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string($this->conn, $this->lastName))));
		$this->username = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string($this->conn, $this->username))));
		$this->emailAddress = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string($this->conn, $this->emailAddress))));
		
		// TODO: Insert input into SQL statement
		// TODO: Execute SQL statement and return the result

		return FALSE;

	}


}




?>