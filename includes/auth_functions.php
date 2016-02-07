<?php
// The caller must have already run 'require_once("/classes/mydb_class.php");' or else these functions will fail.
// require_once('../classes/mydb_class.php');

function check_access($area) {

	/*	INPUTS:
			$_SESSION['access_level']	: A comma-separated list of areas that the current user is allowed to access,
											i.e. "roster,crew_status,photos"

			$area						: A string that specifies which area is being accessed
		
		OUTPUTS:
			Returns either 1 or 0.
			1 : The user is allowed to access the area specified by $area
			0 : The user is NOT allowed to access the area specified by $area
	*/
	$access_granted = 0;
	
	$list = explode(',', $_SESSION['access_level']);
	foreach($list as $allowed) {
		if($area == $allowed) $access_granted = 1;
	}
	
	//Allow access for username 'coh' ONLY if logged in through the mobile portal (www.siskiyourappellers.com/m)
	//if(($_SESSION['mobile'] != 1) && ($_SESSION['username'] == 'coh')) $access_granted = 0;
	
	return $access_granted;
}

//---------------------------------------------------------------------------------------------------------
function login($username, $password) {
	$output = 0;
	$username = strtolower($username); //Make username case INSENSITIVE (password is still case sensitive)
	if(preg_match('/^[a-z\d_]{3,25}$/i', $username)) {
		if(preg_match('/^[a-z\d_]{3,25}$/i', $password)) {
			$query = "SELECT id, username, real_name, access_level FROM authentication WHERE username like '".$username."' && password like '".md5($password)."' LIMIT 1";

			$result = mydb::cxn()->query($query);
			$row = $result->fetch_assoc();

			if($row['username'] == $username) {
				$_SESSION['user_real_name'] = $row['real_name'];
				$_SESSION['logged_in'] = 1;
				$_SESSION['access_level'] = $row['access_level'];
				$_SESSION['username'] = $username;
				$_SESSION['auth_id'] = $row['id']; //NOTE: this bears NO RELATIONSHIP to 'crewmembers.id'
				$output = 1;
				
				// Redirect user to the original URL they tried to access, if any
				if($_SESSION['intended_location'] != '') {
					$intended_location = $_SESSION['intended_location'];
					$_SESSION['intended_location'] = '';
					header('location: '.$intended_location);
				}
			}
		}
		else $output = "Password must be 3 to 25 alphanumeric characters.";
	}
	else $output = "Username must be 3 to 25 alphanumeric characters.";
	
	return $output;
}

//---------------------------------------------------------------------------------------------------------
function add_user($username, $password, $real_name, $access_level) {
	/*	INPUTS:
			$username	:	A string of 3 to 25 alphanumeric characters
			$password	:	A string of 3 to 25 alphanumeric characters
			$real_name	:	A string of characters
			$access_level	:	A comma-separated string of areas that this user has access to
		
		OUTPUTS:
			Returns 1 on success
			Returns a description of the error on failure
	*/
	$output = 1;
	$instances = 0;
	if(preg_match('/^[a-z\d_]{3,25}$/i', $username)) {
		if(preg_match('/^[a-z\d_]{3,25}$/i', $password)) {
			$query = "SELECT id FROM authentication WHERE username like '".$username."'";
			$result = mydb::cxn()->query($query);
			while($row = $result->fetch_assoc()) $instances++;
			if($instances > 0) $output = "That username already exists!";
			
			if($output == 1) {
				$query = "INSERT INTO authentication (username, password, real_name, access_level) VALUES ('".$username."','".md5($password)."','".$real_name."','".$access_level."')";

				$result = mydb::cxn()->query($query);
				if(!result) $output = "Error adding user to the database: ".mydb::cxn()->error;
			}
		}
		else $output = "Password must be 3 to 25 alphanumeric characters.";
	}
	else $output = "Username must be 3 to 25 alphanumeric characters.";
	
	return $output;
}

//---------------------------------------------------------------------------------------------------------
function rm_user($id) {
	/*	INPUTS:
			$id	:	An integer referring to the id number of a user in the 'authentication' table of the database
		
		OUTPUTS:
			Returns 1 on success
			Returns a description of the error on failure
	*/
	$output = 1;
	
	$query = "DELETE FROM authentication WHERE id like '".$id."' LIMIT 1";
	$result = mydb::cxn()->query($query);
	if(!$result) $output = "Error removing user from the database: ".mydb::cxn()->error();

	return $output;
}

//---------------------------------------------------------------------------------------------------------
function edit_user($id, $real_name, $access_level) {
	/*	INPUTS:
			$id				:	An integer referring to the id number of a user in the 'authentication' table of the database
			$real_name		:	A string, the user's real name
			$access_level	:	A comma-separated string of areas that this user has access to

		OUTPUTS:
			Returns 1 on success
			Returns a description of the error on failure
	*/
	$output = 1;
	$query = "	UPDATE authentication
				SET real_name = '".mydb::cxn()->real_escape_string($real_name)."', access_level = '".$access_level."'
				WHERE id like '".$id."'";

	$result = mydb::cxn()->query($query);

	if(!result) $output = "Error updating user info: ".mydb::cxn()->error;

	return $output;
}

//---------------------------------------------------------------------------------------------------------
function change_password($old, $new1, $new2) {
	/*	INPUTS:
			$old	:	The user's old password
			$new1	:	The new (requested) password
			$new2	:	The new (requested) password again, to verify no typos

	OUTPUTS:
			Returns 1 on success
			Returns a description of the error on failure
	*/
	$output = 1;
	
	if(preg_match('/^[a-z\d_]{3,25}$/i', $new1)) {
		if($new1 == $new2) {
			$query = "SELECT password FROM authentication WHERE username like '".$_SESSION['username']."'";
			$result = mydb::cxn()->query($query);
			if(!$result) $output = "Error reading old password: ".mydb::cxn()->error;
			else $row = $result->fetch_assoc();
			
			if($row['password'] == md5($old)) {
				$query = "UPDATE authentication SET password = md5('".$new1."') WHERE username like '".$_SESSION['username']."'";
				$result = mydb::cxn()->query($query);
				if(!$result) $output = "Error changing password: ".mydb::cxn()->error;
			}
			else $output = "Incorrect old password.";
		}
		else $output = "New password #1 did not match new password #2... please re-enter.";
	}
	else $output = "Password must be 3 to 25 alphanumeric characters.";

	return $output;
}
?>
