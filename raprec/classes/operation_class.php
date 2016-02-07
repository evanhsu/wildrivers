<?php
include_once("classes/mydb_class.php");
include_once("classes/hrap_class.php");
include_once("classes/rappel_class.php");
include_once("classes/letdown_line_class.php");

class operation {

	private $id;
	private $date;	// mm/dd/yyyy
	private $type;	// 'operational', 'proficiency_live', 'proficiency_tower', 'certification_new_aircraft', 'certification_new_hrap'
	private $incident_number;	// 'OR-OCF-123456'
	private $height;
	private $canopy_opening;
	private $weather;
	private $location;
	private $comments;
	
	private $pilot_name;	// First Last (i.e. 'John Doe')
	
	private $aircraft_type;
	private $aircraft_configuration;
	private $aircraft_tailnumber;
	private $aircraft_image;	// The filename of the image that corresponds to the $aircraft_type and $aircraft_configuration for this operation
	private $aircraft_types_id;	// The ID in the 'aircraft_types' table [aircraft_types.id] that refers to this combination of aircraft, config, and image
	
	var $spotter;	// An HRAP object
	
	var $rappels;	// An array of RAPPEL objects
	var $letdowns = array();	// An array of LETDOWN_LINE objects

	private $gettable_members = array(	'id','date','type','incident_number','height','canopy_opening','weather','location','comments','pilot_name',
										'aircraft_type','aircraft_configuration','aircraft_tailnumber','aircraft_image','aircraft_types_id','letdowns');
	
/********** Constructor **************************************/
	function operation() {
		
		//Set default values - choose the first entry in the db
		$result = mydb::cxn()->query("SELECT id, shortname, configuration, filename FROM aircraft_types LIMIT 1");
		$row = $result->fetch_assoc();
		
		$this->aircraft_types_id = $row['id'];
		$this->aircraft_type = $row['shortname'];
		$this->aircraft_configuration = $row['configuration'];
		$this->aircraft_image = $row['filename'];
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/
	function set($var, $value = NULL) {
		// This function will set the specified member variable ($var) to the specified value ($value) after checking the data
		
		// NULL Spotter has been allowed to accommodate mixed-loads where a non-RapRec spotter spots a load of RapRec-using rappellers.
		$null_is_ok = array('height','canopy_opening','weather','location','comments', 'spotter');
		if(($value == NULL) && !in_array(strtolower($var),$null_is_ok)) {
			throw new Exception('You must provide a value for '.$var.' (in operation->set())');
			return 0;
		}
		
		$value = mydb::cxn()->real_escape_string($value);
		
		switch (strtolower($var)) {
		case "id":
			throw new Exception('You cannot manually set the ID of an Operation. Use the operation->load() method to specify an existing operation.');
			return 0;
			break;
		
		case "date":
			$dates = explode("/",$value); // The Date ($value) should be in the form: mm/dd/yyyy
			if(!checkdate((int)$dates[0], (int)$dates[1], (int)$dates[2])) {
				throw new Exception('The Date entered is not a valid date (dates must be in the form: mm/dd/yyyy)');
				return 0;
			}
			else $this->date = $value;
			break;
		
		case "incident_number":
			if(preg_match('/\b[a-zA-Z]{2}-[a-zA-Z0-9]{3,5}-[0-9]{6}\b/i',$value) != 1) {
				throw new Exception('An invalid Incident Number was provided!. The Incident Number must be in the form: OR-OCF-123456 (You provided: '.$value.')');
				return 0;
			}
			elseif($this->type != 'operational') {
				throw new Exception('You cannot specify an Incident Number for a Training Rappel');
				return 0;
			}
			else $this->incident_number = strtoupper($value);
			break;
		
		case "type":
			if(in_array($value,array('operational','proficiency_live','proficiency_tower','certification_new_aircraft','certification_new_hrap'))) {
				if($value != 'operational') $this->incident_number = NULL;
				$this->type = strtolower($value);
			}
			else {
				throw new Exception('An invalid Operation Type was selected.');
				return 0;
			}
			break;
		
		case "height":
			if(!is_numeric($value) && ($value != "")) {
				throw new Exception('Height must be a number');
				return 0;
			}
			elseif (($value > 250) || ($value < 0)) {
				throw new Exception('Height must be between 0 and 250');
				return 0;
			}
			else $this->height = $value;
			break;
		
		case "canopy_opening":
			if(!is_numeric($value) && ($value != NULL)) {
				throw new Exception('Canopy Opening must be a number or NULL');
				return 0;
			}
			elseif (is_numeric($value) && ($value < 0)) {
				throw new Exception('Canopy Opening must be greater than 0');
				return 0;
			}
			else $this->canopy_opening = $value;
			break;
		
		case "weather":
			$this->weather = mydb::cxn()->real_escape_string($value);
			break;
		
		case "location":
			$this->location = mydb::cxn()->real_escape_string($value);
			break;
		
		case "comments":
			$this->comments = mydb::cxn()->real_escape_string($value);
			break;
			
		case "pilot_name":
			if(preg_match('/\b[a-z]{1,30}\b/i', $value)) {
				$this->pilot_name = ucwords($value);
			}
			else {
				throw new Exception('Pilot Name must be 1 - 30 letters (Including first and last name)');
				return 0;
			}
			break;
		
		case "aircraft_type":
			$success = false;
			$result = mydb::cxn()->query("SELECT id, shortname, filename FROM aircraft_types WHERE configuration = '".$this->aircraft_configuration."'");
			while($row = $result->fetch_assoc()) {
				if(strtolower($value) == $row['shortname']) {
					$this->aircraft_type = $value;
					$this->aircraft_image = $row['filename'];
					$this->aircraft_types_id = $row['id'];
					$success = true;
					break; // Exit the while() loop
				}
			}
			if(!$success) {
				throw new Exception('An invalid aircraft type was specified ('.$value.')');
				return 0;
			}
			break;
		
		case "aircraft_configuration":
			$success = false;
			$result = mydb::cxn()->query("SELECT id, configuration, filename FROM aircraft_types WHERE shortname = '".$this->aircraft_type."'");
			while($row = $result->fetch_assoc()) {
				if(strtolower($value) == $row['configuration']) {
					$this->aircraft_configuration = $value;
					$this->aircraft_image = $row['filename'];
					$this->aircraft_types_id = $row['id'];
					$success = true;
					break; // Exit the while() loop
				}
			}
			if(!$success) {
				throw new Exception('An invalid aircraft configuration was specified ('.$value.')');
				return 0;
			}
			break;
		
		case "aircraft_tailnumber":
			if(preg_match('/\b(N|C-){1}[a-zA-Z0-9]{3,6}\b/i',$value) == 1) {
				$this->aircraft_tailnumber = $value;
			}
			else {
				throw new Exception('A complete Tailnumber must be provided, including the letter \'N\' at the beginning (i.e. N21HX)');
				return 0;
			}
			break;
		
		case "spotter":
			try {
				$this->spotter = new hrap;
				if(!is_null($value) && $value != '') $this->spotter->load($value);	// The hrap->load() function performs its own data validation
				//$this->spotter->load($value);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-throw any exception that was thrown
				return 0;
			}
			break;
		} // End: switch(strtolower($var))
		
		return 1;
	} // End: function set()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(in_array($var,$this->gettable_members)) return $this->$var;
		else throw new Exception('An invalid member variable name was passed to operation->get() ('.$var.').');
	} // End: function get()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: add_rappel() ********************************************************************/
/*******************************************************************************************************************************/
/*	function add_rappel($rappel_id) {
		// This function will add a new rappel object to the local $rappels array ($this->rappels)
		try {
			$new_rap = new rappel;
			$new_rap->load($rappel_id);
			
			$duplicate_index = NULL;
			// If the new Rappel ID matches an existing Rappel ID, overwrite the existing Rappel (as an update)
			// And make sure that the new rappel isn't in the same stick / same door as an existing rappel in this operation.
			for($i=0;$i<sizeof($this->rappels);$i++) {
				if($this->rappels[$i]->get('id') == $new_rap->get('id')) $duplicate_index = $i;
				elseif(($this->rappels[$i]->get('stick') == $new_rap->get('stick')) && ($this->rappels[$i]->get('door') == $new_rap->get('door'))) {
					throw new Exception('You cannot have more than 1 rappel in Stick '.$this->rappels[$i]->get('stick').' out the '.$this->rappels[$i]->get('door').' door.');
				}
			}
			if($duplicate_index !== NULL) $this->rappels[$i] = $new_rap; // Overwrite the existing rappel with that ID
			elseif(sizeof($this->rappels) >= 6) throw new Exception('You cannot have more than 6 rappels (3 sticks) in one Operation');
			else $this->rappels[] = $temp_rap; // Append the new rappel to the $rappels array
		} catch (Exception $e) {
			throw new Exception($e->getMessage()); // Re-Throw any exception that was thrown in the try block
			return 0;
		}
		return 1;
	} // End: function add_rappel()
*/
/*******************************************************************************************************************************/
/*********************************** FUNCTION: get_rappel() ********************************************************************/
/*******************************************************************************************************************************/
	function get_rappel($in_stick, $in_door) {
		// This function will return the rappel object corresponding to the specified stick and door (i.e. Stick 2, Left)
		// Or, if the requested rappel is not found, the function will return FALSE.
		$in_door = strtolower($in_door);
		if(!in_array($in_stick,array(1,2,3))) { //Check $in_array for valid input
			throw new Exception('Invalid Stick number specified ('.$in_stick.') in operation->get_rappel(). Valid options are 1, 2 or 3.');
			return false;
		}
		if(!in_array($in_door,array('left','right'))) { //Check $in_door for valid input
			throw new Exception('Invalid Door specified ('.$in_door.') in operation->get_rappel(). Valid options are \'left\' or \'right\'');
			return false;
		}
		
		for($i=0;$i<sizeof($this->rappels);$i++) {
			if(($this->rappels[$i]->get('stick') == $in_stick) && ($this->rappels[$i]->get('door') == $in_door)) {
				return $this->rappels[$i];
			}
		}
		throw new Exception('The requested rappel could not be found (Stick: '.$in_stick.', '.$in_door.' Door)');
		return false;
	} // End: function get_rappel()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: remove_rappel() *****************************************************************/
/*******************************************************************************************************************************/
	function remove_rappel($removal_id) {
		// This function will remove a rappel object from the local $rappels array ($this->rappels)
		$success = 0;
		for($i=0;$i<sizeof($this->rappels);$i++) {
			if($this->rappels[$i]->get('id') == $removal_id) {
				array_slice($this->rappels, $i, 1);
				$success = 1;
				break;
			}
		}
		return $success;
	
	} // End: function remove_rappel()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: add_letdown() *******************************************************************/
/*******************************************************************************************************************************/
	function add_letdown($letdown_line_id) {
		// This function will add a letdown line object to the local $letdowns array ($this->letdowns)
		try {
			$new_letdown = new letdown_line;
			$new_letdown->load($letdown_line_id);
			//$this->letdowns[] = $temp_letdown; // Only add the new letdown line to this member array if no exceptions were thrown

			$duplicate_index = NULL;
			// If the new Letdown Line ID matches a letdown line ID already associated with this operation, overwrite the existing Letdown Line (as an update)
			for($i=0;$i<sizeof($this->letdowns);$i++) {
				if($this->letdowns[$i]->get('id') == $new_letdown->get('id')) $duplicate_index = $i;
			}
			if($duplicate_index !== NULL) $this->letdowns[$duplicate_index] = $new_letdown; // Overwrite the existing rappel with that ID
			elseif(sizeof($this->letdowns) >= 6) throw new Exception('You cannot have more than 6 letdowns in one Operation');
			else $this->letdowns[] = $new_letdown; // Append the new rappel to the $rappels array
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage()); // Re-Throw any exception that was thrown in the try block
			return 0;
		}
		return 1;
	
	} // End: function add_letdown()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: remove_letdown() *****************************************************************/
