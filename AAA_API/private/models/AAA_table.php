<?php


class Table {

	protected static mysqli $conn;





	/**
	 * Prepares an SQL statement
	 * 
	 * @param	string			SQL to prepare
	 * @return	mysqli_stmt		Statement result
	 */
	public static function prepare(string $SQL) : mysqli_stmt {
		$res = self::$conn->prepare($SQL);
		if($res === FALSE) {
			ApiResponse::httpResponse(500, ["error" => "Something went wrong whilst preparing the statement."]);
		}
		return $res;
	}

}


?>