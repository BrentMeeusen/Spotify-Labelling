<?php


class JSONWebToken {

	private $header;

	public static function getHeader() {
		$header = json_encode([ "typ" => "JWT", "alg" => "HS256" ]);
		return $header;
	}

}



?>