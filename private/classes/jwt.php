<?php


class JSONWebToken {

	private static function createHeader() {
		return json_encode([ "typ" => "JWT", "alg" => "HS256" ]);
	}






	public static function createToken($payload) {

		$header = JSONWebToken::createHeader();
		$signature = "signature";

		return json_encode("$header.$payload.$signature");

	}

}



?>