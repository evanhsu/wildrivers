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
	include('includes/php_doc_root.php');
	
	require_once("classes/mydb_class.php");
	require_once("classes/user_class.php");
	require_once("classes/email_class.php");
	require_once("classes/hrap_class.php");
	require_once("classes/crew_class.php");
	
	session_name('raprec');
	session_start();
	
	require("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	

	// Make sure this user is allowed to access this page
	if(($_SESSION['logged_in'] == 1)/* && check_access("crew_admin",$_GET['crew'])*/) {
		// ACCESS GRANTED!
	}
	else {
		// ACCESS DENIED!
		store_intended_location();  //Redirect user back to their intended location after they log in
		header('location: index.php');
	}

/*********************************************************************************************************************/
/*********************************************************************************************************************/
/*********************************************************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Account Management :: RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="account management, password, user, fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, records, history" />
<meta name="Description" content="The National Rappel Record Website - This page is used to modify user accounts." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if($_SESSION['mobile'] == 1) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

</head>

<body>
    <div id="banner_left"><a href="index.php"><img src="images/raprec_banner_left.jpg" style="border:none" alt="RapRec Central Logo" /></a></div>
    <div id="banner_right"><a href="index.php"><img src="images/raprec_banner_right.jpg" style="border:none" alt="RapRec Central" /></a></div>
	
    <div id="left_sidebar">
    	<?php make_menu(); ?>
    </div>
    <div id="location_bar"><?php echo $_SESSION['location_bar']; ?></div>
    
    <div id="content" style="text-align:center">
    <?php
		$msg = "";
	    isset($_GET['function']) ? $function = $_GET['function'] : $function = false;
		/*-------------------------------------------------------------------------------------------------*/
		switch($function) {
		case 'create_account':
			if(isset($_POST['email'])) {
				try {
					if(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin')) != true) {
						throw new Exception('You don\'t have permission to create new accounts');
					}
					elseif(($_POST['account_type'] == 'crew_admin') && crew_has_max_admins($_POST['crew_affiliation_id'])) {
						throw new Exception('Your crew already has the maximum number of crew-admin accounts');
					}
					update_form_memory_from_post('create_account');
					$new_password = user::generate_code(8); // Generate an 8-character password
					
					$new_user = new user;
					$new_user->create($_POST['firstname'],$_POST['lastname'],$new_password,$_POST['email'],$_POST['account_type'],$_POST['crew_affiliation_id']);
					$message = new email('new_account',$new_user->get('email'),$new_password);
					$message->send();
					
					clear_form_memory('create_account');
					show_account_creation_form('Account created successfully!');
					
				} catch(Exception $e) {
					show_account_creation_form($e->getMessage());
				}
			}
			elseif(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin'))) {
				clear_form_memory('create_account');
				show_account_creation_form();
			}
			else show_edit_account_form();
			break;
		/*-------------------------------------------------------------------------------------------------*/
		case 'edit_account':
			// Any User can make changes to his own account (crewmember, crew_admin, admin, monitor)
			// Additionally, an ADMIN can make changes to any CREWMEMBER, CREW_ADMIN or MONITOR account
			// NOTE: crew_admin cannot make changes to crewmember account (other than a password reset)
			if(isset($_POST['user_id'])) {
				try {	
					update_form_memory_from_post('edit_account');

					$user = new user;
					$user->load($_POST['user_id']);
					$user->set('firstname',$_POST['firstname']);
					$user->set('lastname',$_POST['lastname']);
					$user->set('email',$_POST['email']);
					if(isset($_POST['account_type'])) $user->set('account_type',$_POST['account_type']);
					if(isset($_POST['crew_affiliation_id'])) $user->set('crew_affiliation_id',$_POST['crew_affiliation_id']);
					
					if(isset($_POST['password1'])) {
						if($_POST['password1'] != $_POST['password2']) throw new Exception('Your passwords did not match. Please enter your new password carefully in both password fields.');
						elseif($_POST['password1'] != "") $user->set('password',$_POST['password1']);  //If password was typed identically in the 2 password fields, update it. If fields where both blank, retain old password
					}
					
					//Check the current user's privileges before committing these changes
					if(($_SESSION['current_user']->get('account_type') != 'admin') && ($user->get('id') != $_SESSION['current_user']->get('id'))) throw new Exception('You may only make changes to your own account.');
					
					process_crew_affiliation_change_if_necessary($user);
					$user->save();

					clear_form_memory('edit_account');
					if($user->get('id') == $_SESSION['current_user']->get('id')) $_SESSION['current_user']->load($user->get('id')); //Update the current_user for this session if the user has just changed his own user account
					show_edit_account_form('Account successfully updated!');

				} catch(Exception $e) {
					show_edit_account_form($e->getMessage());
				}
			}
			elseif(($_SESSION['current_user']->get('account_type') == 'admin') && !isset($_GET['user_id'])) show_account_selection_menu();
			else show_edit_account_form();
			clear_form_memory('edit_account');
			break;
		/*-------------------------------------------------------------------------------------------------*/
		case 'promote_user':
			if(isset($_POST['user_id']) && (in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin')) == true)) {
				try {
					perform_user_promotion();
					show_account_selection_menu('User was successfully promoted to a Crew Admin!');
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			elseif(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin'))) {
				try {
					if(isset($_GET['user_id'])) show_user_promotion_confirmation();
					else show_account_selection_menu();
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			else show_edit_account_form(); //Current user is not an admin or crew_admin
			break;
		/*-------------------------------------------------------------------------------------------------*/
		case 'demote_user':
			if(isset($_POST['user_id']) && (in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin')) == true)) {
				try {
					perform_user_demotion();
					show_account_selection_menu('User was successfully demoted to a Crewmember!');
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			elseif(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin'))) {
				try {
					if(isset($_GET['user_id'])) show_user_demotion_confirmation();
					else show_account_selection_menu();
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			else show_edit_account_form(); //Current user is not an admin or crew_admin
			break;
		/*-------------------------------------------------------------------------------------------------*/
		case 'reset_password':
			if(isset($_POST['user_id'])) {
				try {
					perform_password_reset();
					show_account_selection_menu('Password successfully reset! An email has been sent to the account holder');
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			elseif(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin'))) {
				try {
					if(isset($_GET['user_id'])) show_password_reset_confirmation();
					else show_account_selection_menu();
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			else show_edit_account_form(); //Current user is not an admin or crew_admin
			break;
		/*-------------------------------------------------------------------------------------------------*/
		case 'remove_account':
			if(isset($_POST['user_id'])) {
				try {
					if($_SESSION['current_user']->get('id') == $_POST['user_id']) {
						perform_user_removal();
						$_SESSION['logged_in'] == 0; //This user just deleted their own account.  Log out.
						echo "<h2>You have deleted your own account!</h2>\n";
					}
					elseif(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin'))) {
						//An admin or crew_admin is removing someome else's account
						perform_user_removal();
						show_account_selection_menu('User account successfully removed!');
					}
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			elseif(in_array($_SESSION['current_user']->get('account_type'), array('admin','crew_admin','crewmember'))) {
				try {
					if(isset($_GET['user_id'])) show_user_removal_confirmation();
					else show_account_selection_menu();
				} catch(Exception $e) {
					show_account_selection_menu($e->getMessage());
				}
			}
			else show_edit_account_form(); //User is not removing own account, and is not an admin or crew_admin.
			break;
		/*-------------------------------------------------------------------------------------------------*/
		default:
			if(in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) show_account_selection_menu();
			else show_edit_account_form();
			break;
		} // End: switch($function)
    	/*-------------------------------------------------------------------------------------------------*/
	?>
    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>


<?php 
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_account_management_menu() **************************************************/
/*******************************************************************************************************************************/
function show_account_selection_menu($msg="") {
	// This function is only accessible by ADMIN and CREW_ADMIN account holders.
	// This function displays a list of user accounts (appropriate to the scope of this user's admin privileges)
	// For ADMINs, 5 functions are available for each account in the list: edit, promote (crewmember becomes crew_admin), demote, remove or password reset
	// For CREW_ADMINs, a password reset, delete, AND promote are available for the crewmember accounts in the list
	$text =	 "<br>\n"
			."<div style=\"width:100%;text-align:left;\">\n"
			."<h1>Account Management</h1>\n"
			."</div><br>\n";
	
	if($msg != "") $text .= "<div class=\"error_msg\">".$msg."</div><br /><br />\n\n";
	
	//If the current user is an ADMIN, show a list of all non-admin user accounts
	if($_SESSION['current_user']->get('account_type') == 'admin') {
		$db_scope = "WHERE authentication.account_type <> 'admin'"; 
	}
	//If the current user is a CREW_ADMIN, show a list of all CREWMEMBER and CREW_ADMIN user accounts
	elseif($_SESSION['current_user']->get('account_type') == 'crew_admin') {
		$db_scope = "WHERE authentication.account_type IN ('crewmember','crew_admin') AND authentication.crew_affiliation_id = "
					.$_SESSION['current_user']->get('crew_affiliation_id');
	}
	else {
		//This condition should never be reached, but just in case, force the db query to return a NULL set
		$db_scope = "WHERE 1=2";
	}
	
	
	$query = "	SELECT
				CONCAT(authentication.firstname,' ',authentication.lastname) as name,
				authentication.id,
				authentication.email,
				authentication.account_type,
				authentication.crew_affiliation_id as crew_affiliation_id,
				crews.name as crew_affiliation_name,
				crews.region as region
				
				FROM
				authentication LEFT OUTER JOIN crews ON authentication.crew_affiliation_id = crews.id 
				
				".$db_scope." AND authentication.inactive = 0
				
				ORDER BY crews.region,crews.name,authentication.firstname,authentication.lastname";

	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->affected_rows <= 0) $text .= "<div class=\"error_msg\">There are no user accounts to display!</div>\n";
	else {
		$text .= "<table class=\"alternating_rows\" style=\"margin:0 auto 0 auto; border:2px solid #555555; width:100%;\">\n";
		$last_region = "0";
		$last_crew = "";
		$current_row = 0;
		while($row = $result->fetch_assoc()) {
			$current_row++;
			if($current_row % 2 == 0) $rowclass = "class=\"evn\"";
			else $rowclass = "class=\"odd\"";
			
			if($row['crew_affiliation_id'] != $last_crew) {
				$text .= "<tr><td colspan=\"4\" style=\"height:1.5em;\"></td></tr>\n";
				if($row['region'] != $last_region) {
					$text .= "<tr><td colspan=\"4\" style=\"font-size:1.8em; color:#555555;\">Region ".$row['region']."</td></tr>\n";
				}
				$text .= "<tr style=\"background-color:#fff;\"><td colspan=\"4\" style=\"font-size:1.2em; color:#888;\">".ucwords($row['crew_affiliation_name'])."</td></tr>\n";
				$text .= "<tr><th>Name</th><th>Email</th><th>Account Type</th><th>Functions</th></tr>\n";
			}
			$function_list = "<a href=\"".$_SERVER['PHP_SELF']."?function=reset_password&user_id=".$row['id']."\" title=\"Reset this user's password\">Reset</a>";
			if($row['account_type'] == "crewmember") $function_list .= " | <a href=\"".$_SERVER['PHP_SELF']."?function=promote_user&user_id=".$row['id']."\" title=\"Promote this user to a Crew Admin\">Promote</a>";
			if($row['account_type'] == "crew_admin") $function_list .= " | <a href=\"".$_SERVER['PHP_SELF']."?function=demote_user&user_id=".$row['id']."\" title=\"Demote this user to a Crewmember\">Demote</a>";
			if($_SESSION['current_user']->get('account_type') == 'admin') $function_list .= " | <a href=\"".$_SERVER['PHP_SELF']."?function=edit_account&user_id=".$row['id']."\" title=\"Edit this user's info\">Edit</a>";
			if(in_array($_SESSION['current_user']->get('account_type'), array('crew_admin','admin'))) $function_list .= " | <a href=\"".$_SERVER['PHP_SELF']."?function=remove_account&user_id=".$row['id']."\" title=\"Remove this user account\">Delete</a>";
			$text .= "<tr ".$rowclass."><td>".$row['name']."</td><td>".$row['email']."</td><td>".ucwords(str_replace("_"," ",$row['account_type']))."</td><td>".$function_list."</td></tr>\n";
			
			$last_region = $row['region'];
			$last_crew = $row['crew_affiliation_id'];
		}
		$text .= "</table>\n";
	}
	
	echo $text;
} // End: show_account_management_menu()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_edit_account_form() ********************************************************/
/*******************************************************************************************************************************/
function show_edit_account_form($msg = false) {
	$password_field = "";
	$crew_affiliation_field = "";
	$account_type_field = "<input type=\"hidden\" name=\"account_type\" value=\"".$_SESSION['current_user']->get('account_type')."\">\n";
	$user_id = "";
	
	// Determine whether this user is allowed to edit other people's accounts, and if so which account are we changing?
	if(($_SESSION['current_user']->get('account_type') == 'admin') && isset($_GET['user_id']) && ($_GET['user_id'] != $_SESSION['current_user']->get('id'))) {
		$user_to_edit = new user;
		$user_to_edit->load($_GET['user_id']); //The load function will throw an error if needed
		$user_id = $user_to_edit->get('id');
		$account_type_field = "<input type=\"hidden\" name=\"account_type\" value=\"".$user_to_edit->get('account_type')."\">\n";
	}
	else {
		//If this user is editing his own account, show the password field
		$user_id = $_SESSION['current_user']->get('id');
		$password_field = "<tr><td>New Password:</td><td style=\"text-align:left;\"><input type=\"password\" name=\"password1\" value=\"\"></td></tr>\n"
						."<tr><td>Re-Enter Password:</td><td style=\"text-align:left;\"><input type=\"password\" name=\"password2\" value=\"\"></td></tr>\n";
	}

	if(!isset($_SESSION['form_memory']['edit_account']) || $_SESSION['form_memory']['edit_account']['user_id'] == "") {
		update_form_memory_from_user_id('edit_account',$user_id);
	}
	
	//Determine whether to show the account_type field, and which account types to list
	if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
		$account_type_field = "<tr><td>Account Type:</td><td style=\"text-align:left;\"><select name=\"account_type\">"
								."<option value=\"crew_admin\" selected=\"selected\">Crew Admin</option>"
								."<option value=\"crewmember\">Crewmember</option>"
								."</select></td></tr>\n";
	}

	//Determine whether to show the crew_affiliation field
	if(in_array($_SESSION['current_user']->get('account_type'),array('crewmember','crew_admin')) || ($_SESSION['current_user']->get('account_type') == 'admin' && $_SESSION['current_user']->get('id') != $user_id)) {
		// Crew Affiliation is changeable if the current user is a crewmember or crew_admin.
		// Also, if current user is an ADMIN editing somebody else's account (ADMIN's cannot change their own crew affiliation because they are not affiliated with a crew)
		$query = "SELECT id, name, region FROM crews ORDER BY region,name";
		$result = mydb::cxn()->query($query);
		$crew_list = "<select name=\"crew_affiliation_id\">\n";
		$last_region = "";
		while($row = $result->fetch_assoc()) {
			if(!strpos(strtolower($row['name']),'academy')) {
				$selected = "";
				if(($row['region'] != $last_region) && ($last_region != "")) $crew_list .= "</optgroup>\n";
				if($row['region'] != $last_region) $crew_list .= "<optgroup label=\"Region ".$row['region']."\">\n";
				if($row['id'] == $_SESSION['form_memory']['edit_account']['crew_affiliation_id']) $selected = "selected=\"selected\"";
				$crew_list .= "<option value=\"".$row['id']."\" ".$selected.">".ucwords($row['name'])."</option>\n";
				$last_region = $row['region'];
			}
		}
		$crew_list .= "</select>\n";
		$crew_affiliation_field = "<tr><td>Crew Affiliation:";
		if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
			$crew_affiliation_field .= "<br><small style=\"color:#888;\">Changes to your<br />crew affiliation will<br />not be final until<br />they are approved by<br />the requested crew.</small>";
		}
		$crew_affiliation_field .= "</td><td style=\"text-align:left;\">".$crew_list."</td></tr>\n";
	}
	
	$text =	 "<br>\n"
			."<div style=\"width:400px;text-align:left;margin:0 auto 0 auto;\">\n"
			."<h1>Account Management</h1><br />\n"
			."Edit User Account</div><br>\n";
	
	if($msg) $text .= "<div class=\"error_msg\" style=\"width:400px;text-align:center;margin:0 auto 0 auto;\">".$msg."</div><br />\n";
	
	$text .= "<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"post\">\n"
			."<table style=\"margin:0 auto 0 auto;border:2px solid #666;width:400px;\">\n"
			."<input type=\"hidden\" name=\"user_id\" value=\"".$user_id."\">\n"
			."<tr><td>First Name:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"firstname\" value=\"".$_SESSION['form_memory']['edit_account']['firstname']."\"></td></tr>\n"
			."<tr><td>Last Name:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"lastname\" value=\"".$_SESSION['form_memory']['edit_account']['lastname']."\"></td></tr>\n"
			."<tr><td>Email:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"email\" value=\"".$_SESSION['form_memory']['edit_account']['email']."\" style=\"width:250px;\"></td></tr>\n"
			.$password_field
			.$account_type_field
			.$crew_affiliation_field
			."<tr><td></td><td style=\"text-align:left;\"><input type=\"submit\" value=\"Update\"></td></tr>\n"
			."</table>\n"
			."</form>\n\n";
	
	echo $text;
} // End: show_edit_account_form()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_account_creation_form() ****************************************************/
/*******************************************************************************************************************************/
function show_account_creation_form($msg = false) {
	
	if(!in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) throw new Exception('You are not authorized to view this page');
	
	$account_type_field = "<tr><td>Account Type:</td><td style=\"text-align:left;\"><select name=\"account_type\">"
							."<option value=\"crewmember\" selected=\"selected\">Crewmember</option>"
							."<option value=\"crew_admin\">Crew Admin</option>"
							/*."<option value=\"admin\" selected=\"selected\">Admin</option>"*/
							."</select></td></tr>\n";
	
	if($_SESSION['current_user']->get('account_type') == 'admin') {
		$query = "SELECT id, name, region FROM crews ORDER BY region,name";
		$result = mydb::cxn()->query($query);
		$crew_list = "<select name=\"crew_affiliation_id\">\n";
		$last_region = "";
		while($row = $result->fetch_assoc()) {
			if(!strpos(strtolower($row['name']),'academy')) {
				$selected = "";
				if(($row['region'] != $last_region) && ($last_region != "")) $crew_list .= "</optgroup>\n";
				if($row['region'] != $last_region) $crew_list .= "<optgroup label=\"Region ".$row['region']."\">\n";
				if($row['id'] == $_SESSION['form_memory']['create_account']['crew_affiliation_id']) $selected = "selected=\"selected\"";
				$crew_list .= "<option value=\"".$row['id']."\" ".$selected.">".ucwords($row['name'])."</option>\n";
				$last_region = $row['region'];
			}
		}
		$crew_list .= "</select>\n";
		$crew_affiliation_field = "<tr><td>Crew Affiliation:</td><td style=\"text-align:left;\">".$crew_list."</td></tr>\n";
	}
	else $crew_affiliation_field = "<input type=\"hidden\" name=\"crew_affiliation_id\" value=\"".$_SESSION['current_user']->get('crew_affiliation_id')."\">\n";
	
	$text =	 "<br>\n"
			."<div style=\"width:400px;text-align:left;margin:0 auto 0 auto;\">\n"
			."<h1>Account Management</h1><br />\n"
			."Create New User Account</div><br>\n";
	
	if($msg) $text .= "<div class=\"error_msg\" style=\"width:400px;text-align:center;margin:0 auto 0 auto;\">".$msg."</div><br />\n";
	
	$text .= "<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"post\">\n"
			."<table style=\"margin:0 auto 0 auto;border:2px solid #666;width:400px;\">\n"
			."<tr><td>First Name:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"firstname\" value=\"".$_SESSION['form_memory']['create_account']['firstname']."\"></td></tr>\n"
			."<tr><td>Last Name:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"lastname\" value=\"".$_SESSION['form_memory']['create_account']['lastname']."\"></td></tr>\n"
			."<tr><td>Email:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"email\" value=\"".$_SESSION['form_memory']['create_account']['email']."\" style=\"width:250px;\"></td></tr>\n"
			.$account_type_field
			.$crew_affiliation_field
			."<tr><td></td><td style=\"text-align:left;\"><input type=\"submit\" value=\"Create Account\"></td></tr>\n"
			."</table>\n"
			."</form>\n\n";
	
	echo $text;
} // End: show_account_creation_form()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_password_reset_confirmation() **********************************************/
/*******************************************************************************************************************************/
function show_password_reset_confirmation() {
	// Check that the current user has permission to reset the requested user's password (crew_affiliation_id and account_type)
	if(!in_array($_SESSION['current_user']->get('account_type'), array('crew_admin','admin'))) throw new Exception('You are not authorized to use this page');
	if(!user::exists($_GET['user_id'])) throw new Exception('The requested user does not exist');
	$id = $_GET['user_id'];
	
	if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
		// Make sure the user being reset is on the same crew as this crew admin
		$query = "SELECT count(id) as ids FROM authentication WHERE crew_affiliation_id = ".$_SESSION['current_user']->get('crew_affiliation_id')." AND id = ".mydb::cxn()->real_escape_string($id);
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		if((int)$row['ids'] < 1) throw new Exception('You may only perform a password-reset for members of your own crew');
	}
	
	$text = "<br /><div class=\"error_msg\">Are you sure you want to reset the password for this account?</div><br />\n"
			."<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"post\"><input type=\"hidden\" name=\"user_id\" value=\"".$id."\"><input type=\"submit\" value=\"Yes\">\n"
			."</form>\n";
	
	echo $text;
	
	return 1;
} // End: show_password_reset_confirmation()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: perform_password_reset() ********************************************************/
/*******************************************************************************************************************************/
function perform_password_reset() {
	if(!isset($_POST['user_id'])) throw new Exception('You must specify a user to reset');
	if(!user::exists($_POST['user_id'])) throw new Exception('The requested user does not exist!');
	
	$new_password = user::generate_code(8); // Uppercase, Lowercase, and Digits - 8 characters long
	$user = new user;
	$user->load($_POST['user_id']);
	$user->set('password',$new_password);
	
	$user->save();
	
	$message = new email('password_reset',$user->email,$new_password);
	$message->send();
	
	return 1;
} // End: perform_password_reset()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_user_promotion_confirmation() **********************************************/
/*******************************************************************************************************************************/
function show_user_promotion_confirmation() {
	if(!in_array($_SESSION['current_user']->get('account_type'), array('crew_admin','admin'))) throw new Exception('You are not authorized to use this page');
	if(!user::exists($_GET['user_id'])) throw new Exception('The requested user does not exist!');
	$id = $_GET['user_id'];
	
	if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
		// Make sure the user being reset is on the same crew as this crew admin
		$query = "SELECT count(id) as ids FROM authentication WHERE crew_affiliation_id = ".$_SESSION['current_user']->get('crew_affiliation_id')." AND id = ".mydb::cxn()->real_escape_string($id);
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		if((int)$row['ids'] < 1) throw new Exception('You may only promote members of your own crew');
	}
	
	// Make sure this crew isn't maxed out on Crew Admins...
	if(crew_has_max_admins($id)) throw new Exception('This crew has already reached its maximum number of Crew Admins.');
	
	$result = mydb::cxn()->query("SELECT COUNT(id) as ids FROM authentication WHERE account_type = 'crewmember' AND id = ".$id);
	$row = $result->fetch_assoc();
	
	if($row['ids'] < 1) throw new Exception('The requested user is already an Admin');
	
	$text = "<br /><div class=\"error_msg\">Are you sure you want to promote<br />this user to a Crew Admin?</div><br />\n"
			."<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"post\"><input type=\"hidden\" name=\"user_id\" value=\"".$id."\">\n"
			."<input type=\"submit\" value=\"Yes\" style=\"\"></form>";
	
	echo $text;
	
	return 1;
} // End: show_user_promotion_confirmation()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: perform_user_promotion() ********************************************************/
/*******************************************************************************************************************************/
function perform_user_promotion() {
	if(!isset($_POST['user_id'])) throw new Exception('You must specify a user to promote');
	if(!user::exists($_POST['user_id'])) throw new Exception('The requested user does not exist!');
	if(crew_has_max_admins($_POST['user_id'])) throw new Exception('The requested crew has already met its maximum number of Crew Admins.');
	
	$user = new user;
	$user->load($_POST['user_id']);
	$user->set('account_type','crew_admin');
	$user->save();
	
	return 1;
} // End: perform_user_promotion()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_user_demotion_confirmation() ***********************************************/
/*******************************************************************************************************************************/
function show_user_demotion_confirmation() {
	if(!in_array($_SESSION['current_user']->get('account_type'), array('crew_admin','admin'))) {
		throw new Exception('You are not authorized to use this page');
	}
	if(!user::exists($_GET['user_id'])) {
		throw new Exception('The requested user does not exist!');
	}
	
	$id = $_GET['user_id'];
	$user = new user;
	$user->load($id);

	if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
		// Make sure the user being demoted is on the same crew as this crew admin
		$query = "SELECT count(id) as ids FROM authentication WHERE crew_affiliation_id = ".$_SESSION['current_user']->get('crew_affiliation_id')." AND id = ".mydb::cxn()->real_escape_string($id);
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		if((int)$row['ids'] < 1) throw new Exception('You may only demote members of your own crew');
	}

	$text = "<br /><div class=\"error_msg\">";
	
	$result = mydb::cxn()->query("SELECT COUNT(id) as ids FROM authentication WHERE account_type = 'crewmember' AND id = ".$id);
	$row = $result->fetch_assoc();
	if($row['ids'] > 0) throw new Exception('The requested user is already a Crewmember');
	
	$result = mydb::cxn()->query("SELECT COUNT(id) as ids FROM authentication WHERE account_type = 'crew_admin' AND crew_affiliation_id = ".$user->get('crew_affiliation_id'));
	$row = $result->fetch_assoc();
	if((int)$row['ids'] == 1) $text .= "You are demoting this crew's last Crew Admin!<br /><br />\n";
	
	$text .= "Are you sure you want to demote<br />this user to a Crewmember?</div><br />\n"
			."<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"post\"><input type=\"hidden\" name=\"user_id\" value=\"".$id."\">\n"
			."<input type=\"submit\" value=\"Yes\" style=\"\"></form>";
	
	echo $text;
	
	return 1;
} // End: show_user_demotion_confirmation()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: perform_user_demotion() *********************************************************/
/*******************************************************************************************************************************/
function perform_user_demotion() {
	if(!isset($_POST['user_id'])) throw new Exception('You must specify a user to demote');
	if(!user::exists($_POST['user_id'])) throw new Exception('The requested user does not exist!');
	
	$user = new user;
	$user->load($_POST['user_id']);
	$user->set('account_type','crewmember');
	$user->save();
	
	return 1;
} // End: perform_user_demotion()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_user_removal_confirmation() ************************************************/
/*******************************************************************************************************************************/
function show_user_removal_confirmation() {
	if(!in_array($_SESSION['current_user']->get('account_type'), array('crew_admin','admin'))){
		 //throw new Exception('You are not authorized to use this page');
		 
		 //Permissions are checked prior to calling this function
	}
	if(!user::exists($_GET['user_id'])) {
		throw new Exception('The requested user does not exist!');
	}
	
	$id = $_GET['user_id'];
	$user = new user;
	$user->load($id);

	if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
		// Make sure the user being removed is on the same crew as this crew admin
		$query = "SELECT count(id) as ids FROM authentication WHERE crew_affiliation_id = ".$_SESSION['current_user']->get('crew_affiliation_id')
				." AND id = ".mydb::cxn()->real_escape_string($id);
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		if((int)$row['ids'] < 1) {
			throw new Exception('You may only remove user accounts belonging to members of your own crew');
		}
	}
	elseif($_SESSION['current_user']->get('account_type') == 'crewmember') {
		// Make sure the user is removing his own account if he is a 'crewmember'
		if($_SESSION['current_user']->get('id') != $_GET['user_id']) {
			throw new Exception('You may only remove your own user account');
		}
	}

	$text = "<br /><div class=\"error_msg\">";
	
	$text .= "Are you sure you want to remove<br />this user account?</div><br />\n"
			."<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"post\"><input type=\"hidden\" name=\"user_id\" value=\"".$id."\">\n"
			."<input type=\"submit\" value=\"Yes\" style=\"\"></form>";
	
	echo $text;
	
	return 1;
	
} // End: show_user_removal_confirmation

/*******************************************************************************************************************************/
/*********************************** FUNCTION: perform_user_removal() **********************************************************/
/*******************************************************************************************************************************/
function perform_user_removal() {
	// User accounts are not actually deleted from the database.  They are flagged as INACTIVE because the user ID is referenced
	// by other tables that need to maintain a historical record of actions performed by that user (e.g. rappels.confirmed_by).
	if(!isset($_POST['user_id'])) throw new Exception('You must specify a user account to remove!');
	if(!user::exists($_POST['user_id'])) throw new Exception('The requested user account does not exist!');
	
	$user = new user;
	$user->load($_POST['user_id']);
	$user->set('inactive',1);
	$user->save();
	
	return 1;
} // End: perform_user_removal

/*******************************************************************************************************************************/
/*********************************** FUNCTION: process_crew_affiliation_change_if_necessary() **********************************/
/*******************************************************************************************************************************/
function process_crew_affiliation_change_if_necessary($user) {
	// This function will determine whether or not to send an email to the CREW ADMINs of the specified crew.
	// An email will be sent the following conditions are met:
	//		A Crew Admin is attempting to modify his own crew_affiliation
	//		AND the requested crew has not reached its max number of crew admins
	// 
	// Note: an ADMIN can change the crew_affiliation of a CREW_ADMIN without generating a confirmation email
	// The input ($user) is a USER OBJECT containing the requested user account changes
	
	$email_sent = false;
	$email_needed=false;
	
	if(($user->get('account_type') == 'crew_admin') ) {
		$result = mydb::cxn()->query("SELECT crew_affiliation_id FROM authentication WHERE id = ".$user->get('id'));
		$row = $result->fetch_assoc();
		$current_crew = $row['crew_affiliation_id'];
		$requested_crew=$user->get('crew_affiliation_id');
		if($current_crew != $requested_crew) {
			// The crew affiliation for this user object has changed from the database value
			// Count the number of crew admins on the destination crew.  If max number is already met, throw exception and the entire save operation is aborted
			if(crew_has_max_admins($requested_crew)) throw new Exception('The requested crew has already met its maximum number of Crew Admins.');
			
			if ($_SESSION['current_user']->get('account_type') != 'admin') {
				// Store a dB query in the confirmation table to change this user's crew_affiliation_id, send an email to the receiving crew admin
				// with a link to execute the stored query
				$email_needed = true;
				
				$code1 = user::generate_code(64);
				$code2 = user::generate_code(64);
				$stored_query = "UPDATE authentication SET crew_affiliation_id = ".$user->get('crew_affiliation_id')." WHERE id = ".$user->get('id')
								."; DELETE FROM confirmation WHERE code = '".$code2."'";
				$query	="INSERT into confirmation (code,query) VALUES (\"".$code1."\",\"".mydb::cxn()->real_escape_string($stored_query)."\")";
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->affected_rows != 1) throw new Exception('A database error occurred while storing the Crew Affiliation query.');
				
				// Create a SECOND query which will transfer affiliation to the new crew and ALSO change the user account type to 'crewmember'
				// This option is presented with a link in the body of the email.  One or both of these queries will simply expire after 7 days
				// if it is not executed.
				$stored_query = "UPDATE authentication SET crew_affiliation_id = ".$user->get('crew_affiliation_id').", account_type = 'crewmember' WHERE id = ".$user->get('id')
								."; DELETE FROM confirmation WHERE code = '".$code1."'";
				$query	="INSERT into confirmation (code,query) VALUES (\"".$code2."\",\"".mydb::cxn()->real_escape_string($stored_query)."\")";
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->affected_rows != 1) throw new Exception('A database error occurred while storing the Crew Affiliation query.');
				
				
				//Find the name of the Requestor's crew (for the body of the approval email)
				$crewname_query = 'SELECT name FROM crews WHERE id = '.$current_crew;
				
				$result_crewname = mydb::cxn()->query($crewname_query);
				$row_crewname = $result_crewname->fetch_assoc();
				$requestor_crew = $row_crewname['name'];
				
				//Find the email addresses of the crew admins who need to approve the change (the receiving crew).
				//Send email with approval link to each crew admin.
				$query = "SELECT email FROM authentication WHERE account_type = 'crew_admin' AND crew_affiliation_id = ".$requested_crew;
				$result_crew_admins = mydb::cxn()->query($query);
				while($row_crew_admins = $result_crew_admins->fetch_assoc()) {
					try {
						$msg = new email('change_crew_admin_affiliation',$row_crew_admins['email'],array($code1,$code2,$user,$requestor_crew));
						$msg->send(); // Send an email to each crew_admin for approval
						$email_sent = true;
					} catch(Exception $e) {
						
					}
				}
				$user->set('crew_affiliation_id',$current_crew); //Set the current user's crew_affiliation_id back to its original value for now (not yet approved)
				
				// If there was a problem sending verification emails, remove the verification codes from the database
				if($email_sent != $email_needed) {
					$query = "DELETE FROM confirmation WHERE (code = '".$code1."' OR code = '".$code2."')";
					$result = mydb::cxn()->query($query);
					
					throw new Exception('There was a problem sending your<br />Crew Affiliation request<br />(that crew may not have any Crew Admins)<br /><br />No changes have been made.');
				}
			} // End: if ($_SESSION['current_user']->get('account_type') != 'admin')
		} // End: if($current_crew != $requested_crew)
	} // End: if($this->account_type == 'crew_admin')
	
	
	
} // End: process_crew_affiliation_change_if_necessary()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: update_form_memory_from_user_id() ***********************************************/
/*******************************************************************************************************************************/
function update_form_memory_from_user_id($form_name,$user_id) {
	
	$query = "SELECT id, firstname, lastname, email, account_type, crew_affiliation_id FROM authentication WHERE id = ".$user_id;
	$result = mydb::cxn()->query($query);
	
	$row = $result->fetch_assoc();
	
	$_SESSION['form_memory'][$form_name] = array(	'user_id'	=> $row['id'],
													'firstname'	=> $row['firstname'],
													'lastname'	=> $row['lastname'],
													'email'		=> $row['email'],
													'account_type'			=> $row['account_type'],
													'crew_affiliation_id'	=> $row['crew_affiliation_id']);
	
} // End: update_form_memory_from_user_id()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: update_form_memory_from_post() **************************************************/
/*******************************************************************************************************************************/
function update_form_memory_from_post($form_name) {
	
	$_SESSION['form_memory'][$form_name] = array(	'user_id'	=> (isset($_POST['user_id']) ? $_POST['user_id'] : ''),
													'firstname'	=> $_POST['firstname'],
													'lastname'	=> $_POST['lastname'],
													'email'		=> $_POST['email'],
													'account_type'			=> $_POST['account_type'],
													'crew_affiliation_id'	=> (isset($_POST['crew_affiliation_id']) ? $_POST['crew_affiliation_id'] : ""));
} // End: update_form_memory_from_post()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: clear_form_memory() *************************************************************/
/*******************************************************************************************************************************/
function clear_form_memory($form_name) {
	
	$_SESSION['form_memory'][$form_name] = array(	'user_id'	=> '',
													'firstname'	=> '',
													'lastname'	=> '',
													'email'		=> '',
													'account_type'			=> '',
													'crew_affiliation_id'	=> '');
} // End: clear_form_memory()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: crew_has_max_admins() *************************************************************/
/*******************************************************************************************************************************/
function crew_has_max_admins($crew_id) {
	
	$query = "SELECT count(id) as admins FROM authentication WHERE crew_affiliation_id = ".mydb::cxn()->real_escape_string($crew_id)." AND account_type = 'crew_admin'";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	if($row['admins'] >= $_SESSION['max_crew_admins_per_crew']) return true;
	else return false;
}
?>









