<?php


class JSONWebToken {

	private $header = json_encode([ "typ" => "JWT", "alg" => "HS256" ]);

	public static getHeader() {
		return $header;
	}

}



?>