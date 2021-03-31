<?php


class JSONWebToken {

	private static function createHeader() {
		return json_encode([ "typ" => "JWT", "alg" => "HS256" ]);
	}


	private static function createPayload(array $payload) : string {
		return json_encode($payload);
	}



	public static function createToken(array $payload) {

		$header = JSONWebToken::createHeader();
		$payload = JSONWebToken::createPayload($payload);
		$signature = "signature";

		return json_encode("$header.$payload.$signature");

	}

}



?>