/*******************************************************************************************************************************/
	function remove_letdown($removal_id) {
		// This function will remove a letdown object from the local $letdowns array ($this->letdowns)
		$success = 0;
		for($i=0;$i<sizeof($this->letdowns);$i++) {
			if($this->letdowns[$i]->get('id') == $removal_id) {
				array_slice($this->letdowns, $i, 1);
				$success = 1;
				break;
			}
		}
		return $success;
	
	} // End: function remove_letdown()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() **************************************************************************/
/*******************************************************************************************************************************/
	function load($operation_id) {
		// This function will load all info from the database corresponding to the specified Operation ID

		if(!$this->var_is_int($operation_id)) {
			//Make sure the $rappel_id is an integer
			throw new Exception('A non-integer Operation ID number was requested in operation->load().');
			return 0;
		}
		$operation_id = mydb::cxn()->real_escape_string($operation_id);

		$query_op	="SELECT operations.id, "
							."operations.incident_number, "
							."operations.aircraft_type_config, "
							."operations.aircraft_tailnumber, "
							."operations.spotter_id, "
							."operations.pilot_id, "
							."operations.pilot_name, "
							."operations.weather, "
							."operations.height, "
							."operations.canopy_opening, "
							."DATE_FORMAT(operations.date,'%c/%d/%Y') as date, "
							."operations.location, "
							."operations.type, "
							."operations.comments, "
							."aircraft_types.shortname, "
							."aircraft_types.configuration, "
							."aircraft_types.filename "
					."FROM operations INNER JOIN aircraft_types ON operations.aircraft_type_config = aircraft_types.id "
					."WHERE operations.id = ".$operation_id;
		$result_op = mydb::cxn()->query($query_op);
		$ar_op = mydb::cxn()->affected_rows;
		if(mydb::cxn()->error != NULL) {
			throw new Exception('A database error occurred while retrieving operation info: '.mydb::cxn()->error);
			return 0;
		}

		$query_raps	="SELECT id FROM rappels WHERE operation_id = ".$operation_id;
		$result_raps = mydb::cxn()->query($query_raps);
		$ar_raps = mydb::cxn()->affected_rows;
		if(mydb::cxn()->error != NULL) {
			throw new Exception('A database error occurred while retrieving rappel info: '.mydb::cxn()->error);
			return 0;
		}

		$query_ld	="SELECT letdown_line_id FROM letdown_events WHERE operation_id = ".$operation_id;
		$result_ld = mydb::cxn()->query($query_ld);
		$ar_ld = mydb::cxn()->affected_rows;
		if(mydb::cxn()->error != NULL) {
			throw new Exception('A database error occurred while retrieving letdown line info: '.mydb::cxn()->error);
			return 0;
		}

		if($ar_op == 1) { // Exactly 1 row was returned from the $query_op query
			try {
				$row = $result_op->fetch_assoc();
				$this->id = $row['id'];
				$this->incident_number = $row['incident_number'];
				$this->aircraft_types_id = $row['aircraft_type_config'];
				$this->aircraft_tailnumber = $row['aircraft_tailnumber'];
				$this->aircraft_type = $row['shortname'];
				$this->aircraft_configuration = $row['configuration'];
				$this->aircraft_image = $row['filename'];
				
				$this->spotter = new hrap;
				if(!is_null($row['spotter_id']) && $row['spotter_id'] != '') $this->spotter->load($row['spotter_id']);
				
				$this->pilot_name = $row['pilot_name'];
				$this->weather = $row['weather'];
				$this->height = $row['height'];
				$this->canopy_opening = $row['canopy_opening'];
				$this->date = $row['date'];
				$this->location = $row['location'];
				$this->type = $row['type'];
				$this->comments = $row['comments'];
				
				$i = 0;
				$this->rappels = array();

				while($row = $result_raps->fetch_assoc()) {
					$this->rappels[$i] = new rappel;
					$this->rappels[$i]->load($row['id']);
					$i++;
				}

				$i = 0;
				$this->letdowns = array();
				while($row = $result_ld->fetch_assoc()) {
					$this->letdowns[$i] = new letdown_line;
					$this->letdowns[$i]->load($row['letdown_line_id']);
					$i++;
				}
			} catch (Exception $e) {
				throw new Exception($e);
				return 0;
			}
		} // End: if($ar_op == 1)
		elseif($ar_op < 1) {
			throw new Exception('The specified Operation does not exist (Operation #'.$operation_id.')');
			return 0;
		}
		else {
			throw new Exception('The specified Operation is ambiguous! (Operation #'.$operation_id.'). Notify the site administrator of possible data corruption.');
			return 0;
		}
	
	} // End: function load()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: save() **************************************************************************/
