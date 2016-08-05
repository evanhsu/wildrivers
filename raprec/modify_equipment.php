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
/*
	rope status			:	"in-service", "suspended", "missing", "retired"
	retired category	:	"age", "use", "field_damage", "other_damage"
*/
	include('includes/php_doc_root.php');
	
	require_once("classes/mydb_class.php");
	require_once("classes/user_class.php");
	require_once("classes/email_class.php");
	require_once("classes/hrap_class.php");
	require_once("classes/crew_class.php");
	require_once("classes/operation_class.php");
	require_once("classes/rappel_class.php");
	require_once("classes/rope_class.php");
	require_once("classes/genie_class.php");
	
	session_name('raprec');
	session_start();
	
	require_once("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	require_once("includes/aircraft_layouts.php");

	//$_GET = array('crew'=>1,'eq_type'=>'rope','eq_id'=>'1');
	
	// Determine whether the current user has permission to access this page
	// We must first know which crew owns the piece of equipment being modified
	try {
		if(in_array($_GET['eq_type'],array('rope','genie','letdown_line'))) {
			$eq = new $_GET['eq_type'];
			$eq->load($_GET['eq_id']); // The load method performs its own error checking, no need to do it here
		}
		else throw new Exception('An invalid equipment type was specified');
	} catch (Exception $e) {
		// A piece of equipment has not been specified - redirect to the 'view_equipment.php' page
		header('location: view_equipment.php?'.$_SERVER['QUERY_STRING']);
	}

	if(($_SESSION['logged_in'] == 1) && check_access("crew_admin",$eq->get('crew_affiliation_id'))) {
		// ACCESS GRANTED!
		// The piece of equipment has already been loaded
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
<title>Equipment | RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, record, history" />
<meta name="Description" content="The National Rappel Record Website - This page is for modifying equipment information." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if(isset($_SESSION['mobile']) && ($_SESSION['mobile'] == 1)) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script type="text/javascript" src="scripts/searchautosuggest/lib/ajax_framework.js"></script>
<script language="javascript" src="scripts/popup_calendar/cal2.js">
/*
Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/
</script>
<script language="javascript" src="scripts/popup_calendar/cal_conf2.js"></script>
<script type="text/javascript">
function updateAbbrev() {
	var crew_id = document.getElementById('crew_affiliation_id').value;
	document.getElementById('eq_abbrev').value = document.getElementById('crew_'+crew_id+'_abbrev').value;
}
</script>

<style>
a:hover {text-decoration:none;}
</style>
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
/* ------------------------------------------------<< BEGIN CONTENT >>-------------------------------------------------------------*/

	if(isset($_POST['id'])) {
		try {
			commit_equipment(get_class($eq));
			show_edit_rappel_equipment_form($eq,"Your changes have been saved");
		} catch (Exception $e) {
			show_edit_rappel_equipment_form($eq,$e->getMessage());
		}
	}
	else show_edit_rappel_equipment_form($eq);
?>
	<?php echo "<a href=\"view_equipment.php?eq_type=" . get_class($eq) . "\">View all ".get_class($eq)."s</a>";?>

    </div> <!-- End 'content' -->
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>




<?php

/************************************************************************************************************************************/
/***********************************************<< show_edit_rappel_equipment_form >>************************************************/
/************************************************************************************************************************************/
function show_edit_rappel_equipment_form($eq, $msg = NULL) {
	// $eq is a RAPPEL EQUIPMENT OBJECT
	if($msg != NULL) echo "<br><div class=\"error_msg\">".$msg."</div>";
	
	if(!isset($_SESSION['form_memory']['edit_equipment'])) {
		// Load equipment details into the form memory, if the memory isn't already populated
		$_SESSION['form_memory']['edit_equipment'][0] = $eq->get('id');
		$_SESSION['form_memory']['edit_equipment'][1] = $eq->get('serial_num');
		$_SESSION['form_memory']['edit_equipment'][2] = $eq->get('crew_affiliation_id');
		$_SESSION['form_memory']['edit_equipment'][3] = $eq->get('in_service_date');
		$_SESSION['form_memory']['edit_equipment'][4] = $eq->get('retired_date');
		$_SESSION['form_memory']['edit_equipment'][5] = $eq->get('retired_reason');
		$_SESSION['form_memory']['edit_equipment'][6] = $eq->get('retired_category');
		$_SESSION['form_memory']['edit_equipment'][7] = $eq->get('status');
		$_SESSION['form_memory']['edit_equipment'][10] = $eq->get('mfr_serial_num');
		if($eq->get('item_type') == 'rope') {
			$_SESSION['form_memory']['edit_equipment'][8] = $eq->get('use_offset_a');
			$_SESSION['form_memory']['edit_equipment'][9] = $eq->get('use_offset_b');
		}
		else $_SESSION['form_memory']['edit_equipment'][8] = $eq->get('use_offset');
	}

	// Separate the Crew Abbreviation from the ID Number in the letdown_line_num
	$serial_num = explode("-",$_SESSION['form_memory']['edit_equipment'][1]);
	
	// Build Crew selection menu AND
	// Build a hidden list of Crew Abbreviations.
	// This list is used to update the letdown_line # field in the edit_letdown_line_form when the letdown_line Ownership is changed
	echo "<form action=\"\" method=\"GET\" id=\"abbrev_list\">\n";
	
	$query = "SELECT DISTINCT id, name, abbrev FROM crews ORDER BY name";
	$result = mydb::cxn()->query($query);
	$crew_menu = "";
	$abbrev = "";
	while($row = $result->fetch_assoc()) {
		if($_SESSION['form_memory']['edit_equipment'][2] == $row['id']) {
			$crew_menu .= "<option value=\"".$row['id']."\" selected=\"selected\">".$row['name']."</option>\n";
			$abbrev = $row['abbrev'];
		}
		else $crew_menu .= "<option value=\"".$row['id']."\">".$row['name']."</option>\n";
		echo "<input type=\"hidden\" name=\"crew_".$row['id']."_abbrev\" id=\"crew_".$row['id']."_abbrev\" value=\"".$row['abbrev']."\">\n";
	}
	echo "</form>\n\n";
	
	// Build Status Menu
	$statuses = array('in_service','suspended','missing','retired');
	$status_menu = "";
	foreach($statuses as $status) {
		if($_SESSION['form_memory']['edit_equipment'][7] == $status) $status_menu .= "<option value=\"".$status."\" selected=\"selected\">".ucwords(str_replace("_"," ",$status))."</option>\n";
		else $status_menu .= "<option value=\"".$status."\">".ucwords(str_replace("_"," ",$status))."</option>\n";
	}
	
	// Build Retirement Category Menu
	// $_SESSION['$equipment_retirement_categories'] is defined in 'includes/constants.php'
	$ret_cat_menu = "";
	foreach($_SESSION['$equipment_retirement_categories'] as $cat) {
		if($_SESSION['form_memory']['edit_equipment'][6] == $cat) $ret_cat_menu .= "<option value=\"".$cat."\" selected=\"selected\">".ucwords(str_replace("_"," ",$cat))."</option>\n";
		else $ret_cat_menu .= "<option value=\"".$cat."\">".ucwords(str_replace("_"," ",$cat))."</option>\n";
	}
	
	// Build form
	echo "<form name=\"modify_equipment_form\" action=\"modify_equipment.php?".$_SERVER['QUERY_STRING']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"".$_SESSION['form_memory']['edit_equipment'][0]."\">\n";
	
	echo "<table style=\"width:500px; margin:25px auto 0 auto; background-color:#cccccc; border:2px solid #555555;\">\n"
		."<tr><td colspan=\"2\" style=\"text-align:left; font-size:18px;font-weight:bold;\">Edit ".ucwords(str_replace("_"," ",get_class($eq)))."</td></tr>\n"
		."<tr><td style=\"text-align:right;\">Serial #:</td><td style=\"text-align:left;\">"
				."<input type=\"text\" name=\"serial_num1\" id=\"eq_abbrev\" value=\"".$serial_num[0]."\" style=\"text-transform:uppercase; width:2.5em;  border:none; background-color:#cccccc; text-align:right;\" readonly=\"readonly\">-"
				."<input type=\"text\" name=\"serial_num2\" value=\"".$serial_num[1]."\" style=\"width:5em;\"></td></tr>\n"
		."<tr><td style=\"text-align:right;\">Alternate Serial #:<br />(i.e. SRC-123)</td><td style=\"text-align:left;\">"
				."<input type=\"text\" name=\"mfr_serial_num\" id=\"mfr_serial_num\" value=\"".$_SESSION['form_memory']['edit_equipment'][10]."\" style=\"width:5em;\">"
		."<tr><td style=\"text-align:right;\">Manufacture Date:</td>"
			."<td style=\"text-align:left;\">"
				."<input type=\"text\" name=\"in_service_date\" id=\"in_service_date\" value=\"".$_SESSION['form_memory']['edit_equipment'][3]."\" style=\"width:5em;\" onClick=\"showCal('equipment_in_service_date')\" readonly=\"readonly\">"
			."</td></tr>\n";
			
		if($eq->get('item_type') == 'rope') {
			echo "<tr><td style=\"text-align:right;\">Unrecorded uses<br />on End A:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"use_offset_a\" value=\"".$_SESSION['form_memory']['edit_equipment'][8]."\" style=\"width:2.5em\"></td></tr>\n";
			echo "<tr><td style=\"text-align:right;\">Unrecorded uses<br />on End B:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"use_offset_b\" value=\"".$_SESSION['form_memory']['edit_equipment'][9]."\" style=\"width:2.5em\"></td></tr>\n";
		}
		else echo "<tr><td>Unrecorded uses:</td><td style=\"text-align:left\"><input type=\"text\" name=\"use_offset\" value=\"".$_SESSION['form_memory']['edit_equipment'][8]."\" style=\"width:2.5em\"></td></tr>\n";
		
		echo "<tr><td style=\"text-align:right;\">Status:</td><td style=\"text-align:left;\"><select name=\"status\">".$status_menu."</select></td></tr>\n"
		."<tr><td style=\"text-align:right;\">Retired Date:</td>"
			."<td style=\"text-align:left;\">"
				."<input type=\"text\" name=\"retired_date\" id=\"retired_date\" value=\"".$_SESSION['form_memory']['edit_equipment'][4]."\" style=\"width:5em;\" onClick=\"showCal('equipment_retired_date')\" style=\"\" readonly=\"readonly\">"
			."</td></tr>\n"
		."<tr><td style=\"text-align:right;\">Retirement Category:</td><td style=\"text-align:left;\"><select name=\"retired_category\">".$ret_cat_menu."</select></td></tr>\n"
		."<tr><td style=\"text-align:right;\">Retirement Explanation:</td><td style=\"text-align:left;\"><input type=\"text\" style=\"width:90%\" name=\"retired_reason\" value=\"".$_SESSION['form_memory']['edit_equipment'][5]."\"></td></tr>\n"
		."<tr><td>Ownership:</td><td style=\"text-align:left;\"><select name=\"crew_affiliation_id\" id=\"crew_affiliation_id\" onChange=\"updateAbbrev();\">".$crew_menu."</select></td><tr>\n"
		."<tr><td colspan=\"2\" style=\"text-align:center;\"><input type=\"submit\" value=\"Save\" class=\"form_button\" style=\"width:150px;height:30px;font-size:15px;\"></td></tr>\n"
		."</table>\n";
		
	echo "</form>\n";
	
	$_SESSION['form_memory'] = array(); // Clear the memory - values will be stored again when the form is POST'ed
	
	
} // End: function show_edit_rappel_equipment_form()

/************************************************************************************************************************************/
/***********************************************<< commit_equipment >>***************************************************************/
/************************************************************************************************************************************/
function commit_equipment($item_type) {
	// $item_type must be "rope", "genie" or "letdown_line", corresponding to the CLASS definition of the item to be edited
	if(!in_array($item_type,array('rope','genie','letdown_line'))) throw new Exception('Error in `modify_equipment.php`: invalid item_type passed to `commit_equipment()`.');
	
	// Load item details into the form memory
	$_SESSION['form_memory']['edit_equipment'][0] = mydb::cxn()->real_escape_string($_POST['id']);
	$_SESSION['form_memory']['edit_equipment'][1] = mydb::cxn()->real_escape_string($_POST['serial_num1']) . "-" . mydb::cxn()->real_escape_string($_POST['serial_num2']);
	$_SESSION['form_memory']['edit_equipment'][2] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_id']);
	$_SESSION['form_memory']['edit_equipment'][3] = mydb::cxn()->real_escape_string($_POST['in_service_date']);
	$_SESSION['form_memory']['edit_equipment'][4] = mydb::cxn()->real_escape_string($_POST['retired_date']);
	$_SESSION['form_memory']['edit_equipment'][5] = mydb::cxn()->real_escape_string($_POST['retired_reason']);
	$_SESSION['form_memory']['edit_equipment'][6] = mydb::cxn()->real_escape_string($_POST['retired_category']);
	$_SESSION['form_memory']['edit_equipment'][7] = mydb::cxn()->real_escape_string($_POST['status']);
	$_SESSION['form_memory']['edit_equipment'][10]= mydb::cxn()->real_escape_string($_POST['mfr_serial_num']);
	if($item_type == 'rope') {
		$_SESSION['form_memory']['edit_equipment'][8] = mydb::cxn()->real_escape_string($_POST['use_offset_a']);
		$_SESSION['form_memory']['edit_equipment'][9] = mydb::cxn()->real_escape_string($_POST['use_offset_b']);
	}
	else $_SESSION['form_memory']['edit_equipment'][8] = mydb::cxn()->real_escape_string($_POST['use_offset']);
	

	// This function is called within a try/catch block - let any exceptions thrown by the RAPPEL_EQUIPMENT class return to the caller
	$eq = new $item_type;  // rope, genie, or letdown_line
	$eq->load($_SESSION['form_memory']['edit_equipment'][0]);
	$eq->set('serial_num',$_SESSION['form_memory']['edit_equipment'][1]);
	$eq->set('crew_affiliation_id',$_SESSION['form_memory']['edit_equipment'][2]);
	$eq->set('in_service_date',$_SESSION['form_memory']['edit_equipment'][3]);
	$eq->set('retired_date',$_SESSION['form_memory']['edit_equipment'][4]);
	$eq->set('retired_reason',$_SESSION['form_memory']['edit_equipment'][5]);
	$eq->set('retired_category',$_SESSION['form_memory']['edit_equipment'][6]);
	$eq->set('status',$_SESSION['form_memory']['edit_equipment'][7]);
	$eq->set('mfr_serial_num',$_SESSION['form_memory']['edit_equipment'][10]);
	if($item_type == 'rope') {
		$eq->set('use_offset_a',$_SESSION['form_memory']['edit_equipment'][8]);
		$eq->set('use_offset_b',$_SESSION['form_memory']['edit_equipment'][9]);
	}
	else $eq->set('use_offset',$_SESSION['form_memory']['edit_equipment'][8]);
	
	$eq->save();
	
	return true; // Success
	
} // End: function commit_equipment()

/************************************************************************************************************************************/
/***********************************************<< commit_rope >>********************************************************************/
/************************************************************************************************************************************/
function commit_rope() {
	// Load rope details into the form memory
	$_SESSION['form_memory']['rope'][0] = mydb::cxn()->real_escape_string($_POST['id']);
	$_SESSION['form_memory']['rope'][1] = mydb::cxn()->real_escape_string($_POST['rope_num1']) . "-" . mydb::cxn()->real_escape_string($_POST['rope_num2']);
	$_SESSION['form_memory']['rope'][10]= mydb::cxn()->real_escape_string($_POST['mfr_serial_num']);
	$_SESSION['form_memory']['rope'][2] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_id']);
	$_SESSION['form_memory']['rope'][3] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_name']);
	$_SESSION['form_memory']['rope'][4] = mydb::cxn()->real_escape_string($_POST['in_service_date']);
	$_SESSION['form_memory']['rope'][5] = mydb::cxn()->real_escape_string($_POST['retired_date']);
	$_SESSION['form_memory']['rope'][6] = mydb::cxn()->real_escape_string($_POST['retired_reason']);
	$_SESSION['form_memory']['rope'][7] = mydb::cxn()->real_escape_string($_POST['retired_category']);
	$_SESSION['form_memory']['rope'][8] = mydb::cxn()->real_escape_string($_POST['status']);
	

	// This function is called within a try/catch block - let any exceptions thrown by the ROPE class return to the caller
	$eq = new rope;
	$eq->load($_SESSION['form_memory']['rope'][0]);
	$eq->set('serial_num',$_SESSION['form_memory']['rope'][1]);
	$eq->set('mfr_serial_num',$_SESSION['form_memory']['rope'][10]);
	$eq->set('crew_affiliation_id',$_SESSION['form_memory']['rope'][2]);
	$eq->set('in_service_date',$_SESSION['form_memory']['rope'][4]);
	$eq->set('retired_date',$_SESSION['form_memory']['rope'][5]);
	$eq->set('retired_reason',$_SESSION['form_memory']['rope'][6]);
	$eq->set('retired_category',$_SESSION['form_memory']['rope'][7]);
	$eq->set('status',$_SESSION['form_memory']['rope'][8]);
	
	$eq->save();
	
	return true; // Success
	
} // End: function commit_rope()

/************************************************************************************************************************************/
/***********************************************<< commit_genie >>*******************************************************************/
/************************************************************************************************************************************/
function commit_genie() {
	// Load genie details into the form memory
	$_SESSION['form_memory']['genie'][0] = mydb::cxn()->real_escape_string($_POST['id']);
	$_SESSION['form_memory']['genie'][10] = mydb::cxn()->real_escape_string($_POST['mfr_serial_num']);
	$_SESSION['form_memory']['genie'][1] = mydb::cxn()->real_escape_string($_POST['genie_num1']) . "-" . mydb::cxn()->real_escape_string($_POST['genie_num2']);
	$_SESSION['form_memory']['genie'][2] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_id']);
	$_SESSION['form_memory']['genie'][3] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_name']);
	$_SESSION['form_memory']['genie'][4] = mydb::cxn()->real_escape_string($_POST['in_service_date']);
	$_SESSION['form_memory']['genie'][5] = mydb::cxn()->real_escape_string($_POST['retired_date']);
	$_SESSION['form_memory']['genie'][6] = mydb::cxn()->real_escape_string($_POST['retired_reason']);
	$_SESSION['form_memory']['genie'][7] = mydb::cxn()->real_escape_string($_POST['retired_category']);
	$_SESSION['form_memory']['genie'][8] = mydb::cxn()->real_escape_string($_POST['status']);


	// This function is called within a try/catch block - let any exceptions thrown by the genie class return to the caller
	$eq = new genie;
	$eq->load($_SESSION['form_memory']['genie'][0]);
	$eq->set('serial_num',$_SESSION['form_memory']['genie'][1]);
	$eq->set('mfr_serial_num',$_SESSION['form_memory']['genie'][10]);
	$eq->set('crew_affiliation_id',$_SESSION['form_memory']['genie'][2]);
	$eq->set('in_service_date',$_SESSION['form_memory']['genie'][4]);
	$eq->set('retired_date',$_SESSION['form_memory']['genie'][5]);
	$eq->set('retired_reason',$_SESSION['form_memory']['genie'][6]);
	$eq->set('retired_category',$_SESSION['form_memory']['genie'][7]);
	$eq->set('status',$_SESSION['form_memory']['genie'][8]);
	
	$eq->save();
	
	return true; // Success
	
} // End: function commit_genie()

/************************************************************************************************************************************/
/***********************************************<< commit_letdown_line >>************************************************************/
/************************************************************************************************************************************/
function commit_letdown_line() {
	// Load letdown_line details into the form memory
	$_SESSION['form_memory']['letdown_line'][0] = mydb::cxn()->real_escape_string($_POST['id']);
	$_SESSION['form_memory']['letdown_line'][10] = mydb::cxn()->real_escape_string($_POST['mfr_serial_num']);
	$_SESSION['form_memory']['letdown_line'][1] = mydb::cxn()->real_escape_string($_POST['letdown_line_num1']) . "-" . mydb::cxn()->real_escape_string($_POST['letdown_line_num2']);
	$_SESSION['form_memory']['letdown_line'][2] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_id']);
	$_SESSION['form_memory']['letdown_line'][3] = mydb::cxn()->real_escape_string($_POST['crew_affiliation_name']);
	$_SESSION['form_memory']['letdown_line'][4] = mydb::cxn()->real_escape_string($_POST['in_service_date']);
	$_SESSION['form_memory']['letdown_line'][5] = mydb::cxn()->real_escape_string($_POST['retired_date']);
	$_SESSION['form_memory']['letdown_line'][6] = mydb::cxn()->real_escape_string($_POST['retired_reason']);
	$_SESSION['form_memory']['letdown_line'][7] = mydb::cxn()->real_escape_string($_POST['retired_category']);
	$_SESSION['form_memory']['letdown_line'][8] = mydb::cxn()->real_escape_string($_POST['status']);


	// This function is called within a try/catch block - let any exceptions thrown by the letdown_line class return to the caller
	$eq = new letdown_line;
	$eq->load($_SESSION['form_memory']['letdown_line'][0]);
	$eq->set('serial_num',$_SESSION['form_memory']['letdown_line'][1]);
	$eq->set('mfr_serial_num',$_SESSION['form_memory']['letdown_line'][10]);
	$eq->set('crew_affiliation_id',$_SESSION['form_memory']['letdown_line'][2]);
	$eq->set('in_service_date',$_SESSION['form_memory']['letdown_line'][4]);
	$eq->set('retired_date',$_SESSION['form_memory']['letdown_line'][5]);
	$eq->set('retired_reason',$_SESSION['form_memory']['letdown_line'][6]);
	$eq->set('retired_category',$_SESSION['form_memory']['letdown_line'][7]);
	$eq->set('status',$_SESSION['form_memory']['letdown_line'][8]);
	
	$eq->save();
	
	return true; // Success
	
} // End: function commit_letdown_line()
?>
