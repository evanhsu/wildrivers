<?php
require_once("classes/mydb_class.php");

class user {
	var $id;
	var $firstname;
	var $lastname;
	var $email;
	var $username; //Same as $email, provided for convenience
	var $password;
	var $salt;
	var $account_type;
	var $last_login;
	var $account_creation_date;
	var $last_renewal_date;
	var $crew_affiliation_id;
	var $crew_affiliation_name;
	var $inactive;
	
/********** Constructor **************************************/
	function user($type=false) {
		if($type == 'guest') {
			$this->account_type = 'guest';
		}
		$this->salt = md5(time());
	}
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() **************************************************************************/
/*******************************************************************************************************************************/
	function load($user_id) {
		if(!$this->var_is_int($user_id)) {
			//Make sure the $user_id is an integer
			throw new Exception('A non-integer User ID was requested in user->load().');
			return 0;
		}
		$user_id = mydb::cxn()->real_escape_string($user_id);
		
		$query = "SELECT authentication.id AS id, firstname, lastname, email, password, salt, account_type, account_creation_date, crew_affiliation_id, last_login, authentication.inactive, crews.last_renewal_date, crews.name AS crew_name, crews.region as region "
						."FROM authentication LEFT OUTER JOIN crews "
						." ON authentication.crew_affiliation_id = crews.id "
						."WHERE authentication.id = ".$user_id;
		$result = mydb::cxn()->query($query);
		
		$ar = mydb::cxn()->affected_rows;
		if($ar == 1) {
			$row = $result->fetch_assoc();
			$this->id = $row['id'];
			$this->firstname = $row['firstname'];
			$this->lastname = $row['lastname'];
			$this->email = $row['email'];
			$this->username = $row['email'];
			$this->password = $row['password'];
			$this->salt = $row['salt'];
			$this->account_type = $row['account_type'];
			$this->last_login = $row['last_login'];
			$this->inactive = $row['inactive'];
			$this->account_creation_date = $row['account_creation_date'];
			$this->last_renewal_date = $row['last_renewal_date'];
			$this->crew_affiliation_id = $row['crew_affiliation_id'];
			$this->crew_affiliation_name= $row['crew_name'];
			$this->region= $row['region'];
			return 1;
		}
		elseif($ar == 0)	throw new Exception('The requested User (User #'.$user_id.') does not exist!');
		else 				throw new Exception('An ambiguous User ID (User #'.$user_id.') was passed to user->load().'); // This should NEVER happen
		
		return 0; // The function will RETURN before reaching this point if there are no errors.
	} // End: function load()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: update_last_login_timestamp() ***************************************************/
/*******************************************************************************************************************************/
	function update_last_login_timestamp() {
		if(!isset($this->id)) throw new Exception('The Last-Login Timestamp can only be updated for existing users.');
		else {
			$query = "UPDATE authentication SET last_login = from_unixtime(".time().") WHERE id = ".$this->id;
			mydb::cxn()->query($query);
			if(mydb::cxn()->error != NULL) throw new Exception('Error in user->update_last_login_timestamp(): '.mydb::cxn()->error);
		}	
	} // End function update_last_login_timestamp()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: create() ************************************************************************/
/*******************************************************************************************************************************/
	function create($firstname, $lastname, $password, $email, $account_type, $crew_affiliation_id='') {
		
		// Attempt to set all properties.  The set() function will throw an error if needed.
		$this->set('firstname',$firstname);
		$this->set('lastname',$lastname);
		$this->set('email',$email);
		$this->set('password',$password);
		$this->set('account_type',$account_type);
		$this->set('crew_affiliation_id',$crew_affiliation_id);
		$this->set('inactive',0); // We should never create an inactive account.  Accounts are marked INACTIVE instead of deleting them.


		if($this->crew_affiliation_id == "") $crew_affiliation_for_db = 'NULL';
		else $crew_affiliation_for_db = $this->crew_affiliation_id;
		
		// User data has been checked without throwing any exceptions, commit this data to the database
		$query = "INSERT into authentication (firstname, lastname, email, password, salt, account_type, account_creation_date, crew_affiliation_id) "
				."VALUES(\"".$this->firstname."\",\"".$this->lastname."\",\"".$this->email."\",\"".$this->password."\",\"".$this->salt."\",\"".$this->account_type."\",from_unixtime(".time()."),".$crew_affiliation_for_db.")";
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows == 1) {
			$this->id = mydb::cxn()->insert_id; // Update this object's ID with the auto-increment id assigned by the database
			return true;
		}
		else throw new Exception('There was a problem creating a new account');
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/
	function set($var, $value) {
		switch($var) {
		case 'firstname':
			if(!preg_match('/^[a-zAZ]{1,25}$/i', $value)) throw new Exception('Firstname must be 1 - 25 letters');
			$this->firstname = $value;
			break;
		
		case 'lastname':
			if(!preg_match('/^[a-zAZ]{1,25}$/i', $value)) throw new Exception('Lastname must be 1 - 25 letters');
			$this->lastname = $value;
			break;
			
		case 'email':
			if(!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i", $value)) throw new Exception('Email must be of the form: yourname@yourhost.com');
			//if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $value)) throw new Exception('Email must be of the form: yourname@yourhost.com');
			
			$result = mydb::cxn()->query("SELECT count(id) as ids FROM authentication WHERE email LIKE \"".$value."\" && id <> ".$_SESSION['current_user']->get('id'));
			$row_email = $result->fetch_assoc();

			//if($row_email['ids'] != 0)  throw new Exception('That email address is already in use!');
			$this->email = $value;
			break;
		
		case 'password':
			if(!preg_match($_SESSION['password_pattern'],$value)) throw new Exception('Password must be 6 - 15 characters (no quotes or slashes allowed');
			//$this->password = sha1($this->salt.$value); //Store a SHA-1 encrypted hash of the password (salted with this user's unique salt)
			$this->password = sha1($this->email.$value); //Hash is salted with username to enable stateless API calls. Slightly less 'strong', but allows API functionality.
			break;
		
		case 'account_type':
			$valid_account_types = array('admin','crew_admin','crewmember','observer');
			if(!in_array(strtolower($value),$valid_account_types)) throw new Exception('The account type specified is invalid (account type: '.$account_type.')');
			$this->account_type = strtolower($value);
			break;
		
		case 'crew_affiliation_id':
			if($value != '') {
				$result = mydb::cxn()->query("SELECT count(id) as ids FROM crews WHERE id = ".mydb::cxn()->real_escape_string($value));
				$row = $result->fetch_assoc();
				if($row['ids'] != 1) throw new Exception('');
				$this->crew_affiliation_id = $value;
			}
			else $this->crew_affiliation_id = $value;
			break;
			
		case 'inactive':
			if(($value === 0) || ($value == false)) $this->inactive = 0;
			elseif(($value === 1) || ($value == true)) $this->inactive = 1;
			else throw new Exception('Attempted to set an invalid value for the user::inactive property');
			break;
		
		default:
			throw new Exception('user::set() attempted to set an invalid property: '.$var);
			break;
		}// End: switch()
	} // End: function set()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(isset($this->$var)) return $this->$var;
		else return false;
	} // End: function get()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: delete() ************************************************************************/
/*******************************************************************************************************************************/
	function delete() {
		$id = $this->id;
		
		// Gather information about the account being deleted...
		$result = mydb::cxn()->query("SELECT account_type, crew_affiliation_id FROM authentication WHERE id = ".$id);
		$row = $result->fetch_assoc();
		
		// Count the number of other administrative accounts for this crew
		$result = mydb::cxn()->query("SELECT count(*) as crew_admins FROM authentication WHERE crew_affiliation_id = ".$_SESSION['current_user']->get('crew_affiliation_id')." && account_type = 'crew_admin'");
		$row_admin_count = $result->fetch_assoc();
		$crew_admins_on_this_crew = $row_admin_count['crew_admins'];
		
		// Don't allow deletion if this account is unaffiliated with your crew (can't delete other crews' accounts, UNLESS you are GLOBAL ADMIN)
		if(($row['crew_affiliation_id'] != $_SESSION['current_user']->get('crew_affiliation_id') && ($_SESSION['current_user']->get('account_type')) != 'admin')) {
			throw new Exception('You can\'t delete the accounts of a different crew');}
			
		// Don't allow deletion if you are trying to delete an admin account that is not your own
		elseif(($row['account_type'] == 'crew_admin') && ($id != $_SESSION['current_user']->get('id'))) {
			throw new Exception('You cannot delete an administrative account that is not your own');}
		
		// Don't allow a crew_admin to delete their own account if it is the only administrative account for this crew (but a GLOBAL ADMIN can delete the last crew_admin)
		elseif(($crew_admins_on_this_crew < 2) && ($_SESSION['current_user']->get('account_type') != 'admin')) {
			throw new Exception('You cannot delete your account because you are the only administrator for your crew. Please create another admin account before deleting this one.');}
		
		else {
			// All conditions have been met - go ahead and delete the user account
/*
			$result = mydb::cxn()->query("DELETE FROM authentication WHERE id = ".$id);
			if(mydb::cxn()->error != "") throw new Exception('There was a problem deleting this user account: '.mydb::cxn()->error);
			else return 1;
*/
			//Instead of actually removing this account from the database, just mark it as INACTIVE.  It won't show up on the website
			// frontend, but it will still be in the database since the user ID is referenced by other tables to maintain a historical
			// record (e.g. rappels.confirmed_by).
			try {
			  $this->set('inactive',1);
			  $this->save();
			  return 1; // SUCCESS!
			} catch (Exception $e) {
				throw new Exception('That user account could not be deleted: '.$e->getMessage()); // Re-throw any exception that was thrown
				return 0;
			}
		}
		// If logic flow reaches this point, an exception has been thrown and the requested account deletion has not occurred. Exit with an error state.
		return 0;
	} // End: function delete()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: save() **************************************************************************/
/*******************************************************************************************************************************/

