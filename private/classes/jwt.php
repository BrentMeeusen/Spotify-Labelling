<?php


class JSONWebToken {

	// The key that these JSON Web Tokens are encrypted with
	private static string $key = "pjJsH0DwvzV1vFAy";





	/**
	 * Takes a string and returns it in a base64 format, where + is -, / is _, and = is omitted
	 * 
	 * @param 	string	$string		the string to encode
	 * @return 	string 				the base64 format of the string
	 */
	private static function stringToBase64(string $string) : string {
		return str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($string));
	}


	/**
	 * Takes a base64 encoded string, where + is encoded as a-, / is encoded as _, and = is omitted
	 * 
	 * @param	string	$base64		the string to decode
	 * @return	string				the data in the string
	 */
	private static function base64ToString(string $string) : string {
		
		$remainder = strlen($string) % 4;
		if($remainder > 0) {
			$string .= str_repeat("=", 4 - $remainder);
		}

		return base64_decode(str_replace(["-", "_"], ["+", "/"], $string));

	}





	/**
	 * Creates the header which is the same for every JSON Web Token
	 * 
	 * @return	string	the base64 format of the header
	 */
	private static function createHeader() : string {
		return self::stringToBase64(json_encode([ "typ" => "JWT", "alg" => "HS256" ]));
	}


	/**
	 * Adds the JSON Web Token things to the payload that the user passes
	 * 
	 * @param 	string 	$payload 	to send with the token
	 * @param 	int 	$mins 		the time in minutes until the token expires
	 * @return	string				the base64 format of the total payload in JSON format
	 */
	private static function createPayload(array $payload, $mins) : string {
		$payload["iat"] = time();
		$payload["exp"] = time() + $mins * 60;
		return self::stringToBase64(json_encode($payload));
	}


	/**
	 * Creates the signature based on a SHA256 algorithm
	 * 
	 * @param	string	$header		the header in base64
	 * @param	string	$payload	the payload in base64
	 * @return	string 				the hashed signature in base64
	 */
	private static function createSignature(string $header, string $payload) : string {
		return self::stringToBase64(hash_hmac("sha256", "$header.$payload", self::$key, true));
	}





	/**
	 * Creates the token using the payload that the user passes
	 * 
	 * @param	string	$payload	the payload to send with the token
	 * @param	int		$mins		the time in minutes after which the token expires
	 * @return 	string				the JSON Web Token
	 */
	public static function createToken(array $payload, int $mins) : string {

		$header = self::createHeader();
		$payload = self::createPayload($payload, $mins);
		$signature = self::createSignature($header, $payload);

		return "$header.$payload.$signature";

	}


	/**
	 * Checks whether the given token is valid at this point in time
	 * 
	 * @param	string	$token		the token to check
	 * @return	bool				if the token is valid: true
	 * @return	array				if the token is invalid: the error
	 */
	public static function checkToken(string $token) {

		// Get all the parts
		$parts = explode(".", $token);

		// If there aren't 3 parts (header.payload.signature), return Bad Request
		if(count($parts) !== 3) {
			$message = httpResponseCode(400, "Incorrect number of segments");
			return $message;
		}

		// Check whether all the segments are encoded correctly
		print(self::base64ToString($parts[0]));


		// // Check whether the signature is valid
		// $parts[2]

		// return self::stringToBase64(hash_hmac("sha256", "$header.$payload", self::$key, true));




		// The token is valid
		return TRUE;

	}

}



?>