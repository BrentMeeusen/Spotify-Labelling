<?php

function httpResponseCode(int $code, string $message) : array {
	http_response_code($code);
	return array("code" => $code, "message" => $message);
}

?>