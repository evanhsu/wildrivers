<?php
require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/rappel_class.php");

class crew {
	var $year;
	var $id;
	var $name;
	var $region;
	var $street1;
	var $street2;
	var $city;
	var $state;
	var $zip;
	var $phone;
	var $latitude;
	var $longitude;
	var $logo_filename;
	
	var $crewmembers;	// An array of 'hrap' objects
	var $spotters;		// The number of spotters & spotter-trainees on the crew for the requested year
	
	var $raps_this_year_proficiency;
	var $raps_this_year_proficiency_live;
	var $raps_this_year_operational;
	var $raps_this_year_total;
	var $raps_this_year_live;
	
	var $raps_this_year_per_person_live;
	var $raps_this_year_per_person_operational;
	var $raps_this_year_per_person_proficiency_live;
	
	var $avg_age;		// Average age of all crewmembers (for the selected year)
	var $gender_ratio;	// Ratio of Men to Women (for the selected year) Normalized --- 1=All men, 0=All women, 0.5=Equal number of men & women
	var $male_crewmember_count;
	var $female_crewmember_count;
	
	var $male_rappels;	// Rappels performed by male crewmembers
	var $female_rappels;// Rappels performed by female crewmembers
	
/********** Constructor **************************************/
	function crew() {
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
	function load($crew_id) {
		// Use a TRY/THROW/CATCH block when calling this function
		$year = $_SESSION['current_view']['year'];
		if(is_numeric($crew_id) && (intval($crew_id) == floatval($crew_id))) $crew_id = mydb::cxn()->real_escape_string($crew_id);
		else {
			throw new Exception('Attempted to load a non-integer Crew ID');
			return 0;
		}
		
		$query = "SELECT id, name, region, street1, street2, city, state, zip, phone, lat, lon, logo_filename FROM crews WHERE id = ".$crew_id;
		$crews_result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows < 1) {
			throw new Exception('There is no crew with a Crew ID of '.$crew_id);
			return 0;
		}
		elseif(mydb::cxn()->affected_rows > 1) {
			throw new Exception('There is MORE THAN ONE crew with a Crew ID of '.$crew_id);
			return 0;
		}
		else {
			$row_crews = $crews_result->fetch_assoc();

			if(mydb::cxn()->error != "") throw new Exception('There was a problem retrieving roster information for '.$row_crews['name'].' (Crew #'.$row_crews['id'].')');
			else {
				// No exceptions have been thrown, everything checks out - load this crew's data
				$this->year = $year;
				$this->id = $row_crews['id'];
				$this->name = $row_crews['name'];
				$this->region = $row_crews['region'];
				$this->street1 = $row_crews['street1'];
				$this->street2 = $row_crews['street2'];
				$this->city = $row_crews['city'];
				$this->state = $row_crews['state'];
				$this->zip = $row_crews['zip'];
				$this->phone = $row_crews['phone'];
				$this->latitude = $row_crews['lat'];
				$this->longitude = $row_crews['lon'];
				$this->logo_filename = $row_crews['logo_filename'];

				$this->male_rappels = 0;
				$this->female_rappels = 0;
				
				$this->raps_this_year_proficiency = 0;
				$this->raps_this_year_proficiency_live = 0;
				$this->raps_this_year_operational = 0;
				$this->raps_this_year_total = 0;
				$this->raps_this_year_live = 0;
/*
				$query = "SELECT operations.type, hraps.gender FROM "
						."rappels INNER JOIN rosters ON rappels.hrap_id = rosters.hrap_id "
						."INNER JOIN hraps ON rappels.hrap_id = hraps.id "
						."INNER JOIN crews on rosters.crew_id = crews.id "
						."INNER JOIN operations ON operations.id = rappels.operation_id "
						."WHERE rosters.year = '".$year."' && crews.id = ".$this->id;
*/
				$query = "
					SELECT gender,operation_type, sum(raps) AS raps 
					FROM rap_type_count 
					WHERE year = '".$year."' AND crew_id = ".$this->id."
					GROUP BY gender,operation_type";
				$result = mydb::cxn()->query($query);

				if(mydb::cxn()->error != "") {
					throw new Exception('There was a problem retrieving a rappel count for '.$this->name.' (Crew #'.$this->id.') for the year '.$year.': '.mydb::cxn()->error);
					return 0;
				}
				
				while($row = $result->fetch_assoc()) {
					if(strtolower($row['gender']) == 'male') $this->male_rappels += $row['raps'];
					else $this->female_rappels += $row['raps'];
					

					switch($row['operation_type']) {
					case 'operational':
						$this->raps_this_year_operational += $row['raps'];
						$this->raps_this_year_live += $row['raps'];
						$this->raps_this_year_total += $row['raps'];
						break;
					case 'proficiency_live':
					case 'certification_new_aircraft':
					case 'certification_new_hrap':
						$this->raps_this_year_proficiency += $row['raps'];
						$this->raps_this_year_proficiency_live += $row['raps'];
						$this->raps_this_year_live += $row['raps'];
						$this->raps_this_year_total += $row['raps'];
						break;

					case 'proficiency_tower':
						$this->raps_this_year_proficiency += $row['raps'];
						$this->raps_this_year_total += $row['raps'];
						break;
					} // End: switch($row['type'])
				}

				$query = "SELECT count(hrap_id) as crewmember_count FROM rosters WHERE crew_id = ".$crew_id." && year = '".$year."'";
				$result = mydb::cxn()->query($query);
				$row = $result->fetch_assoc();
				
				if($row['crewmember_count'] < 1) {
					$this->crewmember_count = 0;
					$this->raps_this_year_per_person_live = 0;
					$this->raps_this_year_per_person_operational = 0;
					$this->raps_this_year_per_person_proficiency_live = 0;
				}
				else {
					$this->crewmember_count = $row['crewmember_count'];
					$this->raps_this_year_per_person_live = bcdiv($this->raps_this_year_live,$row['crewmember_count'],1);
					$this->raps_this_year_per_person_operational = bcdiv($this->raps_this_year_operational,$row['crewmember_count'],1);
					$this->raps_this_year_per_person_proficiency_live = bcdiv($this->raps_this_year_proficiency_live,$row['crewmember_count'],1);
				}
				
				// Generate Crew Demographic Information (Male / Female Stats)
				$this->avg_age = 0;
				$this->gender_ratio = "Unknown";
				$this->spotters = 0;
				$this->male_crewmember_count = 0;
				$this->female_crewmember_count=0;
				$total_age = 0;
				$crew_size = 0;
				
				$query = "SELECT hrap_id, gender, birthdate, spotter FROM rosters INNER JOIN hraps ON rosters.hrap_id = hraps.id WHERE year = '".$year."' && crew_id = ".$this->id;
				$result = mydb::cxn()->query($query);
				
				while($row = $result->fetch_assoc()) {
					$crew_size++;
					if(strtolower($row['gender']) == 'male') $this->male_crewmember_count++;
					else $this->female_crewmember_count++;
					
					if($row['spotter'] != 0) $this->spotters++;
					
					// Calculate this person's age
					list($Y,$m,$d)	= explode("-",$row['birthdate']);
					$total_age		+= date("Y") - $Y;
					if( date("md") < $m.$d ) $total_age--;
				}
				if($crew_size > 0) $this->avg_age = round($total_age / $crew_size,1);
				if(($this->male_crewmember_count != 0) || ($this->female_crewmember_count != 0)) $this->gender_ratio = 100 * round($this->male_crewmember_count / $this->crewmember_count,2);

				
				return 1; //If execution reaches this point, everything has gone well (no errors).
			}
		}
		// If code execution reaches this point, an exception has already been thrown - exit with error state
		return 0;
		
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