	function save() {
		// This function will commit the current USER OBJECT to the database
		
		// Any User can make changes to their own account (crewmember, crew_admin, admin, monitor)
		// An ADMIN can make changes to a CREWMEMBER, CREW_ADMIN or MONITOR account (password can only be set via password_reset, not explicitly assigned)
		// Note: A CREW ADMIN cannot make changes to crewmember account (other than a password reset)

		$missing_properties = array();
		if(!isset($this->firstname)) $missing_properties[] = "Firstname";
		if(!isset($this->lastname)) $missing_properties[] = "Lastname";
		if(!isset($this->password)) $missing_properties[] = "Password";
		if(!isset($this->email)) $missing_properties[] = "Email";
		if(!isset($this->account_type)) $missing_properties[] = "Account Type";
		
		if(sizeof($missing_properties) > 0) throw new Exception('The following information is needed before saving this User: '.implode(",",$missing_properties));
		
		if($this->crew_affiliation_id == "") $crew_affiliation_for_db = 'NULL';
		else $crew_affiliation_for_db = $this->crew_affiliation_id;
		
		// Determine if we are UPDATING an existing user or CREATING a new user
		if(isset($this->id) && ($this->id != '')) {
			$result = mydb::cxn()->query("SELECT count(id) as ids FROM authentication WHERE id = ".$this->id);
			$row = $result->fetch_assoc();
			if((int)$row['ids'] == 0) $this->create($this->firstname,$this->lastname,$this->password,$this->email,$this->account_type,$this->crew_affiliation_id);
			else {
				$query = "UPDATE authentication SET firstname = '".$this->firstname."', lastname = '".$this->lastname."', "
						."password = '".$this->password."', account_type = '".$this->account_type."', email = '".$this->email."', crew_affiliation_id = ".$crew_affiliation_for_db.", inactive = ".$this->inactive
						." WHERE id = ".$this->id;
				$result = mydb::cxn()->query($query);
				//echo "<br><br>\n\n".$query."<br><br>\n\n";
				if(mydb::cxn()->error != "") throw new Exception('There was a problem updating this user account: '.mydb::cxn()->error);
			}
		}
		else $this->create($this->firstname,$this->lastname,$this->password,$this->email,$this->account_type,$this->crew_affiliation_id);
		
		return 1; // Success
	} // End: function save()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: generate_code() PRIVATE *********************************************************/
/*******************************************************************************************************************************/

