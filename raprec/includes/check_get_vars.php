<?php
require_once("classes/mydb_class.php");

/***************************************************************************************************/
/***************************************************************************************************/

function is_valid_region($region = 0) {
	$valid_regions = array(1,2,3,4,5,6,8,9,10);	//There is no Region 7

	if(in_array($region,$valid_regions)) return true;
	else return false;
}

/***************************************************************************************************/
/***************************************************************************************************/

function check_crew($crew = "non_integer_value") {
	// This function will accept an INTEGER and check for a corresponding CREW ID in the database (crews.id).
	// If the requested crew exists, the NAME of the crew is returned (a string value).
	// If the requested crew does not exist, or a non-integer value is passed, return 0.
	// There is no need to call this function before calling crew->load(), as the load function performs
	//  its own data validation

	if(is_numeric($crew) && (intval($crew) == floatval($crew))) { //Match an integer value
		$query = "SELECT name FROM crews WHERE id = ".$crew;
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['name'];
		}
	}
	return false;
}

/***************************************************************************************************/
/***************************************************************************************************/

function check_year($year = 0) {
	// This function verifies that the input value is a valid 4-digit year.
	// To be considered VALID, the input must be 4-digits and must begin with either '19' or '20'
	// If the input is found to be a VALID year, return 1, else return 0.
	if(preg_match('/(19|20)[0-9]{2}/', $year)) return 1;
	else return 0;
}

/***************************************************************************************************/
/***************************************************************************************************/

function check_hrap($hrap_id) {
	// This function will accept an INTEGER and check for a corresponding HRAP ID in the database (hraps.id).
	// If the requested hrap exists, the function returns the rappeller's full name (as a string).
	// If the requested hrap does not exist, or a non-integer value is passed, return 0

	if(is_numeric($hrap_id) && (intval($hrap_id) == floatval($hrap_id))) { //Match an integer value
		$query = "SELECT firstname, lastname FROM hraps WHERE id = ".$hrap_id;
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['firstname']." ".$row['lastname'];
		}
	}
	return 0;
} // End: function check_hrap()

/***************************************************************************************************/
/***************************************************************************************************/
/*
function check_operation_id($operation_id = -1) {
	$query = "SELECT id FROM operations WHERE id = ".mydb::cxn()->real_escape_string($operation_id);
	$result = mydb::cxn()->query($query);
	
	if(mydb::cxn()->affected_rows == 1) return true;
	else return false;
}
*/
/***************************************************************************************************/
/***************************************************************************************************/
/*
function operation_date($operation_id) {
	$query = "SELECT CONCAT(MONTH(date),'/',DAY(date),'/',YEAR(date)) AS date FROM operations WHERE id = ".$operation_id;
	$result = mydb::cxn()->query($query);
	
	if(mydb::cxn()->error != NULL) return false;
	elseif(mydb::cxn()->affected_rows > 1) return false;
	else {
		$row = $result->fetch_assoc();
		$date = $row['date'];
		return $date;
	}
}
*/
/***************************************************************************************************/
/***************************************************************************************************/

function var_is_int($value) {
	// Returns TRUE if $value is an integer.
	// Returns FALSE otherwise.
	// This function will take any data type as input.
	return ((string) $value) === ((string)(int) $value);
} // End: function var_is_int()
?>
