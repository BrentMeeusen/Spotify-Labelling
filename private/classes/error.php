<?php

function httpResponseCode(int $code, string $message, array $arr = []) : array {

	http_response_code($code);

	$array = [ "code" => $code, "message" => $message];
	foreach($arr as $key => $val) {
		$array[$key] = $val;
	}

	return $array;

}

?>