<?php
require_once("../classes/mydb_class.php");

class person {
	var $id;					// An Integer, referring to the database id for this person (people.id)
	var $firstname;
	var $lastname;
	var $name;					// Firstname, Lastname, and any 'bling' that has been earned - i.e. John Doe <img src="bling_filename">
	var $crew;					// An Integer, referring to the database id of a crew (crews.id)
	var $job_title;				// These items should be stored in the 'rosters' dB table
	var $company;

	var $address_home_street1;
	var $address_home_street2;
	var $address_home_city;
	var $address_home_state;
	var $address_home_zip;
	
	var $address_work_street1;
	var $address_work_street2;
	var $address_work_city;
	var $address_work_state;
	var $address_work_zip;
	
	var $email;
	
	var $phone_personal_cell;	
	var $phone_home;
	var $phone_work;
	var $phone_work_cell;
	var $fax;

	var $gender;				// "male" or "female"
	var $birthdate;				// A PHP dateTime object
	var $headshot_filename;
	
	
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: person() ***************************************************************************/
/*******************************************************************************************************************************/
	function __constructor() {
		//CONSTRUCTOR
		$this->birthdate = new DateTime;
		
	} // END function person()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/

	function set($var, $value) {
		switch($var) {
/*
		case('id'):
			if(!$this->var_is_int($value)) {
				throw new Exception('Person ID must be an integer');
			}
			else $this->id = $value;
			break;
*/
		case('firstname'):
			if(!preg_match('/^[a-z\d_]{1,30}$/i', $value)) {
				throw new Exception('Firstname must be 1 - 30 letters');
			}
			else $this->firstname = $value;
			break;
			
		case('lastname'):
			if(!preg_match('/^[a-z\d_]{1,30}$/i', $value)) {
				throw new Exception('Lastname must be 1 - 30 letters');
			}
			else $this->lastname = $value;
			break;
		
		case('gender'):
			if((strtolower($value) != 'male') && (strtolower($value) != 'female')) {
				throw new Exception('Gender must be either male or female');
			}
			else $this->gender = strtolower($value);
			break;
		
		case('birthdate'):
			$dates = explode("/",$value);
			if(checkdate((int)$dates[0], (int)$dates[1], (int)$dates[2])) {
				$this->birthdate->setDate($dates[0],$dates[1],$dates[2]);
			}
			else throw new Exception('The birthdate entered is not a valid date');
			break;
		
		case('remove_headshot'):
			$query = "UPDATE people SET headshot_filename = '".$_SESSION['missing_headshot_image']."' WHERE id = ".$this->id;
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') throw new Exception('There was a database error while removing a personal headshot photo');
			break;

		} // End: switch($var)
		
		//If execution reaches this point, data has been checked and no exceptions have been thrown. Return with success state.
		return 1;
	} // End: function set()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		switch($var) {
			case 'address_home':
			$val = $this->address_home_street1."<br />\n"
					.$this->address_home_street2."<br />\n"
					.$this->address_home_city.", ".strtoupper($this->address_home_state)." ".$this->address_home_zip;
			break;
			
			case 'address_work':
			$val = $this->address_work_street1."<br />\n"
					.$this->address_work_street2."<br />\n"
					.$this->address_work_city.", ".strtoupper($this->address_work_state)." ".$this->address_work_zip;
			break;
			
