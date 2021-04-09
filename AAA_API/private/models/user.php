<?php

class User {

	// Initialise variables
	private mysqli $conn;
	private int $id;
	private string $firstName;
	private string $lastName;
	private string $username;
	private string $emailAddress;
	private int $accountStatus;
	private string $accountStatusText;





	/**
	 * User constructor
	 * @param mysqli connection
	 */
	public function __construct(mysqli $conn) {
		$this->conn = $conn;
	}



}




?>