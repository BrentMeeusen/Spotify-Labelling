<?php



/**
 * Checks whether a key in an array is set AND is empty
 * 
 * @param	array	The array to search in
 * @param	string	The key of the array
 * @return	bool	True if it's set and empty, false if it's not
 */
function setAndEmpty($array, $key) : bool {
	if(isset($array[$key]) && empty($array[$key])) {
		return TRUE;
	}
	return FALSE;
}

?>
