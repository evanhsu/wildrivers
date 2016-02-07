<?php
	/*******************************************************************************************************/
	/* Copyright (C) 2012 Evan Hsu
       Permission is hereby granted, free of charge, to any person obtaining a copy of this software
	   and associated documentation files (the "Software"), to deal in the Software without restriction,
	   including without limitation the rights to use, copy, modify, merge, publish, distribute,
	   sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
	   furnished to do so, subject to the following conditions:

       The above copyright notice and this permission notice shall be included in all copies or
	   substantial portions of the Software.

       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
	   NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	   IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */
	/********************************************************************************************************/
require_once("includes/constants.php");
require_once("classes/mydb_class.php");


function check_access($required_access_level='guest', $only_crew=0) {

	/*	INPUTS:
			$_SESSION['current_user']['account_type']	: The account type for the current user ("admin", "crew_admin", "crewmember")
			$required_access_level						: A string that specifies the access level that is required to view the requested page
			$only_crew									: An integer that specifies whether the requested page contains crew-specific information
															that should only be accessible to users who belong to the crew in question. If this
															input is set to 0, the page does NOT contain crew-specific information and it will be
															accessible to users of any crew if they meet the required access level.
															If this input is a non-zero integer the function will check on $_SESSION['current_user']->crew_affiliation_id
															to see if the current user belongs to the crew specified in the function call.  Users who belong to a
															different crew will not be allowed access.
		OUTPUTS:
			Returns either 1 or 0.
			1 : The user is allowed to access the requested page
			0 : The user is NOT allowed to access the requested page
	*/
	
	switch($required_access_level) {
	case "guest":
		$req_al = 0;
		break;
	case "crewmember":
		$req_al = 1;
		break;
	case "crew_admin":
		$req_al = 2;
		break;
	case "admin":
		$req_al = 3;
		break;
	}
	
	switch($_SESSION['current_user']->account_type) {
	case "guest":
		$usr_al = 0;
		break;
	case "crewmember":
		$usr_al = 1;
		break;
	case "crew_admin":
		$usr_al = 2;
		break;
	case "admin":
		$usr_al = 3;
		break;
	}
	
	$access_granted = false;
	
	if($usr_al >= $req_al) $access_granted = true;
	if($only_crew != 0) {
		$result = mydb::cxn()->query("SELECT is_academy, region FROM crews WHERE id = ".$only_crew);
		$row = $result->fetch_assoc();
		if($row['is_academy'] == 1) {/* If this 'crew' is an Academy, allow any user IN THE SAME REGION with the proper ACCOUNT TYPE */
			if($_SESSION['current_user']->region != $row['region']) $access_granted = false;
		}
		elseif(($only_crew != $_SESSION['current_user']->crew_affiliation_id) && ($_SESSION['current_user']->account_type != "admin")) $access_granted = false;
	}

	return $access_granted;
}

//---------------------------------------------------------------------------------------------------------

function login($email, $password) {
	// This function should be called from within a throw/catch block.
	// It will process a login attempt with the specified email(username)/password pair and
	// throw an exception if the login fails for any reason.
	
	$email = strtolower($email); //Make username case INSENSITIVE (password is still case sensitive)
	$password = mydb::cxn()->real_escape_string($password);
	
	
	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) throw new Exception('Username must be a valid email address<br>(i.e. yourname@yourhost.com)');
	if(!preg_match($_SESSION['password_pattern'],$password)) throw new Exception('That login is invalid.'); //Password must be 6 - 15 characters (no quotes or slashes allowed
	
	$query = "SELECT id, email, password FROM authentication "
//			."WHERE email like '".$email."' && password = SHA1(CONCAT(salt,'".$password."')) LIMIT 1";
			."WHERE email like '".$email."' && password = SHA1(CONCAT('".$email."','".$password."')) && (inactive = 0) LIMIT 1";

	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->affected_rows < 1) throw new Exception('That login is incorrect.');
	$row = $result->fetch_assoc();

	if($row['email'] == $email) {
		// The 'user' class will throw its own exceptions if needed
		$_SESSION['logged_in'] = 1;
		$_SESSION['current_user'] = new user;
		$_SESSION['current_user']->load($row['id']);
		$_SESSION['current_user']->update_last_login_timestamp();	// Update the 'last_login' timestamp with the current date/time
		$output = "Logged in as ".$_SESSION['current_user']->get('email');
	}
	
	if(!isset($output)) throw new Exception('An unknown error occurred while logging in. Please try again.');
	return $output;
} // End: function login()

//---------------------------------------------------------------------------------------------------------

function add_user($username, $password, $real_name, $access_level) {

	/*	INPUTS:
			$username	:	A string of 3 to 25 alphanumeric characters
			$password	:	A string of 6 to 15 alphanumeric characters
			$real_name	:	A string of characters
			$access_level	:	A comma-separated string of areas that this user has access to

		OUTPUTS:
			Returns 1 on success
			Returns a description of the error on failure
	*/

	$output = 1;
	$instances = 0;
	
	if(preg_match('/^[a-z\d_]{3,25}$/i', $username)) {
		if(!preg_match($_SESSION['password_pattern'],$password)) {
			$query = "SELECT id FROM authentication WHERE username like '".$username."'";
			$result = mydb::cxn()->query($query);
			while($row = $result->fetch_assoc()) $instances++;

			if($instances > 0) $output = "That username already exists!";
			
			if($output == 1) {
				$query = "INSERT INTO authentication (username, password, real_name, access_level) VALUES ('".$username."',SHA1(CONCAT('".$username."','".$password."')),'".$real_name."','".$access_level."')";
				$result = mydb::cxn()->query($query);
				if(!result) $output = "Error adding user to the database: ".mydb::cxn()->error;
			}
		}
		else $output = "Password must be 6 to 15 alphanumeric characters.";
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

	if(!$result) $output = "Error removing user from the database: ".mydb::cxn()->error;
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

			$query = "SELECT password, SHA1(CONCAT('username','".$old."')) AS proposed_password FROM authentication WHERE username like '".$_SESSION['username']."'";
			$result = mydb::cxn()->query($query);

			if(!$result) $output = "Error reading old password: ".mydb::cxn()->error;
			else $row = $result->fetch_assoc();

			if($row['password'] == $row['proposed_password']) {
				$query = "UPDATE authentication SET password = SHA1(CONCAT('".strtolower($_SESSION['username'])."','".$new1."')) WHERE username like '".$_SESSION['username']."'";
				$result = mydb::cxn()->query($query);
				
				if(!$result) $output = "Error changing password: ".mydb::cxn()->error;
			}

			else $output = "Incorrect old password.";
		}

		else $output = "New password #1 did not match new password #2... please re-enter.";
	}

	else $output = "Password must be 6 to 15 alphanumeric characters.";

	return $output;
}

function store_intended_location() {
	$_SESSION['intended_location'] = $_SERVER['REQUEST_URI'];
	$_SESSION['intended_location_countdown'] = 2;
}

function consult_intended_location() {
	// This function should be called at the top of every page where users can be redirected to a login page.
	// The function will return TRUE if the user should be redirected BACK to the page they originally requested.
	// The function will return FALSE if the user has visited another page since being redirected.
	if(!isset($_SESSION['intended_location_countdown']) || ($_SESSION['intended_location_countdown'] < 1)) {
		$_SESSION['intended_location'] = "";
		$_SESSION['intended_location_countdown'] = 0;
		return false;
	}
	else {
		$_SESSION['intended_location_countdown']--;
		return true;
	}
}

?>
