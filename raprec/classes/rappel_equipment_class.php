<?php
require_once("classes/mydb_class.php");
include_once("classes/item_class.php");

class rappel_equipment extends item {

	var $use_offset;		// A string describing the number of time this piece of equipment was used but NOT recorded in the database. Correct for missing records.
							// For ropes, this value will take the form: $use_offset = 'a123,b456'
							//  where 123 is the integer number of rappels on END A that are not recorded
							//  and 456 is the integer number of rappels on END B that are not recorded
	var $in_service_date;
	var $mfr_serial_num;
	var $retired_date;
	var $retired_reason;	// A string describing the reason for retirement
	var $retired_category;	// "age", "use", "field_damage", "other_damage"

	var $valid_retired_categories;
	var $valid_status = array("in_service", "suspended", "missing", "retired");
	
/********** Constructor **************************************/
	function __construct() {
		$this->status = "in_service";	// Default state: this equipment is still in service (NOT retired)
		array_push($this->gettable_members,'mfr_serial_num','in_service_date','retired_date','retired_reason','retired_category','use_offset');
		array_push($this->settable_members,'mfr_serial_num','in_service_date','retired_date','retired_reason','retired_category','use_offset');
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/
	function set($var, $value) {
		$value = mydb::cxn()->real_escape_string($value);
		
		switch($var) {
		case 'serial_num':
			// Test for the correct form (i.e. COH-123) 2-5 letters, hyphen, 3-7 numbers
			if(preg_match('/\b[a-zA-Z]{2,5}-[0-9]{3,7}\b/',$value) != 1) {
				throw new Exception('Rappel Equipment serial numbers must be written as 2-5 letters followed by a hyphen and 3-7 numbers (i.e. COH-1234567) : '.$value);
			}
			else {
				// Make sure that the requested serial_num is NOT already in the database.
				if($this->is_duplicate($value,get_class($this))) throw new Exception('There is already a '.get_class($this).' with a Serial Number of '.strtoupper($value));
				else parent::set('serial_num',strtoupper($value));
			}
			break;

		case 'mfr_serial_num':
			//This field will store the manufacturer's serial number in the event that an item has been assigned a 'local' serial number by the owner.
			//The database allows a VARCHAR of 0-15 characters
			if(strlen($value) > 15) throw new Exception('The manufacturer\'s serial number can only be 15 characters');
			else $this->mfr_serial_num = $value;
			break;
			
		case 'use_offset':
			if($value == "") $this->use_offset = 0;
			elseif(!$this->var_is_int($value)) throw new Exception('The USE_OFFSET must be an integer value.');
			else $this->use_offset = $value;
			break;
		
		case 'in_service_date':
			//This must be either blank or mm/dd/yyyy
			$dates = explode("/",$value); // The Date ($value) should be in the form: mm/dd/yyyy
			if(($value != "") && !checkdate((int)$dates[0], (int)$dates[1], (int)$dates[2])) throw new Exception('The In-Service Date entered is not a valid date (dates must be in the form: mm/dd/yyyy)');
			else $this->in_service_date = $value;
			break;
		
		case 'retired_date':
			//This must be either blank or mm/dd/yyyy
			$dates = explode("/",$value); // The Date ($value) should be in the form: mm/dd/yyyy
			if(($value != "") && !checkdate((int)$dates[0], (int)$dates[1], (int)$dates[2])) throw new Exception('The Retirement Date entered is not a valid date (dates must be in the form: mm/dd/yyyy)');
			else {
				$this->retired_date = $value;
			}
			break;
			
		case 'retired_reason':
			$this->retired_reason = $value;	//This value is a string with no restrictions. Just store it.
			break;
		
		case 'retired_category':
			/*
			if(in_array(strtolower($value),$this->valid_retired_categories)) $this->retired_category = strtolower($value);
			else throw new Exception('The rappel_equipment->set() function attempted to set an invalid \'retired_category\' ('.$value.')');
			*/
			$this->retired_category = strtolower($value);
			break;
			
		default:
			parent::set($var,$value); // If this request is not specific to a piece of rappel equipment, let the ITEM class deal with it
			//throw new Exception('The rappel_equipment->set() function attempted to modify an invalid variable ('.$var.')');
			break;
		} // End: switch($var)
	
	} // End: function set()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(in_array($var,$this->gettable_members)) return $this->$var;
		else return parent::get($var);
	} // End: function get()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() **************************************************************************/
/*******************************************************************************************************************************/
	function load($item_id) {
		// This function will populate all member variables of the current object with data from the database entry that
		// corresponds to the rappel_equipment with the requested $item_id
		
		if(is_null($item_id) || $item_id === '') {
			foreach($this->settable_members as $key => $value) {
				$this->$key = NULL;
			}
			return true;
		}
		elseif(!is_numeric($item_id) || !(intval($item_id) == floatval($item_id))) {
			//Make sure the $item_id is an integer
			throw new Exception('A non-integer Item ID number was requested in rappel_equipment->load(): '.$item_id);
			return 0;
		}
		$item_id = mydb::cxn()->real_escape_string($item_id);
		
		$db_view = get_class($this) . "_use_view";	// Should be "genie_use_view", "rope_use_view" or "letdown_line_use_view"
		$query = "SELECT	id,
							serial_num,
							mfr_serial_num,
							crew_affiliation_id,
							crew_affiliation_name,
							use_offset,
							in_service_date,
							retired_date,
							retired_reason,
							retired_category,
							status "
				."FROM ".$db_view." "
				."WHERE id = '".$item_id."'";
		$result = mydb::cxn()->query($query);
		
		$ar = mydb::cxn()->affected_rows;
		if($ar == 1) {
			$row = $result->fetch_assoc();
			foreach($row as $key=>$value) $this->$key = $value;
		}
		elseif($ar == 0)	throw new Exception('The requested '.get_class($this).' does not exist! Query: '.$query);
		else 				throw new Exception('An ambiguous '.get_class($this).' ID was passed to rappel_equipment->load(). Query: '.$query);
	} //End: function load()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: load_by_serial_num() ************************************************************/
/*******************************************************************************************************************************/
	function load_by_serial_num($input_serial) {
		if(is_null($input_serial) || $input_serial == '') {
			$this->load(NULL);
			return true;
		}
		$query = "SELECT id FROM items WHERE serial_num = '".mydb::cxn()->real_escape_string($input_serial)."' and item_type = '".$this->item_type."'";
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows < 1) throw new Exception('The requested '.get_class($this).' does not exist!');
		else {
			$row = $result->fetch_assoc();
			$this->load($row['id']);
		}
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: save() **************************************************************************/
/*******************************************************************************************************************************/
	function save() {
		// This function will write the current RAPPEL_EQUIPMENT object into the database as a new entry in the 'items' table
		// Incomplete entries will not be allowed - a catchable exception will be thrown
		
		if(($this->serial_num != "") /*&& ($this->crew_affiliation_id != "")*/ /*&& ($this->in_service_date != "")*/) {
			if(($this->retired_date != "") || ($this->status == 'retired') || ($this->retired_category != "") || ($this->retired_reason != "")) {
				if(($this->retired_date == "") || ($this->status != 'retired') || ($this->retired_category == "")) {
					//If some info indicates retirement, but other retirement details are missing...
					throw new Exception('You must provide a Retirement Date and Retirement Category in addition to a Status of \'Retired\' in order to retire this equipment.');
				}
			}
			// Attempt to determine the crew affiliation from the serial number (if no crew_affiliation_id is provided)
			if($this->crew_affiliation_id == "") $this->crew_affiliation_id = $this->get_crew_affiliation_from_serial_num($this->serial_num);
			if($this->crew_affiliation_id == "NULL") throw new Exception('The ownership could not be determined for '.$this->get('item_type').' '.$this->get('serial_num').'. Make sure the letters in the serial number are correct.');
			
			// Format the in_service_date for a database insertion (change a blank field to 'NULL')
			if($this->in_service_date == "") $in_service_date_for_db = "NULL";
			else $in_service_date_for_db = "str_to_date('".mydb::cxn()->real_escape_string($this->in_service_date)."', '%m/%d/%Y')";
			
			// Format the retired_date for a database insertion (change a blank field to 'NULL')
			if($this->retired_date == "") $retired_date_for_db = "NULL";
			else $retired_date_for_db = "str_to_date('".mydb::cxn()->real_escape_string($this->retired_date)."', '%m/%d/%Y')";
			
			// Check to see whether we are inserting a NEW ENTRY or updating an EXISTING ENTRY
			if(isset($this->id)) {
				if($this->item_id_exists($this->id)) $action = "update";
				else {
					// The Item ID that was loaded no longer exists (another user has deleted the entry since this user loaded it)
					// Insert this ITEM back into the database from scratch using its original database ID
					$action = "insert";
					//$this->id = NULL;
				}
			}
			else $action = "insert";
			
			switch ($action) {
			case "insert":
				// Check for duplicate serial_num immediately before writing to database
				if($this->is_duplicate($this->serial_num,$this->item_type)) {
					throw new Exception('There is already an entry for that '.str_replace("_"," ",get_class($this)).' ('.$this->serial_num.'). You cannot use the same serial number.');
				}
				else {
					$query = "INSERT INTO items (`serial_num`,`mfr_serial_num`,`item_type`,`color`,`size`,`description`,`condition`,`note`,`crew_affiliation_id`,`status`,`use_offset`,`in_service_date`,`retired_date`,`retired_reason`,`retired_category`) "
							."values('".mydb::cxn()->real_escape_string(strtolower($this->serial_num))."','"
									.mydb::cxn()->real_escape_string($this->mfr_serial_num)."','"
									.mydb::cxn()->real_escape_string($this->item_type)."','"
									.mydb::cxn()->real_escape_string($this->color)."','"
									.mydb::cxn()->real_escape_string($this->size)."','"
									.mydb::cxn()->real_escape_string($this->description)."','"
									.mydb::cxn()->real_escape_string($this->condition)."','"
									.mydb::cxn()->real_escape_string($this->note)."',"
									.mydb::cxn()->real_escape_string($this->crew_affiliation_id).",'"
									.mydb::cxn()->real_escape_string($this->status)."','"
									.mydb::cxn()->real_escape_string($this->use_offset)."',"
									.$in_service_date_for_db.","
									.$retired_date_for_db.",'"
									.mydb::cxn()->real_escape_string($this->retired_reason)."','"
									.mydb::cxn()->real_escape_string($this->retired_category)."')";
					$result = mydb::cxn()->query($query);
				}
				break;
			
			case "update":
				$query = "UPDATE items	SET serial_num = '".mydb::cxn()->real_escape_string(strtolower($this->serial_num))."', "
								."`mfr_serial_num` = '".mydb::cxn()->real_escape_string($this->mfr_serial_num)."', "
								."`item_type` = '".mydb::cxn()->real_escape_string($this->item_type)."', "
								."`color` = '".mydb::cxn()->real_escape_string($this->color)."', "
								."`size` = '".mydb::cxn()->real_escape_string($this->size)."', "
								."`description` = '".mydb::cxn()->real_escape_string($this->description)."', "
								."`condition` = '".mydb::cxn()->real_escape_string($this->condition)."', "
								."`note` = '".mydb::cxn()->real_escape_string($this->note)."', "
								."`crew_affiliation_id` = ".mydb::cxn()->real_escape_string($this->crew_affiliation_id).", "
								."`status` = '".mydb::cxn()->real_escape_string($this->status)."', "
								."`use_offset` = '".mydb::cxn()->real_escape_string($this->use_offset)."', "
								."`in_service_date` = ".$in_service_date_for_db.", "
								."`retired_date` = ".$retired_date_for_db.", "
								."`retired_reason` = '".mydb::cxn()->real_escape_string($this->retired_reason)."', "
								."`retired_category` = '".mydb::cxn()->real_escape_string($this->retired_category)."' "
						."WHERE `id` = ".$this->id;
				$result = mydb::cxn()->query($query);
				break;
			} // End: switch()
			
			if(mydb::cxn()->error != "") throw new Exception('Error saving Rappel Equipment info: '.mydb::cxn()->error." :: ".$query);
		}
		else {
			$missing_vars = "";
			if(!isset($this->serial_num)) $missing_vars = "Serial Number";
			// Uncomment the next 2 lines to FORCE a Crew Affiliation ID, also uncomment the 'crew_affiliation_id' condition in the IF-statement at the top of this function
			//if(strlen($missing_vars) < 1) $missing_vars .= ", ";
			//if(!isset($this->crew_affiliation)) $missing_vars .= "Crew Affiliation";
			// Uncomment the next 2 lines to FORCE an In-Service Date, also uncomment the 'in_service_date' condition in the IF-statement at the top of this function
			//if(strlen($missing_vars) < 15) $missing_vars .= ", ";
			//if(!isset($this->in_service_date)) $missing_vars .= "In-Service Date";
			
			throw new Exception('Incomplete equipment entries cannot be saved. The following required information is missing: '.$missing_vars);
		}
	
	} // End: function save()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get_crew_affiliation_from_serial_num() ******************************************/
/*******************************************************************************************************************************/
	