/*******************************************************************************************************************************/
	function save() {
		// This function will write the current operation into the database, including all rappel objects
		// The operation info will create a new entry in the db table: 'operations'
		// Each rappel object in the $rappels member array will create a new entry in the db table: 'rappels'
		
		// Check for required input
		$missing_vars = array();
		
		switch($this->type) {
		case "operational":
			if(!isset($this->date)) $missing_vars[] = "Date";
			if(!isset($this->incident_number)) $missing_vars[] = "Incident Number";
			//if(!isset($this->height)) $missing_vars[] = "Height";
			if(!isset($this->pilot_name)) $missing_vars[] = "Pilot Name";
			if(!isset($this->aircraft_tailnumber)) $missing_vars[] = "Tailnumber";
			if(!isset($this->spotter)) $missing_vars[] = "Spotter";
			//if(!isset($this->rappels)) $missing_vars[] = "At Least 1 Rappel";
			break;
		
		case "proficiency_live":
			if(!isset($this->date)) $missing_vars[] = "Date";
			//if(!isset($this->height)) $missing_vars[] = "Height";
			if(!isset($this->pilot_name)) $missing_vars[] = "Pilot Name";
			if(!isset($this->aircraft_tailnumber)) $missing_vars[] = "Tailnumber";
			if(!isset($this->spotter)) $missing_vars[] = "Spotter";
			//if(!isset($this->rappels)) $missing_vars[] = "At Least 1 Rappel";
			break;
		
		case "proficiency_tower":
			if(!isset($this->date)) $missing_vars[] = "Date";
			//if(!isset($this->height)) $missing_vars[] = "Height";
			//if(!isset($this->rappels)) $missing_vars[] = "At Least 1 Rappel";
			break;
		
		case "certification_new_aircraft":
			if(!isset($this->date)) $missing_vars[] = "Date";
			//if(!isset($this->height)) $missing_vars[] = "Height";
			if(!isset($this->pilot_name)) $missing_vars[] = "Pilot Name";
			if(!isset($this->aircraft_tailnumber)) $missing_vars[] = "Tailnumber";
			if(!isset($this->spotter)) $missing_vars[] = "Spotter";
			//if(!isset($this->rappels)) $missing_vars[] = "At Least 1 Rappel";
			break;
		
		case "certification_new_hrap":
			if(!isset($this->date)) $missing_vars[] = "Date";
			//if(!isset($this->height)) $missing_vars[] = "Height";
			if(!isset($this->pilot_name)) $missing_vars[] = "Pilot Name";
			if(!isset($this->aircraft_tailnumber)) $missing_vars[] = "Tailnumber";
			if(!isset($this->spotter)) $missing_vars[] = "Spotter";
			//if(!isset($this->rappels)) $missing_vars[] = "At Least 1 Rappel";
			break;
		
		default:
			throw new Exception('An invalid Operation Type was set. Valid options are: operational, proficiency_live, proficiency_tower, certification_new_aircraft, certification_new_hrap.');
			return 0;
			break;
		} // End: switch($this->type)
		
		if(sizeof($missing_vars) > 0) {
			$missing_string = "";
			foreach($missing_vars AS $var) {
				$missing_string .= ", ".$var;
			}
			$missing_string = substr($missing_string,2,strlen($missing_string)); // Cut out the first comma (',')
			throw new Exception('Required information is missing: '.$missing_string);
			return 0;
		}
		
		// Decide whether to INSERT a new entry, or UPDATE an existing entry
		if(isset($this->id)) {
			// Update an existing entry
			// Ensure that the current operation_id still exists in the database before trying to update it
			// IF $this->id was NOT FOUND in the database, treat this as a new entry
			if($this->exists($this->id)) $action = "update";
			else $action = "insert";
		}
		else $action = "insert";
		
		switch($action) {
		case "insert": /*----------------------------- CREATE A NEW OPERATION -----------------------------------*/
			try {
				mydb::cxn()->autocommit(FALSE);	// Begin a MySQL Transaction

				$mysql_date = $this->convert_mdy_to_ymd($this->date); // Change date format for MySQL insertion
				$just_year=$this->convert_mdy_to_y($this->date); // Extract the Year for insertion into the dB year column
				//echo "PHP DATE: ".$this->date."<br>\nMySQL DATE: ".$mysql_date."<br>\n";
				if($this->spotter->get('id') == NULL) $spotter_id = "NULL";
				else $spotter_id = $this->spotter->get('id');
				
				$query = "INSERT INTO operations (date, year, type, incident_number, height, canopy_opening, pilot_name, aircraft_type_config, aircraft_tailnumber, "
												."spotter_id, weather, location, comments) "
						."values ('".$mysql_date."',".$just_year.",'".$this->type."','".$this->incident_number."','".$this->height."','".$this->canopy_opening."','".$this->pilot_name
									."',".$this->aircraft_types_id.",'".$this->aircraft_tailnumber."',".$spotter_id.",'".$this->weather."','".$this->location."','".$this->comments."')";
				
				// Make sure this query completes, otherwise rollback the transaction
				if(!$result = mydb::cxn()->query($query)) {
					throw new Exception('An error occurred while trying to save the details of this Operation: '.mydb::cxn()->error.' -- Query: '.$query);
					return 0;
				}
				else $this->id = mydb::cxn()->insert_id; // Grab the ID given to this entry by the database autoincrement
				
/*				// Binding of rappels to operations is handled by the RAPPEL class. The OPERATION collects it's member-rappels during an operation->load().
				foreach($this->rappels AS $rap) {
					$rap->set('operation_id', $this->id);
					$rap->save(); // If this fails, an exception will be thrown and the transaction will rollback
				}
*/
				
				// Remove all LETDOWN_EVENTS corresponding to this Operation - then create a letdown_event for each letdown_line present
				$query = "DELETE FROM letdown_events WHERE operation_id = ".$this->id;
				
				// Make sure this query completes, otherwise rollback the transaction
				if(!$result = mydb::cxn()->query($query)) throw new Exception('An error occurred while trying to save the letdown events for this Operation: '.mydb::cxn()->error);
				
				// Now add a letdown_event for each letdown_line
				foreach($this->letdowns AS $ld) {
					$query = "INSERT INTO letdown_events (letdown_line_id, operation_id) "
							."values (".$ld->get('id').",".$this->id.")";
					// Make sure this query completes, otherwise rollback the transaction
					if(!$result = mydb::cxn()->query($query)) throw new Exception('An error occurred while trying to create a letdown event for Letdown Line #'.$ld->get('id').': '.mydb::cxn()->error);
				}
				
				if(mydb::cxn()->error != NULL) {
					throw new Exception('Database error saving new Operation: '.mydb::cxn()->error);
					return 0;
				}
			
				//No errors have occurred yet - COMMIT the transaction
				mydb::cxn()->commit();
				
			} catch (Exception $e) {				// If an error occurs:
				mydb::cxn()->rollback();				// Cancel the transaction - restore db to previous state
				mydb::cxn()->autocommit(TRUE); 		// Reset the db to default behavior (non-transactional)
				throw new Exception($e->getMessage());	// Re-Throw the exception to the calling function
				return 0;
			}
			break;
		
		case "update": /*-------------------------- UPDATE AN EXISTING OPERATION --------------------------------*/
			try {
				mydb::cxn()->autocommit(FALSE);	// Begin a MySQL Transaction
				
				$mysql_date = $this->convert_mdy_to_ymd($this->date); // Change date format for MySQL insertion
				$just_year = $this->convert_mdy_to_y($this->date); // Extract the Year for db insertion
				if($this->spotter->get('id') == NULL) $spotter_id = "NULL";
				else $spotter_id = $this->spotter->get('id');
				
				$query = "UPDATE operations "
							."SET date = '".$mysql_date."', "
							."year = ".$just_year.", "
							."type = '".$this->type."', "
							."incident_number = '".$this->incident_number."', "
							."height = '".$this->height."', "
							."canopy_opening = '".$this->canopy_opening."', "
							."pilot_name = '".$this->pilot_name."', "
							."aircraft_type_config = ".$this->aircraft_types_id.", "
							."aircraft_tailnumber = '".$this->aircraft_tailnumber."', "
							."spotter_id = ".$spotter_id.", "
							."weather = '".$this->weather."', "
							."location = '".$this->location."', "
							."comments = '".$this->comments."' "
						."WHERE id = ".$this->id;
				
				// Make sure this query completes, otherwise rollback the transaction
				if(!$result = mydb::cxn()->query($query)) throw new Exception('An error occurred while trying to save the details of this Operation: '.mydb::cxn()->error);
				
/*
				foreach($this->rappels AS $rap) {
					$rap->save(); // If this fails, an exception will be thrown and the transaction will rollback
				}
*/
				// Remove all LETDOWN_EVENTS corresponding to this Operation - then create a letdown_event for each letdown_line present
				$query = "DELETE FROM letdown_events WHERE operation_id = ".$this->id;
				
				// Make sure this query completes, otherwise rollback the transaction
				if(!$result = mydb::cxn()->query($query)) throw new Exception('An error occurred while trying to save the letdown events for this Operation: '.mydb::cxn()->error);
				
				// Now add a letdown_event for each letdown_line
				foreach($this->letdowns AS $ld) {
					$query = "INSERT INTO letdown_events (letdown_line_id, operation_id) "
							."values (".$ld->get('id').",".$this->id.")";
					// Make sure this query completes, otherwise rollback the transaction
					if(!$result = mydb::cxn()->query($query)) throw new Exception('An error occurred while trying to create a letdown event for Letdown Line #'.$ld->get('id').': '.mydb::cxn()->error);
				}
				
				//No errors have occurred yet - COMMIT the transaction
				mydb::cxn()->commit();
				
			} catch (Exception $e) {				// If an error occurs:
				mydb::cxn()->rollback();				// Cancel the transaction - restore db to previous state
				mydb::cxn()->autocommit(TRUE); 		// Reset the db to default behavior (non-transactional)
				throw new Exception($e->getMessage());	// Re-Throw the exception to the calling function
				return false;
			}
			break;
			
		} // End: switch($action)
		
		mydb::cxn()->autocommit(TRUE); 		// Reset the db to default behavior (non-transactional)
		return true; // Success
		
		
	} // End: function save()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: exists() ***********************************************************/
