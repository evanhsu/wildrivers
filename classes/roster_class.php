<?php
require_once("../classes/mydb_class.php");
require_once("../classes/person_class.php");

class roster {

	var $id;
	var $year;
	var $crew_id;

	var $crewmembers;	// Array of 'crewmember.id's - change this to An array of 'person' objects
	
	var $avg_age;		// Average age of all crewmembers (for the selected year)
	var $gender_ratio;	// Ratio of Men to Women (for the selected year) Normalized --- 1=All men, 0=All women, 0.5=Equal number of men & women
	var $male_count;
	var $female_count;
	
/********** Constructor **************************************/
	function __constructor() {
		//include_once("./includes/connect.php");
		//$this->mysqli = connect();
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/
	function set($var, $value) {
		if(isset($this->$var)) {
			$this->$var = $value;
			return true;
		}
		else return false;
	} // End: set()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(isset($this->$var)) return $this->$var;
		else return false;
	} // End: function get()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() **************************************************************************/
/*******************************************************************************************************************************/
	function load($year = NULL, $crew_id = NULL) {
		// Use a TRY/THROW/CATCH block when calling this function
		//$year = $_SESSION['current_view']['year'];
		if(is_null($year)) $this->year = date('Y'); // Use current year if not specified
		else $this->year = $year; // Perform validation on this value...
		//if(crew::exists($crew_id)) $this->crew_id = $crew_id;
		
		$query = "SELECT id FROM roster WHERE year = ".$this->year;
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows < 1) {
			throw new Exception('There is no roster from the year '.$this->year);
		}
		
		while($row = $result->fetch_assoc()) {
			$this->crewmembers[] = $row[id];
		}
		
		
	} // End: function load()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get_roster_years() **************************************************************/
/*******************************************************************************************************************************/
	function get_roster_years() {
		//This function will return an array of strings.  The array is a list of years where this crew had a non-empty roster

		$query = "SELECT DISTINCT year FROM rosters WHERE crew_id = ".$this->id." ORDER BY year DESC";
		$result = mydb::cxn()->query($query);

		$year_array = array();

		if(mydb::cxn()->affected_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$year_array[] = $row['year'];
			}
			return $year_array;
		}
		else return false;
	
	} // End: function get_roster_years()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: get_crewmembers() ***************************************************************/
/*******************************************************************************************************************************/
	function get_crewmembers($year) {
	
		$query = "SELECT rosters.id, hrap_id, crew_id, year FROM rosters INNER JOIN hraps ON rosters.hrap_id = hraps.id WHERE year = '".$year."' && crew_id = ".$this->id." ORDER BY firstname";
		$result_rosters = mydb::cxn()->query($query);

		if(mydb::cxn()->affected_rows < 1) throw new Exception('There is no roster information for '.$this->name.' (Crew #'.$this->id.') in '.$year);

		else {
			$this->crewmembers = array(); // Clear any existing crewmember info from the member array

			while($row_rosters = $result_rosters->fetch_assoc()) {
				$temp_hrap = new hrap();
				$temp_hrap->load($row_rosters['hrap_id']);
				$this->crewmembers[] = $temp_hrap;
			}
			
			return 1; // At least 1 crewmember has been loaded into the array, return SUCCESS (1)
		} // End: else
		
		return 0; // No crewmember info has been loaded into the member array (this->crewmembers) - return FAILURE (0)
		
	} // End: function get_crewmembers()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: add_crewmember() ****************************************************************/
/*******************************************************************************************************************************/
	function add_crewmember($hrap_id, $year) {
	
	}
	
	function get_rap_history_years() {
		$query = "SELECT DISTINCT year FROM view_rappels WHERE crew_id = ".$this->id." ORDER BY year DESC";
		$result = mydb::cxn()->query($query);
		
		$years = array();
		while($row = $result->fetch_assoc()) {
			$years[] = $row['year'];
		}
		
		return $years;
	}
	
	function exists($crew_id) {
		// Check the database to make sure the specified crew actually exists
		  $query = "SELECT DISTINCT id, name FROM crews WHERE id = ".$crew_id;
		  $result = mydb::cxn()->query($query);
		  
		  if(mydb::cxn()->affected_rows > 0) return TRUE;
		  else return FALSE;
	}
	
	function get_name($crew_id) {
		$query = "SELECT name FROM crews WHERE id = ".$crew_id;
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['name'];
		}
		else return FALSE;
	}
	
} // End: class crew

?>