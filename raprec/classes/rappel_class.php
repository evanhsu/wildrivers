<?php
require_once("classes/mydb_class.php");
include_once("classes/user_class.php");
include_once("classes/hrap_class.php");
include_once("classes/genie_class.php");
include_once("classes/rope_class.php");
include_once("classes/operation_class.php");
include_once("includes/check_get_vars.php");

class rappel {

	private $id;			// The database ID of this RAPPEL
	private $operation_id;	// The OPERATION that this RAPPEL belongs to.
	
	private $hrap;		// An HRAP object describing the rappeller who performed this rappel
	private $genie;		// A Genie Object
	private $rope;		// A Rope Object
	
	var $crew;			// A CREW object. This refers to the CREW of which the HRAP was a member at the time of this rappel.
	
	private $rope_end;	// Either 'a' or 'b' - describes which end of the rope was attached to the helicopter
	private $stick;		// Possible values: 1, 2, 3.  Describes the stick that this rappel was part of.
	private $door;		// String. Possible Values: 'left', 'right'.  Describes which side of the helicopter the rappeller exited.
	
	private $knot;		// A boolean - 1=Rappeller encountered a knot in the rope.			0=No knot (normal)
	private $eto;		// A boolean - 1=Rappeller performed an ETO (Emergency Tie-Off).	0=No ETO (normal)
	
	private $comments;	// A string - database storage limited to 100 characters
	
	private $confirmed_by;	// A User Object describing the user who confirmed this Rappel Information, or FALSE if this information is unconfirmed.
	