	function get_crew_affiliation_from_serial_num($serial_num) {
		// This function will attempt to determine which crew this piece of gear belongs to based on its serial_num prefix
		// For example, the serial number 'COH-048' would match with the 'Central Oregon Helitack Crew' because the abbreviation
		// specified in the dB for that crew is 'COH'
		//
		// Return values:
		// 		If a crew is identified, this function returns the ID of that crew.
		//		Otherwise, this function returns 'NULL'
		
		$output = 'NULL';
		
		$pieces = explode('-',$serial_num);	// Use the hyphen as a delimeter to extract the crew prefix
		if(isset($pieces[0])) {
			$prefix = $pieces[0];
			$query = "SELECT id FROM crews WHERE LOWER(abbrev) = '".strtolower($prefix)."'";
			$result= mydb::cxn()->query($query);
			
			if(mydb::cxn()->affected_rows > 0) {
				$row = $result->fetch_assoc();
				$output = $row['id'];
			}
		}
		
		return $output;
	}// End: get_crew_affiliation_from_serial_num()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: var_is_int() ********************************************************************/
/*******************************************************************************************************************************/
	private function var_is_int($value) {
		// Returns TRUE if $value is an integer.
		// Returns FALSE otherwise.
		// This function will take any data type as input.
    	return ((string) $value) === ((string)(int) $value);
	} // End: function var_is_int()
	
} // End: class rappel_equipment
?>
