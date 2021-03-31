<?php


class JSONWebToken {

	
	private string $key = "pjJsH0DwvzV1vFAy";


	private static function stringToBase64(string $string) : string {
		return str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($string));
	}


	private static function createHeader() : string {
		return self::stringToBase64(json_encode([ "typ" => "JWT", "alg" => "HS256" ]));
	}


	private static function createPayload(array $payload) : string {
		return self::stringToBase64(json_encode($payload));
	}


	private static function createSignature(string $header, string $payload) : string {
		GLOBAL $key;
		return self::stringToBase64(hash_hmac("sha256", "$header.$payload", $key, true));
	}



	public static function createToken(array $payload) : string {

		$header = self::createHeader();
		$payload = self::createPayload($payload);
		$signature = self::createSignature($header, $payload);

		return json_encode("$header.$payload.$signature");

	}

}



?>