	private $gettable_members = array('id','operation_id','hrap','genie','rope','rope_end','stick','door','knot','eto','comments','confirmed_by');
	
/********** Constructor **************************************/
	function rappel() {
		//include_once("./includes/connect.php");
		//$this->mysqli = connect();
		$this->knot = 0;
		$this->eto = 0;
	} // End: constructor

/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/
	function set($var, $value=NULL) {
		// Call this function inside of a try/throw/catch block
		
		// Blank genie & rope fields have been allowed to accommodate crews that aren't entering their equipment into RapRec
		$null_is_ok = array('knot','eto', 'comments', 'genie_by_serial_num', 'genie', 'rope_by_serial_num', 'rope', 'rope_end');
		if(($value == NULL) && !in_array(strtolower($var),$null_is_ok)) {
			throw new Exception('A value must be specified for \''.$var.'\' in rappel::set().');
			return 0;
		}
		
		$value = mydb::cxn()->real_escape_string($value);
		
		switch (strtolower($var)) {
		case "hrap_id":
			try {
				$this->hrap = new hrap;
				$this->hrap->load($value);	// The hrap->load() function performs its own data validation

				// Set $this->crew (if $this->operation_id is already set - the DATE is stored as part of the OPERATION, and we need to know the YEAR to determine this HRAP's crew membership
				if(isset($this->operation_id)) {
					$date = operation::operation_date($this->operation_id);
					$date_array = explode('/',$date);	// $date_array[0] == mm,	$date_array[1] == dd,	$date_array[2] == yyyy
					$year = $date_array[2];

					$this->crew = new crew;
					$this->crew->load($this->hrap->get_crew_by_year($year));
					
				}
				
				// Set 'confirmed_by' as follows:
				// If the current user is an ADMIN or CREW_ADMIN, and the current user belongs to the same crew as the HRAP in this rappel
				//   then 'confirmed_by' takes the value of the current user
				// Otherwise, 'confirmed_by' takes on a value of `false`
				if($this->is_confirmable()) $this->confirm();
				else $this->confirmed_by = false;
				
			} catch (Exception $e) {
				//throw new Exception($e->getMessage()); // Re-throw any exception that was thrown
				throw new Exception($e);
			}
			break;
		
		case "operation_id":
			if(intval($value) == floatval($value)) $this->operation_id = $value;
			else throw new Exception('A non-integer Operation ID number ('.$operation_id.') was passed to rappel::set().');
			
			// Set $this->crew (if $this->hrap_id is already set - the DATE is stored as part of the OPERATION, and we need to know the YEAR to determine this HRAP's crew membership
			if(isset($this->hrap_id)) {
				$date = operation::operation_date($this->operation_id);
				$date_array = split('/',$date);	// $date_array[0] == mm,	$date_array[1] == dd,	$date_array[2] == yyyy
				$year = $date_array[2];

				$this->crew = new crew;
				$this->crew->load($this->hrap->get_crew_by_year($year));
			}
			break;
			
		case "genie":
			try {
				$this->genie = new genie;
				$this->genie->load($value); //The genie->load() method performs its own error-checking & data validation
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-Throw the exception from the genie->load() method
			}
			break;
		
		case "genie_by_serial_num":
			try {
				$this->genie = new genie;
				$this->genie->load_by_serial_num($value); //The genie->load_by_serial_num() method performs its own error-checking & data validation
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-Throw the exception from the genie->load_by_serial_num() method
			}
			break;
		
		case "rope":
			try {
				$this->rope = new rope;
				$this->rope->load($value); //The rope->load() method performs its own error-checking & data validation
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-Throw the exception from the rope->load() method
			}
			break;
		
		case "rope_by_serial_num":
			try {
				$this->rope = new rope;
				$this->rope->load_by_serial_num($value); //The rope->load_by_serial_num() method performs its own error-checking & data validation
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-Throw the exception from the rope->load_by_serial_num() method
			}
			break;
		
		case "rope_end":
			$value = strtolower($value);
			if(($value != 'a') && ($value != 'b') && ($value != NULL)) throw new Exception('The Rope End must be either A or B.');
			else $this->rope_end = $value;
			break;
		
		case "stick":
			if(!in_array($value,array(1,2,3))) throw new Exception('A Rappel must belong to Stick 1, Stick 2 or Stick 3.');
			else $this->stick = $value;
			break;
		
		case "door":
			$value = strtolower($value);
			if(($value != 'left') && ($value != 'right')) throw new Exception('A Rappel must specify either the LEFT door or the RIGHT door of the helicopter.');
			else $this->door = $value;
			break;
		
		case "knot":
			if($value == 1) $this->knot = 1;
			else $this->knot = 0;
			break;
		
		case "eto":
			if($value == 1) $this->eto = 1;
			else $this->eto = 0;
			break;
		
		case "comments":
			$this->comments = $value; // mydb::cxn()->real_escape_string() has already been performed on $value
			break;
	
		default:
			throw new Exception('An invalid parameter was passed to the rappel->set() function.');
			break;
		} // End: switch(strtolower($var))
	
	} // End: function set()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(in_array($var,$this->gettable_members)) return $this->$var;
		else throw new Exception('An invalid member variable name was passed to rappel->get() ('.$var.').');
	} // End: function get()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: bind_to_op() ********************************************************************/
/*******************************************************************************************************************************/
	function bind_to_op($operation_id) {
		if(!$this->var_is_int($operation_id)) {
			//Make sure the $rappel_id is an integer
			throw new Exception('A non-integer Operation ID number ('.$operation_id.') was passed to rappel::bind_to_op().');
			return 0;
		}
		else $this->operation_id = $operation_id;
		
	} // End: function bind_to_op()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() **************************************************************************/
