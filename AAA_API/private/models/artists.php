<?php


class Artists {


	// Declare variables
	public array $artists;





	/**
	 * Constructor
	 * 
	 * @param		array		An array of Artist objects
	 */
	public function __construct(array $artists) {
		$this->artists = $artists;
	}

}

?>