/*******************************************************************************************************************************/
	public static function exists($operation_id = false) {
		// Check for the requested operation_id in the database
		// Return values:	true	:	The specified operation_id is NOT found in the database
		//					false	:	The specified operation_id was found (already exists)
		if(!$operation_id) return false;
		
		$query = "SELECT id FROM operations WHERE id = ".mydb::cxn()->real_escape_string($operation_id);
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) return true;
		else return false;
	} // End: function exists()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: var_is_int() ********************************************************************/
/*******************************************************************************************************************************/
	private function var_is_int($value) {
		// Returns TRUE if $value is an integer.
		// Returns FALSE otherwise.
		// This function will take any data type as input.
    	return ((string) $value) === ((string)(int) $value);
	} // End: function var_is_int()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: convert_mdy_to_y() **************************************************************/
/*******************************************************************************************************************************/
	private function convert_mdy_to_y($mdy) {
		list($m,$d,$Y)	= explode("/",$mdy);
		return $Y;
	} // End: function convert_mdy_to_y($mdy)

/*******************************************************************************************************************************/
/*********************************** FUNCTION: convert_mdy_to_ymd() ************************************************************/
/*******************************************************************************************************************************/
	private function convert_mdy_to_ymd($mdy) {
		list($m,$d,$Y)	= explode("/",$mdy);
		return $Y."/".$m."/".$d;
	} // End: function convert_mdy_to_ymd($mdy)

/*******************************************************************************************************************************/
/*********************************** FUNCTION: operation_date() ****************************************************************/
/*******************************************************************************************************************************/
	public static function operation_date($operation_id) {
		// Return the date of the requested operation in the format mm/dd/yyyy
		// Return FALSE if operation not found
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

/*-------------------------------------------------------------------------------------------------------------------------*/
} // End: class operation
?>
