<?php


class Table {

	protected static mysqli $conn;





	/**
	 * 
	 */
	public static function prepare() {
		print(json_encode(self::$conn));
	}

}


?>