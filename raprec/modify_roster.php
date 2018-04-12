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
	include_once('includes/php_doc_root.php');
	
	require_once("classes/mydb_class.php");
	require_once("classes/user_class.php");
	require_once("classes/email_class.php");
	require_once("classes/hrap_class.php");
	require_once("classes/crew_class.php");
	
	session_name('raprec');
	session_start();
	
	require_once("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	

	// Make sure this user is allowed to access this page
	
	if(($_SESSION['logged_in'] == 1) && check_access("crew_admin",$_GET['crew'])) {
		// ACCESS GRANTED!
		// Attempt to load the specified crew
		try {
			global $crew;
			$crew = new crew;
			$crew->load($_GET['crew']);
		} catch (Exception $e) {
			if($_SESSION['current_user']->get('account_type') == 'admin') {
				//echo "admin";
				if($_GET['function'] != 'no_crew_specified') header('location: '.$_SERVER['PHP_SELF'].'?function=no_crew_specified');
			}
			else header('location: index.php');
		}
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
<title>RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, history" />
<meta name="Description" content="The National Rappel Record Website - This page is used to modify crew rosters." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if($_SESSION['mobile'] == 1) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script language="javascript" src="scripts/popup_calendar/cal2.js">
/*
Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/
</script>
<script language="javascript" src="scripts/popup_calendar/cal_conf2.js"></script>

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
	isset($_GET['function']) ? $function = $_GET['function'] : $function = false;
	switch($function) {
/*---------------------------------------------------------------*/
	case "add_hrap_menu":
		show_add_hrap_menu();
		break;
/*---------------------------------------------------------------*/
	case "add_new_hrap":
		if(isset($_POST['MAX_FILE_SIZE'])) {
			// The 'add hrap' form has been submitted - check data and enter into database
			$_SESSION['form_memory']['field1'] = $_POST['firstname'];
			$_SESSION['form_memory']['field2'] = $_POST['lastname'];
			$_SESSION['form_memory']['field3'] = $_POST['birthdate'];
			$_SESSION['form_memory']['field12'] = $_POST['iqcs_num'];
			$_SESSION['form_memory']['field4'] = $_POST['year_of_1st_rappel'];
			$_SESSION['form_memory']['field5'] = $_POST['rap_count_offset_proficiency'];
			$_SESSION['form_memory']['field6'] = $_POST['rap_count_offset_operational'];
			$_SESSION['form_memory']['field7'] = $_POST['gender'];
			$_SESSION['form_memory']['field8'] = $_POST['spotter'];
			$_SESSION['form_memory']['field9'] = $_POST['uploadedfile'];
			$_SESSION['form_memory']['field10'] = $_POST['spot_count_offset_proficiency'];
			$_SESSION['form_memory']['field11'] = $_POST['spot_count_offset_operational'];
			
			try {
				$hrap = new hrap;
				$hrap->create($_POST['firstname'], $_POST['lastname'], $_POST['gender'], $_POST['birthdate'], $_POST['iqcs_num'], $_POST['year_of_1st_rappel'], $_POST['rap_count_offset_proficiency'], $_POST['rap_count_offset_operational'], $_POST['spotter'], $_POST['spot_count_offset_proficiency'], $_POST['spot_count_offset_operational']);
				$hrap->add_to_roster($_GET['crew'],$_SESSION['current_view']['year']);
				$_SESSION['form_memory'] = ""; //Clear the form memory if all operations were successful
				show_roster_modification_menu();
				
			} catch (Exception $e) {
				show_add_new_hrap_form();
				echo "<div class=\"error_msg\">".$e->getMessage()."</div>\n";
			}
			
		}
		else {
			// Display the 'add hrap' form
			show_add_new_hrap_form();
		}
		break;
/*---------------------------------------------------------------*/
	case "add_existing_hrap":
		if(isset($_POST['hrap_id'])) {
			//An existing hrap has been selected, enter into database (table: 'rosters')
			$query = "INSERT INTO rosters (hrap_id, crew_id, year) values (".$_POST['hrap_id'].",".$_GET['crew'].",".$_SESSION['current_view']['year'].")";
			$result = mydb::cxn()->query($query);
			
			if(mydb::cxn()->error == '') {
				//show_header();
				$msg = "Your crew just gained a crewmember!<br>\n\n";
				show_add_existing_hrap_form($msg);
			}
		}
		elseif(isset($_GET['hrap_id'])) {
			//An existing hrap has been REQUESTED - request confirmation
			show_confirm_existing_hrap_menu();
		}
		else show_add_existing_hrap_form();
		break;
/*---------------------------------------------------------------*/
	case "edit_hrap":
		if(isset($_POST['hrap_id'])) {
			//Commit the requested changes to the database
			try {
				if(!isset($_POST['remove_headshot']) || $_POST['remove_headshot'] != 1) $remove_headshot = 0;
				else $remove_headshot = 1;
				
				$hrap = new hrap;
				$hrap->load($_POST['hrap_id']);
				$hrap->update($_POST['firstname'], $_POST['lastname'], $_POST['gender'], $_POST['birthdate'], $_POST['iqcs_num'], $_POST['year_of_1st_rappel'],$_POST['count_offset_proficiency'], $_POST['count_offset_operational'], $_POST['spotter'], $_POST['spot_count_offset_proficiency'], $_POST['spot_count_offset_operational'], $remove_headshot);
				show_roster_modification_menu();
			} catch(Exception $e) {
				show_edit_hrap_menu($e->getMessage());
			}
		}
		else {
			try {
				show_edit_hrap_menu();
			} catch (Exception $e) {
				echo "<div class=\"error_msg\">".$e->getMessage()."</div><br>\n</div>\n";
			}
		}
		break;
/*---------------------------------------------------------------*/
	case "remove_hrap":
		if(isset($_POST['hrap_id'])) {
			// An HRAP has been selected for removal, DO IT.
			$query = "DELETE FROM rosters WHERE hrap_id = ".$_POST['hrap_id']." AND crew_id = ".$crew->id." AND year = ".$_SESSION['current_view']['year'];
			$result = mydb::cxn()->query($query);
			
			if(mydb::cxn()->error == "") {
				show_header();
				echo "The requested rappeller has been successfully removed from your ".$_SESSION['current_view']['year']." roster!<br><br>\n\n";
				echo "</div>\n\n";
			}
		}
		else {
			// Display the 'remove hrap' form
			show_remove_hrap_form();
		}
		break;
/*---------------------------------------------------------------*/
	case 'no_crew_specified':
		echo "<br /><div class=\"error_msg\">You must select a crew before modifying rosters</div><br>\n"
			."<a href=\"index.php\">Return Home to select a Region</a>\n";
		break;
/*---------------------------------------------------------------*/
	default:
		// No function was specified, or invalid function was specified
		// But if an HRAP was specified, assume that the user wants to edit that HRAP - show_edit_hrap_menu()
		if($_SESSION['current_view']['hrap'] != NULL) {
			try {
				show_edit_hrap_menu();
			} catch (Exception $e) {
				echo "<div class=\"error_msg\">".$e->getMessage()."</div><br>\n</div>\n";
			}
		}
		else {
		// Display the ROSTER MODIFICATION MENU
			show_roster_modification_menu();
		}
		break;
	} // End: switch($_GET['function'])
?>

    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>

<?php

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_header() *******************************************************************/
/*******************************************************************************************************************************/
	function show_header() {
		global $crew;

		$year_str = "";
		$hrap = "";
		$function = "";
		if(isset($_GET['hrap'])) $hrap = "&hrap=".$_GET['hrap'];
		if(isset($_GET['function'])) $function = "&function=".$_GET['function'];
		
		if($year_array = $crew->get_roster_years()) {
			foreach($year_array as $year) {
				$year_str .= "<a href=\"".$_SERVER['PHP_SELF']."?crew=".$_GET['crew'].$hrap."&year=".$year.$function."\">".$year."</a> | ";
			}
			$year_str = substr($year_str,0,strlen($year_str)-3); // Strip the last pipe divider off the string
		}
		else $year_str = "Your crew currently has no Roster History";
		
		echo "<div style=\"width:200px;float:left;\"><img src=\"".$crew->logo_filename."\"></div>\n";
		
		echo "<div style=\"width:550px;float:left;text-align:left;\"><h1>".$crew->name."</h1><br>\n"
			."<h2>Modify the ".$_SESSION['current_view']['year']." Roster</h2><br><br>\n\n"
			."<hr style=\"margin:0 auto 0 0; height:2px; width:100%;\"><br>\n\n"
			."<ul style=\"font-weight:bold\"><li><a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."&function=add_hrap_menu\">Add a crewmember</a></li>\n"
			."	<li><a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."\">Edit / Remove a current crewmember</a></li></ul>\n";
			
		echo "<div style=\"width:100%; padding:2px; text-align:left; background-color:#eeeeee; border:2px solid #cccccc;\">Your Crew has rosters for the following years:<br>\n".$year_str."</div>\n\n";
		
		echo "</div>\n\n"
			."<br style=\"clear:both\">\n\n"
			."<div style=\"text-align:center; width:100%;\">\n";
	} // End: function show_header()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_add_hrap_menu() ************************************************************/
/*******************************************************************************************************************************/

	function show_add_hrap_menu() {
		global $crew;
		show_header();
		
		echo "<table style=\"width:90%; margin:0 auto 0 auto;\"><tr>\n"
			."<td style=\"padding:0 10px 0 0;\"><a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."&function=add_new_hrap\"><h2>Create a New HRAP</h2></a></td>\n"
			."<td style=\"padding:0 10px 0 10px;\">&nbsp;</td>\n"
			."<td style=\"padding:0 0 0 10px;\"><a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."&function=add_existing_hrap\"><h2>Add an Existing HRAP</h2></a></td></tr>\n";
		
		echo "<tr>\n"
			."<td style=\"text-align:justify; padding:0 10px 0 0;\">\n"
			."Choose this option if the rappeller you wish to add has never been entered into the RapRec system. This applies to first-year rappellers, "
			."or rappellers who are transitioning from a rappel crew that doesn't use RapRec Central.</td>\n"
			."<td style=\"font-size:18px; font-weight:bold; vertical-align:center; text-align:center; padding:0 10px 0 10px; margin:0;\">OR</td>\n"
			."<td style=\"text-align:justify; padding:0 0 0 10px;\">\n"
			."Choose this option if the rappeller you wish to add is already entered into the RapRec system.  This applies to veteran rappellers, "
			."even if this is their first year on your crew (as long as they have a RapRec entry from their old crew).</td></tr>\n";
		
		echo "</table><br>\n\n";
		echo "</div><br>\n";
	
	}
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_add_new_hrap_form() ********************************************************/
/*******************************************************************************************************************************/

	function show_add_new_hrap_form() {
		global $crew;
		
		initialize_form_menu();
		
		show_header();
			
		echo "<div style=\"margin:0 auto 0 auto;width:585px;\"><div style=\"float:left;width:271px; text-align:justify;padding-right:10px;\">\n"
			."You are adding a new rappeller to the <span style=\"font-weight:bold\">".$_SESSION['current_view']['year']."</span> roster for <span style=\"font-weight:bold\">".$crew->name."</span><br><hr style=\"width:100%\"><br>\n"
			."You should use this form to create a record for a new rappeller.<br>\n"
			."<br>If you want to add an existing rappeller to your ".$_SESSION['current_view']['year']." roster, you should use the "
			."<a href=\"modify_roster.php?function=add_existing_hrap&crew=".$_GET['crew']."\">'add existing HRAP'</a> form.</div>";
		
		echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'] . "\" method=\"post\" name=\"add_hrap_form\">\n"
			."<table style=\"margin:0 auto 0 auto; width:50%;\" border=0>\n"
			."	<tr><td>First Name</td><td>:</td><td style=\"width:150px;\"><input type=\"text\" name=\"firstname\" value=\"".$_SESSION['form_memory']['field1']."\" class=\"input_field\"></td><td style=\"width:auto;\"></td></tr>\n"
			."	<tr><td>Last Name</td><td>:</td><td><input type=\"text\" name=\"lastname\" value=\"".$_SESSION['form_memory']['field2']."\" class=\"input_field\"></td><td></td></tr>\n";
		
		echo "	<tr><td style=\"vertical-align:top;\">Birthdate</td><td style=\"vertical-align:top;\">:</td>"
			."		<td style=\"vertical-align:top;\"><input type=\"text\" name=\"birthdate\" value=\"".$_SESSION['form_memory']['field3']."\" class=\"input_field\" readonly=\"readonly\"><br>"
			."			<small><a href=\"javascript:showCal('Calendar1')\">Select Date</a></small></td><td></td></tr>\n";
		echo "	<tr><td>IQCS Number</td><td>:</td><td><input type=\"text\" name=\"iqcs_num\" value=\"".$_SESSION['form_memory']['field12']."\" class=\"input_field\"></td><td></td></tr>\n";
		echo "	<tr><td>Year of<br>1st Rappel<br>(yyyy)</td><td>:</td><td><input type=\"text\" name=\"year_of_1st_rappel\" value=\"".$_SESSION['form_memory']['field4']."\" class=\"input_field\"></td><td></td></tr>\n";
		
		echo "	<tr><td>Number of proficiency rappels<br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"rap_count_offset_proficiency\" value=\"".$_SESSION['form_memory']['field5']."\" class=\"input_field\"></td><td></td></tr>\n";
		
		echo "	<tr><td>Number of operational rappels<br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"rap_count_offset_operational\" value=\"".$_SESSION['form_memory']['field6']."\" class=\"input_field\"></td><td></td></tr>\n";
		
		echo "	<tr><td>Gender</td><td>:</td><td>Male<input type=\"radio\" name=\"gender\" value=\"male\" ";
		if($_SESSION['form_memory']['field7'] == "male") echo "checked";
		echo ">&nbsp;&nbsp; Female<input type=\"radio\" name=\"gender\" value=\"female\" ";
		if($_SESSION['form_memory']['field7'] == "female") echo "checked";
		echo "></td><td></td></tr>\n";
		
		echo "	<tr><td>Spotter<br>Qualified?</td><td>:</td><td>No<input type=\"radio\" name=\"spotter\" value=\"0\" ";
		
		if(($_SESSION['form_memory']['field8'] == "0") || ($_SESSION['form_memory']['field8'] == "")) echo "checked";
		echo "><br> "
			."			Yes<input type=\"radio\" name=\"spotter\" value=\"1\" ";
		if($_SESSION['form_memory']['field8'] == "1") echo "checked";
		echo "><br> "
			."			Trainee<input type=\"radio\" name=\"spotter\" value=\"2\" ";
		if($_SESSION['form_memory']['field8'] == "2") echo "checked";
		echo "></td><td></td></tr>\n";
		
		echo "	<tr><td>Number of proficiency spots<br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"spot_count_offset_proficiency\" value=\"".$_SESSION['form_memory']['field10']."\" class=\"input_field\"></td><td></td></tr>\n";
		
		echo "	<tr><td>Number of operational spots<br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"spot_count_offset_operational\" value=\"".$_SESSION['form_memory']['field11']."\" class=\"input_field\"></td><td></td></tr>\n";
		
		echo "	<tr><td>Photo</td><td>:</td><td colspan=\"2\"><input name=\"uploadedfile\" type=\"file\" value=\"".$_SESSION['form_memory']['field9']."\" class=\"input_field\" style=\"width:95%;\" />\n"
			."			<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"3500000\" /></td></tr>\n"
			."	<tr><td></td><td></td><td><input type=\"submit\" value=\"Add New HRAP\"></td><td></td></tr>"
			."</table>\n"
			."</form><br>\n\n";
		
		echo "</div></div>\n\n";
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_add_existing_hrap_form() ***************************************************/
/*******************************************************************************************************************************/

	function show_add_existing_hrap_form($msg="") {
		global $crew;
		
		show_header();
		
		echo "<div style=\"width:500px; margin:0 auto 0 auto; text-align:justify;\">";
		echo "You are adding a rappeller to your ".$_SESSION['current_view']['year']." roster.<br><br>"
			."<span style=\"font-weight:bold;\">Start typing the name of the rappeller you want to add</span>.<br>"
			." A menu will appear with relevant names once you've entered several characters.  You can continue typing to narrow the "
			."options in the popup menu, and then make your choice by clicking on a name.<br><br>\n"
			."Rappellers who are already assigned to a ".$_SESSION['current_view']['year']." roster will not appear in the list.<br><br><br>\n\n";
		
		$_SESSION['current_view']['crew_id'] = $crew->id;
		
		echo "Name:<br>\n";
		include("raprec/scripts/searchautosuggest/autosuggest.php");
		
		echo "<br /><div style=\"font-weight:bold; font-size:1.2em; color:#995555;\">".$msg."</div>\n";
	
		echo "</div></div>\n";
		
	} // End: function show_add_existing_hrap_form()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_confirm_existing_hrap_menu() ***********************************************/
/*******************************************************************************************************************************/

	function show_confirm_existing_hrap_menu() {
		
		//Check that the requested HRAP is valid
		$name = check_hrap($_GET['hrap_id']);
		$crew = check_crew($_GET['crew']);
		
		//Check that the requested HRAP is not already on a roster for the requested year
		$query = "SELECT crews.name as crew_name FROM rosters INNER JOIN crews ON crews.id = rosters.crew_id WHERE rosters.hrap_id = ".$_GET['hrap_id']." AND rosters.year = '".$_SESSION['current_view']['year']."'";
		$result = mydb::cxn()->query($query);
		
		$msg = "";
		if($name === 0) $msg = "The rappeller you requested does not appear to exist!";
		elseif($crew == false) $msg = "The crew you requested does not appear to exist!";
		elseif(mydb::cxn()->affected_rows > 0) {
			$row = $result->fetch_assoc();
			$msg = $name." is already a member of ".$row['crew_name']." in ".$_SESSION['current_view']['year']."!";
		}
		else $msg = "Are you sure you want to add ".$name." to your ".$_SESSION['current_view']['year']." roster?<br><br>\n\n"
					."<form action=\"\" method=\"post\">\n <input type=\"hidden\" name=\"hrap_id\" value=\"".$_GET['hrap_id']."\">\n "
					."<input type=\"submit\" value=\"Add\"> <input type=\"button\" value=\"Cancel\" onClick=\"window.location.href='".$_SERVER['PHP_SELF']."?crew=".$_GET['crew']."&function=add_existing_hrap'\">\n "
					."</form>";
		
		
		show_header();
		echo "<div style=\"border:2px solid #666666;padding:10px;text-align:center;\">".$msg."</div><br>\n\n";

		echo "</div>\n";
	}
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_edit_hrap_menu() *********************************************************/
/*******************************************************************************************************************************/
	function show_edit_hrap_menu($error_msg = "") {
		global $crew;
		
		$hrap = new hrap;
		
		show_header();
		
		try {
			$hrap->load($_GET['hrap']);
			if($hrap->get_crew_by_year($_SESSION['current_view']['year']) != $crew->get('id')) throw new Exception('The HRAP you selected is not a member of the '.$_SESSION['current_view']['year'].' roster.');
			
			echo "<div style=\"width:700px; text-align:left;\">\n"
				."<ul><li>The information below applies to all roster years and all crews that this rappeller has worked for.</li>\n"
				."		<li>Changes will be applied when you click the \"Update this HRAP\" button (this page <i>is</i> your confirmation).</li></ul>\n"
				."</div><br>\n\n";
			
			echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'] . "\" method=\"post\" name=\"edit_hrap_form\">\n"
				."<table style=\"margin:0 auto 0 auto;\">\n"
				."	<tr><td>First Name</td><td>:</td><td style=\"width:150px;\"><input type=\"text\" name=\"firstname\" value=\"".$hrap->firstname."\" class=\"input_field\"></td><td style=\"width:auto;\"></td></tr>\n"
				."	<tr><td>Last Name</td><td>:</td><td><input type=\"text\" name=\"lastname\" value=\"".$hrap->lastname."\" class=\"input_field\"></td><td></td></tr>\n";
			
			echo "	<tr><td style=\"vertical-align:top;\">Birthdate</td><td style=\"vertical-align:top;\">:</td>"
				."		<td style=\"vertical-align:top;\"><input type=\"text\" name=\"birthdate\" value=\"".$hrap->birthdate."\" class=\"input_field\" readonly=\"readonly\"><br>"
				."			<small><a href=\"javascript:showCal('Calendar2')\">Select Date</a></small></td><td></td></tr>\n";
			echo "	<tr><td>Year of<br>1st Rappel<br>(yyyy)</td><td>:</td><td><input type=\"text\" name=\"year_of_1st_rappel\" value=\"".$hrap->year_of_1st_rappel."\" class=\"input_field\"></td><td></td></tr>\n";
			
			echo "	<tr><td>IQCS Number</td><td>:</td><td><input type=\"text\" name=\"iqcs_num\" value=\"".$_SESSION['form_memory']['field12']."\" class=\"input_field\"></td><td></td></tr>\n";
			echo "	<tr><td>Number of proficiency rappels <br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"count_offset_proficiency\" value=\"".$hrap->count_offset_proficiency."\" class=\"input_field\"></td><td></td></tr>\n";
			
			echo "	<tr><td>Number of operational rappels <br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"count_offset_operational\" value=\"".$hrap->count_offset_operational."\" class=\"input_field\"></td><td></td></tr>\n";
			
			echo "	<tr><td>Gender</td><td>:</td><td>Male<input type=\"radio\" name=\"gender\" value=\"male\" ";
			
			if(strtolower($hrap->gender) == "male") echo "checked";
			echo ">&nbsp;&nbsp; Female<input type=\"radio\" name=\"gender\" value=\"female\" ";
			if(strtolower($hrap->gender) == "female") echo "checked";
			echo "></td><td></td></tr>\n";
			
			echo "	<tr><td>Spotter<br>Qualified?</td><td>:</td><td>No<input type=\"radio\" name=\"spotter\" value=\"0\" ";
			
			if($hrap->spotter == "0") echo "checked";
			echo "><br> "
				."			Yes<input type=\"radio\" name=\"spotter\" value=\"1\" ";
			if($hrap->spotter == "1") echo "checked";
			echo "><br> "
				."			Trainee<input type=\"radio\" name=\"spotter\" value=\"2\" ";
			if($hrap->spotter == "2") echo "checked";
			echo "></td><td></td></tr>\n";
			
			echo "	<tr><td>Number of proficiency spots<br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"spot_count_offset_proficiency\" value=\"".$hrap->spot_count_offset_proficiency."\" class=\"input_field\"></td><td></td></tr>\n";
		
			echo "	<tr><td>Number of operational spots<br>NOT in the RapRec system</td><td>:</td><td><input type=\"text\" name=\"spot_count_offset_operational\" value=\"".$hrap->spot_count_offset_operational."\" class=\"input_field\"></td><td></td></tr>\n";
		
			echo "	<tr><td>Photo</td><td>:</td><td><img src=\"".$hrap->headshot_filename."\"></td><td></td></tr>\n"
				."	<tr><td>Remove Photo</td><td>:</td><td style=\"text-align:left;\"><input type=\"checkbox\" name=\"remove_headshot\" value=\"1\" /></td><td></td></tr>\n"
				."	<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=\"2\"><input name=\"uploadedfile\" type=\"file\" value=\"\" class=\"input_field\" />"
				."			<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"3500000\" /></td></tr>\n"
				."	<tr><td></td><td></td><td><input type=\"hidden\" name=\"hrap_id\" value=\"".$hrap->id."\"><input type=\"submit\" value=\"Update this HRAP\"></td><td></td></tr>\n"
				."</table>\n"
				."</form><br>\n\n"
				."</div>\n\n";
			
			if($error_msg != "") echo "<div class=\"error_msg\"><br><br><br>".$error_msg."</div>\n\n";
			
		} catch (Exception $e) {
/*			echo "<div class=\"error_msg\">".$e->getMessage()."</div><br>\n"
				."<a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."\">Return to Crew Roster</a>\n</div>\n\n";
*/
			throw new Exception($e->getMessage());
		}
		
	
	} // End: function show_edit_hrap_menu()
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_remove_hrap_form() *********************************************************/
/*******************************************************************************************************************************/

	function show_remove_hrap_form() {
		global $crew;
		
		show_header();
		
		$name = check_hrap($_GET['hrap']);
		if($name !== 0) {
		echo "You have chosen to remove ".$name." from the ".$_SESSION['current_view']['year']." roster for ".$crew->name.".<br><br>\n"
			."This action will not affect any other roster years for this crew, nor will it affect rosters from other crews.<br>\n"
			."Are you sure you want to continue?<br><br>\n";
		
		echo "<form action=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."&function=remove_hrap\" method=\"post\">"
			."<input type=\"hidden\" name=\"hrap_id\" value=\"".$_GET['hrap']."\">\n"
			."<input type=\"submit\" value=\"Remove\"> <input type=\"button\" onClick=\"window.location.href='".$_SERVER['PHP_SELF']."?crew=".$crew->id."'\" value=\"Cancel\">\n"
			."</form><br>\n\n";
		
		}
		else echo "You must specify an existing rappeller to remove!<br><br><a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."\" style=\"font-weight:bold;\">Return to Roster Management Menu</a><br>\n";
		
		echo "</div>\n";
	} // End: function show_remove_hrap_form()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_roster_modification_menu() *************************************************/
/*******************************************************************************************************************************/
	
	function show_roster_modification_menu() {
		global $crew;
		
		show_header();

		try {
			$crew->get_crewmembers($_SESSION['current_view']['year']);
		
			echo "<table class=\"alternating_rows\" style=\"margin:0 auto 0 auto;\"><tr><th>Photo</th><th>Name</th><th>IQCS #</th><th>Gender</th><th>1st Rappel</th><th>Spotter?</th><th>Tools</th></tr>\n";
			
			$i = 0;
			foreach($crew->crewmembers as $hrap) {
				$i++;
				if($i % 2 == 0) $tr = "<tr class=\"evn\">";
				else			$tr = "<tr class=\"odd\">";
				
				switch($hrap->spotter) {
				case 0:
					$spotter = "No";
					break;
				case 1:
					$spotter = "Yes";
					break;
				case 2:
					$spotter = "Trainee";
					break;
				}
				
				echo $tr."<td><img src=\"".$hrap->headshot_filename."\"></td><td>".$hrap->name."</td><td>".$hrap->iqcs_num."</td>"
					."<td>".$hrap->gender."</td><td>".$hrap->year_of_1st_rappel."</td><td>".$spotter."</td>"
					."<td><a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."&hrap=".$hrap->id."&function=edit_hrap\" title=\"Edit\"><img src=\"images/edit.jpg\"></a> "
					."<a href=\"".$_SERVER['PHP_SELF']."?crew=".$crew->id."&hrap=".$hrap->id."&function=remove_hrap\" title=\"Remove\"><img src=\"images/remove.png\"></a></td></tr>\n";
			}
			echo "</table><br>\n\n";
			echo "</div>\n";
		
		} catch (Exception $e) {
			echo $e->getMessage()."<br>\n</div>";
		}
	} // End: function show_roster_modification_menu()
	
	
	function initialize_form_menu() {
		for($i=1; $i<=11; $i++) {
			if(!isset($_SESSION['form_memory']['field'.$i])) $_SESSION['form_memory']['field'.$i] = '';
		}
		
	}
?>
