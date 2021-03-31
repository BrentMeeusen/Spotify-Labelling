<?php


class JSONWebToken {


	private static function stringToBase64(string $string) : string {
		return str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($string));
	}


	private static function createHeader() : string {
		return self::stringToBase64(json_encode([ "typ" => "JWT", "alg" => "HS256" ]));
	}


	private static function createPayload(array $payload) : string {
		return self::stringToBase64(json_encode($payload));
	}



	public static function createToken(array $payload) {

		$header = self::createHeader();
		$payload = self::createPayload($payload);
		$signature = "signature";

		return json_encode("$header.$payload.$signature");

	}

}



?>