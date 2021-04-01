<?php


class ApiError {

	/**
	 * Sets an HTTP response code error with custom arguments if necessary
	 * 
	 * @param	int		$code		The HTTP error code to throw
	 * @param	array	$arr		The custom arguments to set
	 */
	public static function httpResponse(int $code, array $arr = []) : void {

		// Set HTTP response code
		http_response_code($code);

		// Construct the message
		$msg = ["code" => $code ];
		foreach($arr as $key => $val) {
			$msg[$key] = $val;
		}

		// Print the message
		print(json_encode($msg));

		// Exit the application
		exit();

	}

}

?>