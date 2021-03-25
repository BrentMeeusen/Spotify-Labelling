<?php
header("Content-Type: application/json; charset=UTF-8");
include_once("../../private/include_all.php");
print(json_encode(httpResponseCode(403, "Forbidden")));
?>