<?php

class Label extends Table implements TableInterface {


	// Initialise variables
	public int $id;
	public string $publicID;

	public string $creator;
	public string $name;
	public bool $isPublic;





	/**
	 * Label constructor
	 * 
	 * @param		string		Label name
	 * @param		bool		Whether the label is public or not
	 */
	public function __construct(string $publicID, string $creator, string $name, bool $isPublic) {

		$this->publicID = $publicID;
		$this->creator = $creator;
		$this->name = $name;
		$this->isPublic = $isPublic;

	}





	/**
	 * Label constructor when the data comes from the database
	 * 
	 * @param		array		An associative array with the database values
	 * @return		Label		The created user
	 */
	public static function construct(array $values) : Label {

		$label = new Label($values["PublicID"], $values["Creator"], $values["Name"], $values["IsPublic"]);
		return $label;

	}










	/**
	 * Sanitizes the inputs
	 */
	public function sanitizeInputs() : void {
		$this->name = htmlspecialchars(strip_tags(trim(mysqli_real_escape_string(self::$conn, $this->name))));
	}





	/**
	 * Checks whether there are duplicates
	 */
	public function hasDuplicates() : bool {

	}










	/**
	 * Create the user with the given values
	 * 
	 * @param		array		The values to create the user with
	 * @return		Label		The user that was created
	 */
	public static function create(array $values) : Label {

		// Create a label object
		$label = new Label(self::generateRandomID("LABELS"), $values["Creator"], $values["Name"], $values["IsPublic"]);

		// Prepare SQL statement
		$stmt = self::prepare("INSERT INTO LABELS (PublicID, Creator, Name, IsPublic) 
		VALUES ( ?, ?, ?, ? );");

		// Sanitize input
		$label->sanitizeInputs();
		
		// Insert input into SQL statement
		$stmt->bind_param("sssi", $label->publicID, $label->creator, $label->name, $label->isPublic);

		// Execute SQL statement
		self::execute($stmt);

		// Return the result
		return $label;

	}





	/**
	 * Updates the label
	 * 
	 * @param		Label		The label to update
	 * @param		array		The new values in an associative array
	 * @param		Label		The updated label
	 */
	public static function update(Label $label, array $values) : Label {
		
		// Prepare the update process
		$label = parent::prepareUpdate($user, $values);

		// Prepare SQL statement
		$stmt = self::prepare("UPDATE LABELS SET Name = ?, IsPublic = ?;");

		// Insert input into SQL statement
		$stmt->bind_param("si", $label->name, $label->isPublic);

		// Execute SQL statement and return the result
		self::execute($stmt);
		return $label;

	}





	/**
	 * Deletes the given label from the database
	 * 
	 * @param		Label		The label to delete
	 * @return		bool		Whether it was deleted successfully or not
	 */
	public static function delete(Label $label) : bool {
		return parent::deleteEntry($label, "LABELS");
	}










	/**
	 * Finds a label by public ID
	 * 
	 * @param		string		The ID of the label
	 * @return		Label		If it was found
	 * @return		null		If no label was found
	 */
	public static function findByPublicID(string $publicID) : ?Label {

		$stmt = self::prepare("SELECT * FROM LABELS WHERE PublicID = ?;");
		$publicID = self::sanitizeArray([$publicID])[0];
		$stmt->bind_param("s", $publicID);
		$res = self::getResults($stmt);

		// If no label is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found label as an object
		return Label::construct($res[0]);

	}





	/**
	 * Finds a label by public ID
	 * 
	 * @param		string		The ID of the owner
	 * @return		array		If any labels were found
	 * @return		null		If no label was found
	 */
	public static function findAvailable(string $ownerID) : ?array {

		$stmt = self::prepare("SELECT * FROM LABELS WHERE Creator = ? OR IsPublic = 1;");
		$ownerID = self::sanitizeArray([$ownerID])[0];
		$stmt->bind_param("s", $ownerID);
		$res = self::getResults($stmt);

		// If no label is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Loop over all labels and return the array
		$return = [];
		foreach($res as $row) {
			array_push($return, Label::construct($row));
		}

		return $return;

	}





	/**
	 * Finds the label by name
	 * 
	 * @param		string		The name of the label
	 * @return		Label		If it was found
	 * @return		null		If no label was found
	 */
	public static function findByName(string $name) : ?Label {

		$stmt = self::prepare("SELECT * FROM LABELS WHERE Name = ?;");
		$name = self::sanitizeArray([$name])[0];
		$stmt->bind_param("s", $name);
		$res = self::getResults($stmt);

		// If no label is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found label as an object
		return Label::construct($res[0]);

	}





	/**
	 * Finds all labels from the owner
	 * 
	 * @param		string		The owner ID
	 * @return		array		If at least one label is found
	 * @return		null		If no labels are found
	 */
	public static function findByOwner(string $ownerID) : ?array {

		$stmt = self::prepare("SELECT * FROM LABELS WHERE Creator = ?;");
		$id = self::sanitizeArray($ownerID)[0];
		$stmt->bind_param("s", $id);
		$res = self::getResults($stmt);

		// If no labels are found, return null
		if(count($res) === 0) {
			return NULL;
		}

		// Loop over all labels and return the array
		$return = [];
		foreach($res as $row) {
			array_push($return, Label::construct($row));
		}

		return $return;

	}


}

?>