	public static function generate_code($length) {
		/* This function generates a string of random characters (uppercase/lowercase/numbers) of the specified length */
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$code = "";
		for($i=0;$i<$length-1;$i++) {
			$code .= $chars[mt_rand(0,61)];
		}
		return $code;
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: check_user_data() PRIVATE *******************************************************/
/*******************************************************************************************************************************/

	private function check_user_data($firstname, $lastname, $password, $email, $account_type, $crew_affiliation_id='NULL') {
		// This function checks for valid data
		/* Use try/throw/catch when calling this function */
		
		$result = mydb::cxn()->query("SELECT count(id) as ids FROM authentication WHERE email LIKE \"".$email."\" && id != ".$_SESSION['current_user']->get('id'));
		$row_email = $result->fetch_assoc();
/*
		$result = mydb::cxn()->query("SELECT instr(query, '".$email."') as idx, code FROM confirmation");
		while($row_confirmation = $result->fetch_assoc()) {
			if($row_confirmation['idx'] > 0) $existing_code = $row_confirmation['code'];
		}
*/
		$valid_account_types = array('admin','crew_admin','crewmember','observer');
		
		$result = mydb::cxn()->query("select distinct id from crews order by id");
		$crewlist = array();
		while($row_crews = $result->fetch_assoc()) {
			$crewlist[] = $row_crews['id'];
		}
		
		$result = mydb::cxn()->query("SELECT count(*) as crew_admins FROM authentication WHERE crew_affiliation_id = ".$_SESSION['current_user']->get('crew_affiliation_id')." && account_type = 'crew_admin'");
		$row_admin_count = $result->fetch_assoc();
		$crew_admins_on_this_crew = $row_admin_count['crew_admins'];
		
		if(!preg_match('/^[a-z\d_]{1,25}$/i', $firstname)) throw new Exception('Firstname must be 1 - 25 letters');
		elseif(!preg_match('/^[a-z\d_]{1,25}$/i', $lastname)) throw new Exception('Lastname must be 1 - 25 letters');
		elseif(!preg_match($_SESSION['password_pattern'],$password)) throw new Exception('Password must be 6 - 15 characters (no quotes or slashes allowed');
		elseif(!in_array($account_type,$valid_account_types)) throw new Exception('The account type specified is invalid (account type: '.$account_type.')');
		elseif(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) throw new Exception('Email must be of the form: yourname@yourhost.com');
		elseif($row_email['ids'] != 0)  throw new Exception('That email address is already in use!');
		elseif(!in_array($crew_affiliation_id,$crewlist) && ($account_type != 'monitor')) throw new Exception('The crew you selected does not exist');
		elseif(($crew_admins_on_this_crew >= $_SESSION['max_crew_admins_per_crew']) && ($account_type == "crew_admin")) throw new Exception('Your crew already has '.$crew_admins_on_this_crew.' administrative accounts');
		elseif(($crew_affiliation_id != $_SESSION['current_user']->get('crew_affiliation_id')) && ($_SESSION['current_user']->get('account_type') != 'admin') && ($account_type != 'monitor'))
			throw new Exception('You can only create crewmember accounts for your own crew');

		else {
			// All data has PASSED inspection
			return 1;
		}
		return 0; //An exception has been thrown
		
	} // End: function check_user_data()

/***************************************************************************************************/
/***************************************************************************************************/
	private function var_is_int($value) {
		// Returns TRUE if $value is an integer.
		// Returns FALSE otherwise.
		// This function will take any data type as input.
    	return ((string) $value) === ((string)(int) $value);
	} // End: function var_is_int()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: exists()  ***********************************************************************/
/*******************************************************************************************************************************/
	public static function exists($id) {
		// This function will check the database for a user account with the specified $id
		// Return TRUE if exactly one entry is found
		// Return FALSE otherwise
		
		$query = "SELECT count(id) as ids FROM authentication WHERE id = ".$id;
		$result = mydb::cxn()->query($query);
		
		$row = $result->fetch_assoc();
		
		if((int)$row['ids'] == 1) return true;
		else return false;
		
	} // End: public static function exists()
	
} // End: class user()

?>
