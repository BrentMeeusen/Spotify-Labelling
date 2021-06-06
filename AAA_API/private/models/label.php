<?php

class Label extends Table {


	// Initialise variables
	public static int $id;
	public static string $publicID;

	public static string $name;
	public static bool $isPublic;





	/**
	 * Label constructor
	 * 
	 * @param		string		Label name
	 * @param		bool		Whether the label is public or not
	 */
	public function __construct(string $publicID, string $creator, string $name, bool $isPublic) {

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

		// If no user is found, return NULL
		if(count($res) === 0) {
			return NULL;
		}

		// Create and return the found label as an object
		return Label::construct($res[0]);

	}


}

?>