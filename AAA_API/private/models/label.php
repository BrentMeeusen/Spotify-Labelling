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
	public function __construct(string $name, bool $isPublic) {

		$this->name = $name;
		$this->isPublic = $isPublic;

	}


}

?>