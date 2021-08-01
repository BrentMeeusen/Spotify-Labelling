<?php

class Label extends Database {


	// Initialise variables
	public int $id;
	public string $publicID;

	public string $creator;
	public string $name;
	public bool $isPublic;





	/**
	 * Label constructor
	 * 
	 * @param		string		The label public ID
	 * @param		string		The creator public ID
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
		$this->name = parent::sanitize($this->name);
	}





	/**
	 * Default method for duplicates check
	 * 
	 * @return		bool		False if no duplicates are found
	 * @return		array		[key => Property, value => Duplicate value]
	 */
	public function hasDuplicates() {

		// Get all entries that are from this creator with this name
		// TODO: REFACTOR WITH NEW DATABASE STRUCTURE AND CLASSES
		$stmt = self::prepare("SELECT * FROM LABELS WHERE Creator = ? AND Name = ? AND NOT PublicID = ?;");
		$stmt->bind_param("sss", $this->creator, $this->name, $this->publicID);
		$res = self::getResults($stmt);

		// Return whether it has found a duplicate or not
		return (count($res) === 0 ? FALSE : ["key" => "a label", "value" => $res[0]["Name"]]);

	}










	/**
	 * Create the user with the given values
	 * 
	 * @param		array		The values to create the user with
	 * @return		Label		The user that was created
	 */
	public static function create(array $values) : Label {

		// Create a label object
		$label = new Label(Database::generateRandomID("LABELS"), $values["Creator"], $values["Name"], $values["IsPublic"]);

		// Create label
		$stmt = self::prepare("INSERT INTO LABELS (PublicID, Name, IsPublic) VALUES ( ?, ?, ? );");
		$label->sanitizeInputs();
		$stmt->bind_param("ssi", $label->publicID, $label->name, $label->isPublic);
		self::execute($stmt);

		// Create label-to-user
		$stmt = self::prepare("INSERT INTO LABELS_TO_USERS (LabelID, OwnerID) VALUES (?, ?);");
		$stmt->bind_param("ss", $label->publicID, $label->creator);
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

		// Check whether object is of type Label
		if(!($label instanceof Label)) { throw new InvalidArgumentException; }

		// Prepare the update process
		$label = parent->prepareUpdate($label, $values);

		// Prepare SQL statement
		$stmt = self::prepare("UPDATE LABELS SET Name = ?, IsPublic = ? WHERE PublicID = ?;");

		// Insert input into SQL statement
		$stmt->bind_param("sis", $label->name, $label->isPublic, $label->publicID);

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
	public static function delete($label) : bool {

		// Check whether object is of type Label
		if(!($label instanceof Label)) { throw new InvalidArgumentException; }

		// Delete the entry
		return parent->deleteEntry($label, "LABELS");

	}










	/**
	 * Finds a label by public ID
	 * 
	 * @param		string		The ID of the label
	 * @return		Label		If it was found
	 * @return		null		If no label was found
	 */
	public static function findByPublicID(string $publicID) : ?Label {

		// If no label is found, return NULL
		$res = parent::find("SELECT * FROM LABELS WHERE PublicID = ?;", $publicID);
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

		// If no label is found, return NULL
		$res = parent::find("SELECT * FROM LABELS WHERE Creator = ? OR IsPublic = 1;", $publicID);
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

		// If no label is found, return NULL
		$res = parent::find("SELECT * FROM LABELS WHERE Name = ?;", $name);
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

		// If no labels are found, return NULL
		$res = parent::find("SELECT * FROM LABELS WHERE Creator = ?;", $ownerID);
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