/*******************************************************************************************************************************/
	function load($rappel_id) {
		// This function will load all info from the database corresponding to the specified Rappel ID
		
		if(!$this->var_is_int($rappel_id)) {
			//Make sure the $rappel_id is an integer
			throw new Exception('A non-integer Rappel ID number was requested in rappel->load().');
			return 0;
		}
		$rappel_id = mydb::cxn()->real_escape_string($rappel_id);
		
		$query = "SELECT	rappels.id,		rappels.operation_id,	rappels.hrap_id,	rappels.rope_id,	rappels.genie_id,	rappels.door, "
						."	rappels.stick,	rappels.rope_end, 		rappels.knot, 		rappels.eto,		rappels.comments, "
						."	rappels.confirmed_by AS confirmed_by_id "
				."FROM rappels WHERE rappels.id = ".$rappel_id;
		
		$result = mydb::cxn()->query($query);

		$ar = mydb::cxn()->affected_rows;
		if($ar == 1) {
			try {
				$row = $result->fetch_assoc();
				$this->id = $row['id'];
				$this->operation_id = $row['operation_id'];
				$this->rope_end = $row['rope_end'];
				$this->stick = $row['stick'];
				$this->door = $row['door'];
				$this->knot = $row['knot'];
				$this->eto = $row['eto'];
				$this->comments = $row['comments'];
	
				//$this->hrap = new hrap;
				//$this->hrap->load($row['hrap_id']);
				$this->set('hrap_id',$row['hrap_id']);
				
				$this->rope = new rope;
				$this->rope->load($row['rope_id']);

				$this->genie = new genie;
				$this->genie->load($row['genie_id']);
				
				if($row['confirmed_by_id'] != 0) {
					$this->confirmed_by = new user;
					$this->confirmed_by->load($row['confirmed_by_id']);
				}
				else $this->confirmed_by = false;

				return 1;
				
			} catch (Exception $e) {
				throw new Exception($e);
				return 0;
			}
			
		}
		elseif($ar < 1) throw new Exception('The requested Rappel does not exist (Rappel ID: '.$rappel_id.').');
		// The following case should NEVER happen:
		else throw new Exception('The requested Rappel ID is ambiguous! Notify the Site Administrator of this message.<br>\n'
								.'Details: rappel_class.php : function load()<br>\nRequested rappel_id:'.$rappel_id.')');
	
	} // End: function load()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: save() **************************************************************************/
