<?php


class JSONWebToken {

	// The key that these JSON Web Tokens are encrypted with
	private static string $key = "pjJsH0DwvzV1vFAy";


	/**
	 * Takes a string and returns it in a base64 format, where + is -, / is _, and = is omitted
	 * @param string the string to encode
	 * @return string the base64 format of the string
	 */
	private static function stringToBase64(string $string) : string {
		return str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($string));
	}


	/**
	 * Creates the header which is the same for every JSON Web Token
	 * @return string the base64 format of the header
	 */
	private static function createHeader() : string {
		return self::stringToBase64(json_encode([ "typ" => "JWT", "alg" => "HS256" ]));
	}


	/**
	 * Adds the JSON Web Token things to the payload that the user passes
	 * @param payload to send with the token
	 * @return string the base64 format of the total payload in JSON format
	 */
	private static function createPayload(array $payload) : string {
		return self::stringToBase64(json_encode($payload));
	}


	/**
	 * Creates the signature based on a SHA256 algorithm
	 * @param header the header in base64
	 * @param payload the payload in base64
	 * @return string the hashed signature in base64
	 */
	private static function createSignature(string $header, string $payload) : string {
		return self::stringToBase64(hash_hmac("sha256", "$header.$payload", self::$key, true));
	}





	/**
	 * Creates the token using the payload that the user passes
	 * @param payload the payload to send with the token
	 * @return string the JSON Web Token
	 */
	public static function createToken(array $payload) : string {

		$header = self::createHeader();
		$payload = self::createPayload($payload);
		$signature = self::createSignature($header, $payload);

		return "$header.$payload.$signature";

	}

}



?>