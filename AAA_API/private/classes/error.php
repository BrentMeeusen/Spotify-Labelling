<?php


class ApiResponse {

	/**
	 * Sets an HTTP response code with custom arguments if necessary
	 * 
	 * @param	int		$code		The HTTP code to return
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