/*******************************************************************************************************************************/
	function save() {
		// This function will write the current RAPPEL Object into the database.  All information is required.
		// If saving a NEW entry, this function will also SET the member variable 'id' with the autoincrement ID generated by the database
		
		// FIRST: check if the current user has permission to SAVE
		if(!in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) {
			throw new Exception("You must be a Crew Admin to save or modify rappel information");
		}
		
		//If somebody has already confirmed this rappel info, only allow changes by a CREW_ADMIN ON THE SAME CREW AS THE HRAP IN THIS RAPPEL
		//If this is a NEW rappel, allow creation by ANY CREW_ADMIN (No need to be on same crew as HRAP involved)
		// This allows crew_admins on host crews to enter rappels for Boosters (visiting HRAPs), but the HRAP's home crew must confirm the info.
		if($this->is_confirmable()) $this->confirm(); // Set confirmed_by to the user currently making changes, even if the rappel was previously confirmed
		// If this rappel is NOT confirmable, don't change the confirmed_by value at all (it should already be set to FALSE, or this rap may have been previously confirmed)
		//else throw new Exception('You must be a CREW ADMIN for '.$this->hrap->get('firstname').'\'s crew in order to modify this rappel.');

		
		// Ensure that all information is provided
		$missing_vars = array();
		if(!isset($this->operation_id)) $missing_vars[] = 'operation_id'; 
		if(!isset($this->rope_end)) $missing_vars[] = 'rope_end';
		if(!isset($this->stick)) $missing_vars[] = 'stick';
		if(!isset($this->door)) $missing_vars[] = 'door';
		if(!isset($this->hrap)) $missing_vars[] = 'hrap';
		if(!isset($this->rope)) $missing_vars[] = 'rope';
		if(!isset($this->genie)) $missing_vars[] = 'genie';
		if(!isset($this->confirmed_by)) $missing_vars[] = 'confirmed_by';
		
		if(sizeof($missing_vars) > 0) {
			$missing_string = "";
			foreach($missing_vars AS $var) {
				$missing_string .= ", ".$var;
			}
			$missing_string = substr($missing_string,2,strlen($missing_string)); // Cut out the first comma (',')
			throw new Exception('Required Rappel information is missing: '.$missing_string);
			return 0;
		}
		
		// Decide whether to INSERT a new entry, or UPDATE an existing entry
		if($this->rappel_id_exists($this->id)) $action = "update";
		else $action = "insert";
		// Update an existing entry
		// Ensure that the current rappel_id still exists in the database before trying to update it
		// IF $this->id was NOT FOUND in the database, treat this as a new entry
		
		
		// Now actually perform the action that was decided on above
		switch($action) {
		case "insert":
			try {
				$query = "INSERT INTO rappels (operation_id, hrap_id, rope_id, genie_id, door, stick, rope_end, knot, eto, comments, confirmed_by) "
						."values ("	.$this->operation_id.","
									.$this->hrap->get('id').","
									.(is_null($this->rope->get('id')) ? 'NULL' : $this->rope->get('id')).","
									.(is_null($this->genie->get('id')) ? 'NULL' : $this->genie->get('id')).",'"
									.$this->door."',"
									.$this->stick.",'"
									.$this->rope_end."',"
									.$this->knot.","
									.$this->eto.",'"
									.$this->comments."',";
				if($this->confirmed_by !== false) $query .= $this->confirmed_by->get('id').")";
				else $query .= "0)";

				$result = mydb::cxn()->query($query);

				if(mydb::cxn()->error != NULL) {
					throw new Exception("Database error saving new Rappel: ".mydb::cxn()->error."<br />\nQuery: ".$query);
					return 0; // Failure
				}
				else {
					$this->id = mydb::cxn()->insert_id; // Grab the ID given to this entry by the database autoincrement
					//return 1; // Success
				}
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-Throw the exception to the calling function
			}
			break;
		
		case "update":
			try {
				if(!isset($this->operation_id)) {
					throw new Exception('Required Rappel information is missing: operation_id');
					return 0;
				}
				$query = "UPDATE rappels "
						."SET operation_id = "	.$this->operation_id
							.",hrap_id = "		.$this->hrap->get('id')
							.",rope_id = "		.(is_null($this->rope->get('id')) ? 'NULL' : $this->rope->get('id'))
							.",genie_id = "		.(is_null($this->genie->get('id')) ? 'NULL' : $this->genie->get('id'))
							.",door = '"		.$this->door."'"
							.",stick = "		.$this->stick
							.",rope_end = '"	.$this->rope_end."'"
							.",knot = "			.$this->knot
							.",eto = "			.$this->eto
							.",comments = '"	.$this->comments."'";
				if($this->confirmed_by !== false) {
					$query .= ",confirmed_by = " . $this->confirmed_by->get('id');
				}
				else $query .= ",confirmed_by = 0";
				
				$query .= " WHERE id = ".$this->id;

				$result = mydb::cxn()->query($query);

				if(mydb::cxn()->error != NULL) {
					throw new Exception('Database error saving new Rappel: '.mydb::cxn()->error);
					return 0; // Failure
				}
				else {
					//$this->id = mydb::cxn()->insert_id; // Grab the ID given to this entry by the database autoincrement
					//return 1; // Success
				}
			} catch (Exception $e) {
				throw new Exception($e->getMessage()); // Re-Throw the exception to the calling function
			}
			break;
		} // End: switch($action)


		// Everything has completed without throwing any exceptions...
		// If this rappel is still unconfirmed, send an email to the crew admins for confirmation
		/************************ EMAIL NOTIFICATION IS DISABLED HERE ******************************/
		/*******************************************************************************************/
		/*******************************************************************************************/
/*
		if($this->confirmed_by == false) {
			//Determine the year in which this rappel took place, then find the crew this hrap was on that year, then find the crew admins for that crew
			$result = mydb::cxn()->query("SELECT YEAR(operations.date) as `year` FROM operations WHERE id = ".$this->operation_id);
			$row = $result->fetch_assoc();
			$crew_affiliation_id = $this->hrap->get_crew_by_year($row['year']);
			
			$query = "SELECT email from authentication WHERE account_type = 'crew_admin' AND crew_affiliation_id = ".$crew_affiliation_id;
			$result = mydb::cxn()->query($query);
			
			while($row = $result->fetch_assoc()) {
				
				$message = new email('rappel_needs_verification',$row['email'],'http://tools.siskiyourappellers.com/raprec/view_rappels.php?op='.$this->operation_id);
				$message->send();
			}
		}
*/

	} // End: function save()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: delete() ************************************************************************/
