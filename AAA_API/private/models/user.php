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
	 * 
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

		$firstName = $values["FirstName"];
		$lastName = $values["LastName"];
		$username = $values["Username"];
		$password = $values["Password"];
		$emailAddress = $values["EmailAddress"];
		$this->setAccountStatus(1);

		return FALSE;

	}


}




?>