			default:
			$val = $this->$var;
			break;
		}
		
		return $val;
	} // END function get()
	
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: save() **************************************************************************/
/*******************************************************************************************************************************/
	function save() {
		// Error-checking should already be complete
		if(person::exists($this->id)) {
			// UPDATE an existing database entry
			$query = "UPDATE people set "
					."firstname = ".$this->firstname.", "
					."lastname = ".$this->lastname.", "
					."address_home_street_1 = ".$this->address_home_street_1.", "
					."address_home_street_2 = ".$this->address_home_street_2.", "
					."address_home_city = ".$this->address_home_city.", "
					."address_home_state = ".$this->address_home_state.", "
					."address_home_zip = ".$this->address_home_zip.", "
					."address_work_street_1 = ".$this->address_work_street_1.", "
					."address_work_street_2 = ".$this->address_work_street_2.", "
					."address_work_city = ".$this->address_work_city.", "
					."address_work_state = ".$this->address_work_state.", "
					."address_work_zip = ".$this->address_work_zip.", "
					."email = ".$this->email.", "
					."phone_personal_cell = ".$this->phone_personal_cell.", "
					."phone_work = ".$this->phone_work.", "
					."phone_work_cell = ".$this->phone_work_cell.", "
					."phone_home = ".$this->phone_home.", "
					."fax = ".$this->fax.", "
					."gender = ".$this->gender.", "
					."birthdate = ".$this->birthdate.", "
					."facebook_username = ".$this->facebook_username.", "
					."username = ".$this->username.", "
					."headshot_filename = ".$this->headshot_filename
					." WHERE id = ".$this->id;
					
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') throw new Exception('There was a problem updating '.$this->firstname.' '.$this->lastname.'\'s database entry.');
		}
		else {
			// INSERT a new database entry
			$query = "INSERT INTO people ("
					."firstname, "
					."lastname, "
					."address_home_street_1, "
					."address_home_street_2, "
					."address_home_city, "
					."address_home_state, "
					."address_home_zip, "
					."address_work_street_1, "
					."address_work_street_2, "
					."address_work_city, "
					."address_work_state, "
					."address_work_zip, "
					."email, "
					."phone_personal_cell, "
					."phone_home, "
					."phone_work, "
					."phone_work_cell, "
					."fax, "
					."gender, "
					."birthdate, "
					."facebook_username, "
					."username, "
					."headshot_filename) "
					."VALUES ("
					."'".$this->firstname."', "
					."'".$this->lastname."', "
					."'".$this->address_home_."', "
					."'".$this->address_home_."', "
					."'".$this->address_home_."', "
					."'".$this->address_home_."', "
					."'".$this->address_home_."', "
					."'".$this->address_work_."', "
					."'".$this->address_work_."', "
					."'".$this->address_work_."', "
					."'".$this->address_work_."', "
					."'".$this->address_work_."', "
					."'".$this->email."', "
					."'".$this->phone_personal_cell."', "
					."'".$this->phone_home."', "
					."'".$this->phone_work."', "
					."'".$this->phone_work_cell."', "
					."'".$this->fax."', "
					."'".$this->gender."', "
					."'".$this->birthdate->format('Y-m-d')."', "
					."'".$this->facebook_username."', "
					."'".$this->username."', "
					."'".$this->headshot_filename."')";
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') throw new Exception('There was a problem inserting '.$this->firstname.' '.$this->lastname.'\'s database entry.');
		}
		
	} // END function save()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() ********************************************************************/
/*******************************************************************************************************************************/
	function load($id) {
		if(!$this->exists($id)) throw new Exception("A non-existent Person ID attempted to load");
		
		$query = "SELECT "
				."firstname, "
				."lastname, "
				."address_home_street_1, "
				."address_home_street_2, "
				."address_home_city, "
				."address_home_state, "
				."address_home_zip, "
				."address_work_street_1, "
				."address_work_street_2, "
				."address_work_city, "
				."address_work_state, "
				."address_work_zip, "
				."email, "
				."phone_personal_cell, "
				."phone_home, "
				."phone_work, "
				."phone_work_cell, "
				."fax, "
				."gender, "
				."DATE_FORMAT(birthdate,'%Y-%m-%d') as birthdate, "
				."facebook_username, "
				."username, "
				."headshot_filename "
				."FROM people WHERE id = ".$id;
		
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception('There was a problem loading Person #'.$id);
		$row = $result->fetch_assoc();
		
		$this->firstname = $row['firstname'];
		$this->lastname = $row['lastname'];
		$this->address_home_street_1 = $row['address_home_street_1'];
		$this->address_home_street_2 = $row['address_home_street_2'];
		$this->address_home_city = $row['address_home_city'];
		$this->address_home_state = $row['address_home_state'];
		$this->address_home_zip = $row['address_home_zip'];
		$this->address_work_street_1 = $row['address_work_street_1'];
		$this->address_work_street_2 = $row['address_work_street_2'];
		$this->address_work_city = $row['address_work_city'];
		$this->address_work_state = $row['address_work_state'];
		$this->address_work_zip = $row['address_work_zip'];
		$this->email = $row['email'];
		$this->phone_personal_cell = $row['phone_personal_cell'];
		$this->phone_home = $row['phone_home'];
		$this->phone_work = $row['phone_work'];
		$this->phone_work_cell = $row['phone_work_cell'];
		$this->fax = $row['fax'];
		$this->gender = $row['gender'];
		$this->birthdate = DateTime::createFromFormat('Y-m-d',$row['birthdate']);
		$this->facebook_username = $row['facebook_username'];
		$this->username = $row['username'];
		$this->headshot_filename = $row['headshot_filename'];
		
		return 1;
	} // END function load()

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
/*********************************** FUNCTION: exists() ********************************************************************/
/*******************************************************************************************************************************/
	public function exists($id = false) {
		// Returns TRUE if $id is found in the 'people' database table
		// Returns FALSE otherwise.
		// This function will take any data type as input.
		if(!$id || !is_numeric($id)) return false;
		
    	$query = "SELECT id FROM people WHERE id = ".mydb::cxn()->real_escape_string($id);
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) return TRUE;
		else return FALSE;
	} // End: function exists()
	
} // End class person