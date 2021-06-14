<?php

// If we get an "Access denied" error, return to the user
if($_GET["error"]) {
	header("Location: php/redirect.php?redirect=&code=001&message=You%20must%20accept%20the%20Spotify%20popup%20if%20you%20want%20to%20use%20this%20application.");
}


print("<pre>");
print_r($_GET);
?>