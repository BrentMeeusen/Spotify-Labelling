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
	 * @param 	array 	$payload 	to send with the token
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
	 * @param	array	$payload	the payload to send with the token
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
	 */
	public static function validateToken(string $token) : bool {

		// Get all the parts
		$parts = explode(".", $token);

		// If there aren't 3 parts (header.payload.signature), return Bad Request
		if(count($parts) !== 3) {
			ApiResponse::httpResponse(400, ["error" => "Incorrect number of segments"]);
		}

		list($header, $payload, $signature) = $parts;


		// Check whether all the segments are encoded correctly
		$headerDecoded = json_decode(self::base64ToString($header));
		$payloadDecoded = json_decode(self::base64ToString($payload));
		$signatureDecoded = self::base64ToString($signature);

		if($headerDecoded === NULL || $payloadDecoded === NULL || $signatureDecoded === NULL) {
			ApiResponse::httpResponse(400, ["error" => "Invalid token encoding"]);
		}



		// Check whether it's a JSON token
		if(!property_exists($headerDecoded, "typ") || $headerDecoded->typ !== "JWT") {
			ApiResponse::httpResponse(400, ["error" => "Not a JSON Web Token"]);
		}
		// Check whether it's the correct algorithm
		if(!property_exists($headerDecoded, "alg") || $headerDecoded->alg !== "HS256") {
			ApiResponse::httpResponse(400, ["error" => "Invalid hashing algorithm"]);
		}



		// Check whether the signature is valid
		if($signature !== self::createSignature($header, $payload)) {
			ApiResponse::httpResponse(400, ["error" => "Invalid signature"]);
		}



		// If NOT BEFORE exists, AND if we're before that date, return an error
		if(property_exists($payloadDecoded, "nbf") && time() < $payloadDecoded->nbf) {
			ApiResponse::httpResponse(400, ["error" => "Token is not yet valid"]);
		}
		
		// If ISSUED AT exists, AND if we're before that date, return an error
		if(property_exists($payloadDecoded, "iat") && time() < $payloadDecoded->iat) {
			ApiResponse::httpResponse(400, ["error" => "Token is issued in the future"]);
		}

		// If EXPIRES AT exists, AND if we're after that date, return an error
		if(property_exists($payloadDecoded, "exp") && time() > $payloadDecoded->exp) {
			ApiResponse::httpResponse(400, ["error" => "Token expired"]);
		}



		// The token is valid
		return TRUE;

	}

	
	/**
	 * Gets the payload from the token, after checking whether the token is valid or not
	 * 
	 * @param	string	$token		The token to get the payload from
	 * @return	StdClass			The payload
	 */
	public static function getPayload(string $token) : StdClass {

		// Check whether the token is valid
		$res = self::validateToken($token);

		// If it's valid, return the payload
		$payload = explode(".", $token)[1];
		return json_decode(self::base64ToString($payload));

	}

}



?>