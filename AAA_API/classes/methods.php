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





/**
 * Checks whether a key in an array is set AND is empty
 * 
 * @param	array	The array to search in
 * @param	string	The key of the array
 * @return	bool	True if it's set and empty, false if it's not
 */
function my_isset($value) : bool {
	if(isset($value) && !empty($value)) {
		return TRUE;
	}
	return FALSE;
}





/**
 * Makes a one-dimensional array from a multi-dimensional array
 * 
 * @param		array		The array to flatten
 * @return		array		The flattened array
 */
function array_flatten(array $array) : array {

	$flat = [];
	foreach($array as $entry) {

		if(is_array($entry)) {
			$new = array_flatten($entry);
			foreach($new as $n) {
				array_push($flat, $n);
			}
		}
		else  {
			array_push($flat, $entry);
		}

	}
	return $flat;

}

?>