/*******************************************************************************************************************************/
	function delete() {
		// This function will completely delete THIS rappel from the database
		if($this->is_confirmable()) {
			$query = "DELETE FROM rappels WHERE id = ".$this->id;
			if(!mydb::cxn()->query($query)) throw new Exception('A database error occurred while deleting the requested rappel: '.mydb::cxn()->error);
			else return true;
		}
		else {
			throw new Exception('You do not have permission to delete this rappel.');
			return false;
		}
	}
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: is_confirmable() ****************************************************************/
/*******************************************************************************************************************************/
	function is_confirmable() {
		// This function will check to see whether or not the current user is allowed to confirm this rappel
		// Return Values: TRUE or FALSE
		$allowed_account_types = array('admin','crew_admin');
		$user_account_type = $_SESSION['current_user']->get('account_type');
		
		$user_crew_id = $_SESSION['current_user']->get('crew_affiliation_id');
		$rap_crew_id = $this->crew_affiliation_id();
		
		if(in_array($user_account_type,$allowed_account_types) && ($user_crew_id == $rap_crew_id)) return true;
		else return false;
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: crew_affiliation_id() ***********************************************************/
/*******************************************************************************************************************************/
	function crew_affiliation_id() {
		// This function will return the CREW ID of the hrap who performed this rappel, if the information is available
		// If the OPERATION or HRAP info is not yet defined for this RAPPEL, this function will return FALSE
		if(isset($this->operation_id) && isset($this->hrap)) {
			$op_date = operation::operation_date($this->operation_id);
			$pieces = explode("/",$op_date);
			return $this->hrap->get_crew_by_year($pieces[2]);
		}
		else {
			//throw new Exception('The rappel you requested does not contain any Crew information.');
			return false;
		}
	}
/*******************************************************************************************************************************/
/*********************************** FUNCTION: rappel_id_exists() **************************************************************/
/*******************************************************************************************************************************/
	private function rappel_id_exists($rappel_id = false) {
		// Check for the requested rappel_id in the database
		// Return values:	true	:	The specified rappel_id is NOT found in the database
		//					false	:	The specified rappel_id was found (already exists)
		if(!$rappel_id) return false;
		
		$query = "SELECT id FROM rappels WHERE id = ".$rappel_id;
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) return true;
		else return false;
	}
/*******************************************************************************************************************************/
/*********************************** FUNCTION: is_confirmed() *****************************************************************/
/*******************************************************************************************************************************/	
	private function is_confirmed() {
		if(($this->confirmed_by != NULL) && ($this->confirmed_by != false) && ($this->confirmed_by != "")) return true;
		else return false;
	} // End: function is_confirmed()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: allowed_to_confirm() ************************************************************/
/*******************************************************************************************************************************/
/*	private function allowed_to_confirm() {
		if(!in_array($_SESSION['current_user']->get('account_type'),array('crew_admin','admin'))) {
			throw new Exception('You must be a CREW ADMIN to change information about this rappel.');
			return false;
		}
		if($_SESSION['current_user']->get('account_type') != 'admin') {
			if($_SESSION['current_user']->get('crew_affiliation_id') != $this->hrap->get_crew_by_year($_SESSION['current_view']['year'])) {
				throw new Exception('You must be a member of this HRAP\'s crew to modify the details of this rappel.');
			}
		}
		
		return true;
	} // End function allowed_to_confirm()
*/
/*******************************************************************************************************************************/
/*********************************** FUNCTION: confirm() ***********************************************************************/
/*******************************************************************************************************************************/
	private function confirm() {
		// This function will set $this->confirmed_by to the current user
		//throw new Exception('confirming');
		$this->confirmed_by = new user;
		$this->confirmed_by->load($_SESSION['current_user']->get('id'));
	} // End: function confirm()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: var_is_int() ********************************************************************/
/*******************************************************************************************************************************/
	private function var_is_int($value) {
		// Returns TRUE if $value is an integer.
		// Returns FALSE otherwise.
		// This function will take any data type as input.
    	return ((string) $value) === ((string)(int) $value);
	} // End: function var_is_int()
/*-------------------------------------------------------------------------------------------------------------------------*/
} // End: class rappel
?>
