<?php
include_once("../../private/include_all.php");
print(json_encode(httpResponseCode(404, "Not Found")));
exit();
?>