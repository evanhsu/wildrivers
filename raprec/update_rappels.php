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
	require_once("classes/operation_class.php");
	require_once("classes/rappel_class.php");
	
	session_name('raprec');
	session_start();
	
	require_once("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	require_once("includes/aircraft_layouts.php");
	
	global $crew;
	$crew = new crew;
	
	global $aircraft_type_array; // Store the different helicopter make/models (e.g. bell_205, bell_206, etc)
	global $operation_type_array;
	global $seating_config_array;
	
	global $which_form;	// This var controls which form_memory to use and also the top-level function that this page will perform
	isset($_GET['function']) ? $which_form = $_GET['function'] : $which_form = 'add_rappel';
	
	$operation_type_array = array(	'operational',
									'proficiency_live',
									'proficiency_tower',
									'certification_new_aircraft',
									'certification_new_hrap');
	
	$seating_config_array = array('bench','hellhole');

	// Make sure this user is allowed to access this page
	if($_SESSION['logged_in'] == 1) {
		// ACCESS GRANTED!
		
		$query = "SELECT DISTINCT shortname, fullname, type FROM aircraft_types";
		$result = mydb::cxn()->query($query);
		while($row = $result->fetch_assoc()) {
			$aircraft_type_array[$row['shortname']] = array('fullname'=>$row['fullname'], 'type'=>$row['type']);
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
<meta name="Description" content="The National Rappel Record Website - This page is used to update rappel records." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if($_SESSION['mobile'] == 1) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script type="text/javascript" src="scripts/searchautosuggest/lib/ajax_framework.js"></script>

<script type="text/javascript" src="scripts/add_rappel_form_functions.js"></script>
<script type="text/javascript" src="scripts/preload_aircraft_images.js"></script>
<script type="text/javascript" src="scripts/astar_b3_config.js"></script>
<script type="text/javascript" src="scripts/bell_205_config.js"></script>
<script type="text/javascript" src="scripts/bell_206_config.js"></script>
<script type="text/javascript" src="scripts/bell_210_config.js"></script>
<script type="text/javascript" src="scripts/bell_212_config.js"></script>
<script type="text/javascript" src="scripts/bell_407_config.js"></script>
<script type="text/javascript" src="scripts/training_tower_config.js"></script>

<script language="javascript" src="scripts/popup_calendar/cal2.js">
/*
Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/
</script>
<script language="javascript" src="scripts/popup_calendar/cal_conf2.js"></script>
<script language="javascript">
function loadStoredDate() {
	var r = document.referrer.split('/');
	r = r[r.length-1];
	r = r.split('?');
	var refer = r[0];
	var d; //This will store the split(date) pieces

	if((refer == 'update_rappels.php')) {
		if(document.getElementById('date').value == "") eraseCookie('add_rappel_date'); //Get rid of the stored date if the php form_memory has been cleared
		
		var rappel_date = readCookie('add_rappel_date');
		if(rappel_date != null) {
			// Change the YEAR portion of the Date field to match the sidebar year value
			d = rappel_date.split('/');
			if(d[2] != document.getElementById('sidebar_year').value) rappel_date = d[0] + '/' + d[1] + '/' + document.getElementById('sidebar_year').value;
			
			document.getElementById('date').value=rappel_date;
		}
	}
}
function updateYear() {
	if(document.add_rappel_form.date.value != '') {
		var year = document.add_rappel_form.date.value.split('/');
		
		year = year[2];
		if(document.getElementById('sidebar_year').value != year) {
			document.getElementById('sidebar_year').value = year;
			createCookie('add_rappel_date',document.add_rappel_form.date.value,0);
			document.forms.sidebar_year_form.submit();
		}
	}
}
function disableForeignCrewInputs() {
	if(document.formFieldsToDisable.listOfFields.value.length > 0) {
		var fieldsToDisable = document.formFieldsToDisable.listOfFields.value.split(',');
		
		for(i=0; i<fieldsToDisable.length; i++) {
			document.getElementById('name_'+ fieldsToDisable[i] +'_text').disabled = "disabled";
			document.getElementById('name_'+ fieldsToDisable[i] +'_id').disabled = "disabled";
			document.getElementById('genie_'+ fieldsToDisable[i] +'_text').disabled = "disabled";
			document.getElementById('genie_'+ fieldsToDisable[i] +'_id').disabled = "disabled";
			document.getElementById('rope_'+ fieldsToDisable[i] +'_text').disabled = "disabled";
			document.getElementById('rope_'+ fieldsToDisable[i] +'_id').disabled = "disabled";
			document.getElementById('rope_'+ fieldsToDisable[i] +'_end_a').disabled = "disabled";
			document.getElementById('rope_'+ fieldsToDisable[i] +'_end_b').disabled = "disabled";
			document.getElementById('knot_'+ fieldsToDisable[i]).disabled = "disabled";
			document.getElementById('eto_'+ fieldsToDisable[i]).disabled = "disabled";
			document.getElementById(fieldsToDisable[i] +'_headshot_filename').disabled = "disabled";
			document.getElementById('comments_'+ fieldsToDisable[i]).disabled = "disabled";
		}
	}
}
</script>
</head>

<body onLoad="loadStoredDate(); disableForeignCrewInputs();">
    <div id="banner_left"><a href="index.php"><img src="images/raprec_banner_left.jpg" style="border:none" alt="RapRec Central Logo" /></a></div>
    <div id="banner_right"><a href="index.php"><img src="images/raprec_banner_right.jpg" style="border:none" alt="RapRec Central" /></a></div>
	
    <div id="left_sidebar">
    	<?php make_menu(); ?>
    </div>
	
    <div id="location_bar"><?php echo $_SESSION['location_bar']; ?></div>
    
    <div id="content" style="text-align:center">
    
<?php
	
	switch($which_form) {
/*---------------------------------------------------------------*/
	case "add_rappel":
		if(isset($_POST['date'])) {
			// Rappel report has been submitted, check data then enter into db
			try {
				//echo "<br>\nPOST Data:<br>\n";
				//print_r($_POST);
				//echo "<br><br><br>\n\n\n\n";
				clear_id_for_blank_text_fields();				// Clear the _id field for each _text/_id field pair that has a blank text field
				remember_form_data();							// Temporarily save all form data to session var so that the form can be automatically repopulated if user must fix a typo
				
				check_add_rappel_form_data();					// Check for correct data types and formats - form validation
				$success_message = add_operation_to_db();		// Add all information to the appropriate db tables - 
																//	This includes placing certain rappel records into 'unapproved' status if the
																//	person who entered the data is on a different crew than the HRAP
				initialize_form_memory($which_form,'force');	// Clear the $_SESSION['form_memory']['add_rappel'] variable
				show_add_rappel_form($success_message);
				
			} catch(Exception $e) {
				show_add_rappel_form($e->getMessage()/*."<br>\n".$e->getFile()."<br>\n".$e->getLine()*/);
			} // End: catch()
		} // End: if(isset($_POST['date']))
		else {
			initialize_form_memory($which_form,'update');
			show_add_rappel_form();
		}
		break;

/*---------------------------------------------------------------*/
	case "edit_rappel":
		show_edit_rappel_form();
		break;

/*---------------------------------------------------------------*/
	case "confirm_rappel":
		confirm_rappel();
		break;
		
/*---------------------------------------------------------------*/
	case "delete_rappel":
		show_delete_rappel_form();
		break;
	
/*---------------------------------------------------------------*/
	case "edit_operation_info":
		if(isset($_POST['date'])) {
			try {
				clear_id_for_blank_text_fields();		// Clear the _id field for each _text/_id field pair that has a blank text field
				remember_form_data();					// Temporarily save all form data to session var so that the form can be automatically repopulated if user must fix a typo
				
				check_add_rappel_form_data();					// Check for correct data types and formats - form validation
				$success_message = update_operation_in_db();	// Add all information to the appropriate db tables - This includes placing certain rappel records into 'unapproved' status if the
																//   person who entered the data is on a different crew than the HRAP
				initialize_form_memory($which_form,'force');	// Clear the $_SESSION['form_memory']['edit_operation_info'] variable
				//show_add_rappel_form($success_message);
				echo "<br /><div class=\"error_msg\">".$success_message."</div><br /><br />\n\n"
					."<a href=\"view_rappels.php?op=".$_GET['op']."\">View this rappel</a>";
				
			} catch (Exception $e) {
				show_add_rappel_form($e->getMessage());
			}
		}
		else {
			try {
				$msg = "";
				if(!isset($_GET['op']) || !operation::exists($_GET['op'])) throw new Exception("You have specified an invalid Operation ID");
				initialize_form_memory($which_form,'force');	// Initialize the $_SESSION['form_memory']['edit_operation_info'] array
				load_op_into_form_memory($_GET['op']);
				show_add_rappel_form();
				
			} catch(Exception $e) {
				initialize_form_memory(which_form,'update'); // Initialize the $_SESSION['form_memory'][$which_form] array
				show_add_rappel_form($e->getMessage());
			}
		}
		break;

/*---------------------------------------------------------------*/
	default:
		//This condition should never occur
		//show_rappel_menu();
		break;
	} // End: switch($page_function)
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
		//echo "<div style=\"width:200px;float:left;\"><img src=\"".$crew->logo_filename."\"></div>\n";
		
		(isset($_GET['function']) && ($_GET['function'] == 'edit_operation_info')) ? $function = 'Editing an Operation' : $function = 'Add a New Operation';
		isset($_SESSION['current_view']['crew']) ? $crew_url = "&crew=".$_SESSION['current_view']['crew']->get('id') : $crew_url = '';
		isset($_SESSION['current_view']['hrap']) ? $hrap_url = "&hrap=".$_SESSION['current_view']['hrap']->get('id') : $hrap_url = '';
		isset($_GET['op']) ? $op_url = "&op=".$_GET['op'] : $op_url = '';
		
		echo "<div style=\"width:750px;text-align:left;\"><h1>".$function."</h1><br>\n"
			/*."<h2>Add a New Rappel</h2><br><br>\n\n"*/
			."<hr style=\"margin:0 auto 0 0; height:2px; width:100%;\"><br>\n\n"
			."<ul style=\"font-weight:bold\"><li><a href=\"".$_SERVER['PHP_SELF']."?function=add_rappel".$crew_url.$hrap_url.$op_url."\">Add a New Rappel</a></li>\n"
			."	<li><a href=\"view_rappels.php?".$crew_url.$hrap_url."\">Edit / Remove an Existing Rappel</a></li></ul>\n"
			."</div>\n\n";
	} // End: function show_header()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_add_rappel_form() **********************************************************/
/*******************************************************************************************************************************/
function show_add_rappel_form($msg="") {
	global $aircraft_type_array;
	global $operation_type_array;
	global $seating_config_array;
	global $which_form;
	
	$function_url = $which_form;
	isset($_GET['crew']) ? $crew_url = "&crew=".$_GET['crew'] : $crew_url = '';
	(isset($_GET['hrap']) && hrap::exists($_GET['hrap'])) ? $hrap_url = "&hrap=".$_GET['hrap'] : $hrap_url = '';
	isset($_GET['op']) ? $op_url = "&op=".$_GET['op'] : $op_url = '';
	
	show_header();

	if($function_url == 'add_rappel') {
		// Initialize default values for the form memory if its blank
		if($_SESSION['form_memory'][$which_form]['aircraft_type'] == '') $_SESSION['form_memory'][$which_form]['aircraft_type'] = 'bell_205';
		if($_SESSION['form_memory'][$which_form]['configuration'] == '') $_SESSION['form_memory'][$which_form]['configuration'] = 'bench';
		if($_SESSION['form_memory'][$which_form]['operation_type']== '') $_SESSION['form_memory'][$which_form]['operation_type'] = 'operational';
	
		// This section allows the operation type, aircraft, tailnumber, pilot and seating config to be specified in the URL for quick bookmarking
		if(isset($_GET['operation_type']) && in_array($_GET['operation_type'],$operation_type_array)) $_SESSION['form_memory'][$which_form]['operation_type'] = $_GET['operation_type'];
		if(isset($_GET['aircraft']) && array_key_exists($_GET['aircraft'],$aircraft_type_array)) $_SESSION['form_memory'][$which_form]['aircraft_type'] = $_GET['aircraft'];
		if(isset($_GET['configuration']) && in_array($_GET['configuration'],$seating_config_array)) $_SESSION['form_memory'][$which_form]['configuration'] = $_GET['configuration'];
		if(isset($_GET['tailnumber']) && (!isset($_SESSION['form_memory'][$which_form]['tailnumber']) || ($_SESSION['form_memory'][$which_form]['tailnumber'] == ''))) $_SESSION['form_memory'][$which_form]['tailnumber'] = $_GET['tailnumber'];
		if(isset($_GET['pilot']) && (!isset($_SESSION['form_memory'][$which_form]['pilot_name']) || $_SESSION['form_memory'][$which_form]['pilot_name'] == "")) $_SESSION['form_memory'][$which_form]['pilot_name'] = $_GET['pilot'];
		if(isset($_GET['today']) && (!isset($_SESSION['form_memory'][$which_form]['date']) || $_SESSION['form_memory'][$which_form]['date'] == "")) $_SESSION['form_memory'][$which_form]['date'] = date('m/d/Y');
	}
	
	
	if($msg != "") echo "<div class=\"error_msg\">".$msg."</div><br><br>\n\n";
	
	echo "<br>\n<div style=\"width:100%;text-align:center;\">\n"
		."<form action=\"#\" method=\"post\" name=\"memory_form\" id=\"memory_form\">\n"
		."	<input type=\"hidden\" id=\"operation_type_memory\" value=\"".$_SESSION['form_memory'][$which_form]['operation_type']."\">\n"
		."	<input type=\"hidden\" id=\"aircraft_type_memory\" value=\"".$_SESSION['form_memory'][$which_form]['aircraft_type']."\">\n"
		."	<input type=\"hidden\" id=\"configuration_memory\" value=\"".$_SESSION['form_memory'][$which_form]['configuration']."\">\n"
		."</form>\n"
		."<form action=\"".$_SERVER['PHP_SELF']."?function=".$function_url.$crew_url.$hrap_url.$op_url."\" method=\"post\" name=\"add_rappel_form\" id=\"add_rappel_form\" autocomplete=\"off\" >\n\n\n";
		
	/* echo "<script type=\"text/javascript\">updateForm();</script>\n\n\n"; */
	initialize_add_rappel_form_from_memory();
	
	echo "</form>\n"
		."</div><br><br><br>\n\n";

} // End: show_add_rappel_menu()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: initialize_add_rappel_form_from_memory() ****************************************/
/*******************************************************************************************************************************/
function initialize_add_rappel_form_from_memory() {
	global $aircraft_type_array;
	global $which_form;

	echo "<table id=\"form_table\" class=\"form_table\" style=\"margin:0 auto 0 auto; border:2px solid #bbbbbb;\">\n"
			."<tr><th colspan=\"3\" style=\"text-align:left\">Operation Info</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"text-align:center\">\n"
					."<table style=\"margin:0 auto 0 auto;\" border=0>\n"
						."<tr><td style=\"text-align:right;width:125px;\">Date</td>\n"
							."<td style=\"text-align:left;\">:</td>\n"
							."<td style=\"text-align:left;\"><input type=\"text\" name=\"date\" id=\"date\" value=\"".$_SESSION['form_memory'][$which_form]['date']."\" style=\"width:70px;\" readonly=\"readonly\" "
								."onFocus=\"updateYear();\" > "
								."<small><a href=\"javascript:showCal('Calendar_operation_date')\">Select Date</a></small></td></tr>\n"
						."<tr><td style=\"text-align:right;\">Type</td><td style=\"text-align:left;\">:</td>\n"
							."<td style=\"text-align:left;\">\n";
							
							echo "<select name=\"operation_type\" onChange=\"set_operation_type(); updateForm()\">\n"
	
								."<option value=\"operational\"";
								if($_SESSION['form_memory'][$which_form]['operation_type'] == "operational") echo " selected";
								echo ">Operational</option>\n"
								
								."<option value=\"proficiency_live\"";
								if($_SESSION['form_memory'][$which_form]['operation_type'] == "proficiency_live") echo " selected";
								echo ">Proficiency (Helicopter)</option>\n"
								
								."<option value=\"proficiency_tower\"";
								if($_SESSION['form_memory'][$which_form]['operation_type'] == "proficiency_tower") echo " selected";
								echo ">Proficiency (Tower)</option>\n"
								
								."<option value=\"certification_new_aircraft\"";
								if($_SESSION['form_memory'][$which_form]['operation_type'] == "certification_new_aircraft") echo " selected";
								echo ">Certification in New Aircraft</option>\n"
								
								."<option value=\"certification_new_hrap\"";
								if($_SESSION['form_memory'][$which_form]['operation_type'] == "certification_new_hrap") echo " selected";
								echo ">Certification for New HRAP</option>\n"
							
							."</select>\n"
							."</td></tr>\n\n";
							
	if(($_SESSION['form_memory'][$which_form]['operation_type'] == 'operational') || ($_SESSION['form_memory'][$which_form]['operation_type'] == '')) {
		echo "<tr><td style=\"text-align:right;\">Incident #</td><td style=\"text-align:left;\">:</td>"
			."	<td style=\"text-align:left;\">"
			."		<input type=\"text\" name=\"inc_1\" value=\"".$_SESSION['form_memory'][$which_form]['inc_1']."\" style=\"width:25px;text-transform:uppercase;\">-"
			."		<input type=\"text\" name=\"inc_2\" value=\"".$_SESSION['form_memory'][$which_form]['inc_2']."\" style=\"width:30px;text-transform:uppercase;\">-"
			."		<input type=\"text\" name=\"inc_3\" value=\"".$_SESSION['form_memory'][$which_form]['inc_3']."\" style=\"width:60px;text-transform:uppercase;\">"
			."	</td>\n"
			."</tr>\n\n";
	}
	else {
		echo "<tr style=\"visibility:hidden;\">\n"
			."	<td><input type=\"hidden\" name=\"inc_1\" value=\"\"></td>\n"
			."	<td><input type=\"hidden\" name=\"inc_2\" value=\"\"></td>\n"
			."	<td><input type=\"hidden\" name=\"inc_3\" value=\"\"></td>\n"
			."</tr>\n\n";
	}
	
	echo "<tr style=\"\">\n"
		."	<td style=\"text-align:right;\">Location</td>\n"
		."	<td style=\"text-align:middle;\">:</td>\n"
		."	<td style=\"text-align:left;\"><input type=\"text\" name=\"location\" value=\"".$_SESSION['form_memory'][$which_form]['location']."\" style=\"width:150px\"></td>\n"
		."</tr>\n\n";
	
	if($_SESSION['form_memory'][$which_form]['operation_type'] == "proficiency_tower") {
		echo "<tr style=\"visibility:hidden;\">\n"
			."	<td colspan=\"3\"><input type=\"hidden\" name=\"height\" value=\"50\"></td></tr>\n"
			."	<td colspan=\"3\"><input type=\"hidden\" name=\"canopy_opening\" value=\"0\"></td></tr>\n\n"
			."</table></td></tr>\n\n";
	}
	else {
		echo "<tr>	<td style=\"text-align:right;\">Height<br>(feet)</td>\n"
			."		<td style=\"vertical-align:middle;\">:</td>\n"
			."		<td style=\"text-align:left;\"><input type=\"text\" name=\"height\" value=\"".$_SESSION['form_memory'][$which_form]['height']."\" style=\"width:50px\"></td></tr>\n"
			."<tr>\n"
			."	<td style=\"text-align:right;\">Canopy Opening<br>(sq. feet)</td>\n"
			."	<td style=\"vertical-align:middle; text-align:left;\">:</td>\n"
			."	<td style=\"text-align:left;\"><input type=\"text\" name=\"canopy_opening\" value=\"".$_SESSION['form_memory'][$which_form]['canopy_opening']."\" style=\"width:50px\"></td></tr>\n\n"
			."</table></td></tr>\n\n";
	}
		echo "<tr><th colspan=\"3\" style=\"text-align:left;\">Pilot & Aircraft</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"text-align:center\">\n";
				
	if($_SESSION['form_memory'][$which_form]['operation_type'] == "proficiency_tower") {
	echo	"There is no pilot or aircraft information needed for a tower rappel.\n"
			."<input type=\"hidden\" name=\"pilot_name\" value=\"0\">\n"
			."<input type=\"hidden\" name=\"tailnumber\" value=\"0\">\n"
			."<input type=\"hidden\" name=\"aircraft_type\" value=\"tower\">\n"
			."<input type=\"hidden\" name=\"configuration\" value=\"".$_SESSION['form_memory'][$which_form]['configuration']."\">\n";
	}
	else {
		echo "<table style=\"margin:0 auto 0 auto; width:500px;\">\n"
				."<tr><td style=\"text-align:right;width:190px\">Pilot</td>\n"
				."		<td style=\"width:10px;\">:</td>\n"
				."		<td style=\"text-align:left;\"><input type=\"text\" name=\"pilot_name\" value=\"".$_SESSION['form_memory'][$which_form]['pilot_name']."\" style=\"width:100px;\"></td></tr>\n"
				
				."<tr><td style=\"text-align:right;\">Aircraft Type</td><td>:</td><td style=\"text-align:left;\">\n"
				."		<select name=\"aircraft_type\" onChange=\"set_aircraft_type(); updateForm()\">\n";

		$medium_aircraft = array();
		foreach($aircraft_type_array as $shortname=>$info) {
			echo "<option value=\"".$shortname."\"";
			if($_SESSION['form_memory'][$which_form]['aircraft_type'] == $shortname) echo " selected";
			echo">".$info['fullname']."</option>\n";
			if($info['type'] == 2) $medium_aircraft[] = $shortname; // Generate a list of MEDIUM (Type 2) aircraft to determine when to show/hide the configuration menu
	}
	echo "</select>\n";


					if(in_array($_SESSION['form_memory'][$which_form]['aircraft_type'], $medium_aircraft)) echo "<select name=\"configuration\" onChange=\"set_configuration(); updateForm();\">\n";
					else echo "<select name=\"configuration\" style=\"visibility:hidden\" onChange=\"set_configuration(); updateForm();\">\n";
					
					if($_SESSION['form_memory'][$which_form]['configuration'] == 'hellhole') echo "<option value=\"hellhole\" selected>Hellhole</option>\n"
																						."<option value=\"bench\">Bench</option>\n";
					else echo "<option value=\"hellhole\">Hellhole</option>\n"
							."<option value=\"bench\" selected>Bench</option>\n";
							
					echo "</select>\n";
					echo "</td></tr>\n";
								
					echo "<tr>	<td style=\"text-align:right;\">Tailnumber</td>\n"
						."		<td>:</td>\n"
						."		<td style=\"text-align:left;\"><input type=\"text\" name=\"tailnumber\" value=\"".$_SESSION['form_memory'][$which_form]['tailnumber']."\" style=\"width:50px;text-transform:uppercase;\"></td></tr>\n"
						."</table>\n";
				}
			echo "</td></tr>\n\n"
			."<tr><th colspan=\"3\" style=\"text-align:left;\">Rappeller Configuration</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"text-align:center;\" id=\"rappeller_configuration\">\n\n";
			
			if($_SESSION['form_memory'][$which_form]['operation_type'] == "proficiency_tower") $config_to_load = "training_tower";
			else {
				$config_to_load = $_SESSION['form_memory'][$which_form]['aircraft_type'].'_'.$_SESSION['form_memory'][$which_form]['configuration'];
			}
			
			if(function_exists($config_to_load)) echo $config_to_load();
			else {
				echo "<br><div class=\"error_msg\">That Aircraft is not currently available. Please change your selection</div><br><br>\n\n";
				echo "<div style=\"visibility:hidden; height:0px;\">\n"; // Create the form so that values will be remembered, but hide it from view
				bell_205_hellhole();
				echo "\n</div>\n\n";
			}

			echo "</td></tr>\n\n"
			."<tr><th colspan=\"3\" style=\"text-align:left;\" >Cargo Letdown</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"border-bottom:2px solid #bbbbbb;\">\n"
			."		<div id=\"letdown_section\" style=\"text-align:left; width:200px; margin:0 auto 0 auto;\">\n";
			
			for($i=1; $i<=6; $i++) {
				echo "Letdown #".$i.": "
						." <input type=\"text\" name=\"letdown_".$i."_text\" id=\"letdown_".$i."_text\" value=\"".$_SESSION['form_memory'][$which_form]['letdown_'.$i.'_text']."\" "
								."style=\"width:100px;text-transform:uppercase;\" onkeyup=\"javascript:autosuggest('letdown_".$i."','letdown_line');\" onFocus=\"this.select()\" ><br>\n"
						." <input type=\"hidden\" name=\"letdown_".$i."_id\" id=\"letdown_".$i."_id\" value=\"".$_SESSION['form_memory'][$which_form]['letdown_'.$i.'_id']."\" >\n"
						."<div id=\"letdown_".$i."_results\" class=\"results\"></div>\n\n";
			}
			
			echo "		</div>\n"
			."</td></tr>\n\n"
			."<tr><td colspan=\"3\" style=\"text-align:center;\" >\n"
			."		<input type=\"button\" value=\"Save\" class=\"form_button\" style=\"width:10em; height:2em; vertical-align:middle; font-size:1.5em;\" onClick=\"submit();\"></td></tr>\n"
			."</table>";

}

function create_spotter($position) {
	global $which_form;
	// Input 'position' must be one of the following: 'left', 'center', 'right'
	// This value describes which side of the aircraft schematic the spotter's info will appear on
	if($_SESSION['form_memory'][$which_form]['spotter_headshot_filename'] == '') $_SESSION['form_memory'][$which_form]['spotter_headshot_filename'] = 'images/hrap_headshots/nobody.jpg';
	
	$margin = '0 auto 0 auto';
	$align = 'left';
	
	if($position == 'left') {
		$margin = '0 0 0 auto';
		$align = 'right';
	}
	else if($position == 'right') {
		$margin = '0 auto 0 0';
		$align = 'left';
	}

	$text= "<table style=\"margin:".$margin."; border:2px dashed #555555;\">\n"
			."<tr><td colspan=\"2\" style=\"text-align:".$align.";\"><h3>Spotter</h3></td></tr>\n"
			."<tr><td style=\"text-align:".$align.";\">\n"
			."<img src=\"".$_SESSION['form_memory'][$which_form]['spotter_headshot_filename']."\" id=\"spotter_headshot\" name=\"spotter_headshot\" style=\"border:2px solid #555555; width:75px; height:75px;\">\n"
								."<input type=\"hidden\" id=\"spotter_headshot_filename\" name=\"spotter_headshot_filename\" value=\"".$_SESSION['form_memory'][$which_form]['spotter_headshot_filename']."\"></td></tr>\n"
			."<tr><td colspan=\"2\" style=\"text-align:".$align.";\">Name:<br><input type=\"text\" name=\"name_spotter_text\" id=\"name_spotter_text\" value=\"".$_SESSION['form_memory'][$which_form]['name_spotter_text']."\" "
					."style=\"width:98%;\" onkeyup=\"javascript:autosuggest('name_spotter','spotter');\" onFocus=\"this.select()\" ><br>\n"
			."<input type=\"hidden\" name=\"name_spotter_id\" id=\"name_spotter_id\" value=\"".$_SESSION['form_memory'][$which_form]['name_spotter_id']."\" >\n"
			."<div id=\"name_spotter_results\" class=\"results\"></div>\n\n"
			."</td></tr>\n"
		."</table>\n";
	return $text;
}
function create_left_rap($stick) {
	global $which_form;
	//The input string 'stick' should be one of the following: '1', '2' or '3'
	$suffix = "stick".$stick."_left";
	if($_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename'] == '') $_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename'] = 'images/hrap_headshots/nobody.jpg';
	
	$text =	"<table style=\"margin:0 0 0 auto; border:2px dashed #555555; width:200px;\">\n"
					."<tr><td rowspan=\"2\" style=\"text-align:left; vertical-align:top;\">\n";
	
	if($stick > 1) {
		// Add a javascript to the hidden field that will copy the Rope info from the 1st stick into this
		// stick after a rappeller is selected for this stick.
		$text .= "Name:<br><input type=\"text\" name=\"name_".$suffix."_text\" id=\"name_".$suffix."_text\" value=\"".
				$_SESSION['form_memory'][$which_form]['name_'.$suffix.'_text']."\" style=\"width:100px;margin-bottom:5px;\" "
				."onkeyup=\"javascript:autosuggest('name_".$suffix."','hrap_for_rappel');\" onFocus=\"this.select()\" "
				."onChange=\"copyRopeFrom1stStick(".$stick.",'left');\"><br>\n";
	}
	else {
		// If this is the 1st stick, omit the javascript to copy rope data.
		$text .= "Name:<br><input type=\"text\" name=\"name_".$suffix."_text\" id=\"name_".$suffix."_text\" value=\"".
				$_SESSION['form_memory'][$which_form]['name_'.$suffix.'_text']."\" style=\"width:100px;margin-bottom:5px;\" "
				."onkeyup=\"javascript:autosuggest('name_".$suffix."','hrap_for_rappel');\" onFocus=\"this.select()\"><br>\n";
	}
	
	$text .= "<input type=\"hidden\" name=\"name_".$suffix."_id\" id=\"name_".$suffix."_id\" value=\"".
			$_SESSION['form_memory'][$which_form]['name_'.$suffix.'_id']."\">\n";
	
	$text .= "<div id=\"name_".$suffix."_results\" class=\"results\"></div>\n\n"
								
								."Genie:<br><input type=\"text\" name=\"genie_".$suffix."_text\" id=\"genie_".$suffix."_text\" value=\"".$_SESSION['form_memory'][$which_form]['genie_'.$suffix.'_text']."\" style=\"width:75px;\" "
											."onkeyup=\"javascript:autosuggest('genie_".$suffix."','genie');\"  onFocus=\"this.select()\">"
								."<input type=\"hidden\" name=\"genie_".$suffix."_id\" id=\"genie_".$suffix."_id\" value=\"".$_SESSION['form_memory'][$which_form]['genie_'.$suffix.'_id']."\">\n"
								."<div id=\"genie_".$suffix."_results\" class=\"results\"></div>\n\n"
								
								."Rope:<br><input type=\"text\" name=\"rope_".$suffix."_text\" id=\"rope_".$suffix."_text\" value=\"".$_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_text']."\" style=\"width:75px;margin-bottom:5px;\" "
											."onkeyup=\"javascript:autosuggest('rope_".$suffix."','rope');\"  onFocus=\"this.select()\" ><br>\n"
								."<input type=\"hidden\" name=\"rope_".$suffix."_id\" id=\"rope_".$suffix."_id\" value=\"".$_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_id']."\">\n"
								."<div id=\"rope_".$suffix."_results\" class=\"results\"></div>\n\n"
								
								."Rope End:<br>"
									."A<input type=\"radio\" name=\"rope_".$suffix."_end\" id=\"rope_".$suffix."_end_a\" value=\"a\" ";
								if($_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_end'] == "a") $text .= "checked";
								$text .= "> &nbsp;"
									."B<input type=\"radio\" name=\"rope_".$suffix."_end\" id=\"rope_".$suffix."_end_b\" value=\"b\" ";
								if($_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_end'] == "b") $text .= "checked";
								$text .= "></td>\n\n"
								
						."<td style=\"text-align:right;vertical-align:top;\">"
								."<h3>".num_suffix($stick)." Stick</h3><br>"
								."<img src=\"".$_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename']."\" id=\"".$suffix."_headshot\" name=\"".$suffix."_headshot\" style=\"border:2px solid #555555; width:75px; height:75px;\">\n"
								."<input type=\"hidden\" id=\"".$suffix."_headshot_filename\" name=\"".$suffix."_headshot_filename\" value=\"".$_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename']."\"></td></tr>\n"
					."<tr><td style=\"text-align:right;\">\n"
								."Knot:<input type=\"checkbox\" name=\"knot_".$suffix."\" id=\"knot_".$suffix."\" value=\"1\" tabindex=\"-1\" ";
								if($_SESSION['form_memory'][$which_form]['knot_'.$suffix] == "1") $text .= "checked";
								$text .= ">\n"
								."ETO:<input type=\"checkbox\" name=\"eto_".$suffix."\" id=\"eto_".$suffix."\" value=\"1\" tabindex=\"-1\" ";
								if($_SESSION['form_memory'][$which_form]['eto_'.$suffix] == "1") $text .= "checked";
								$text .= "></td></tr>\n"
					."<tr><td colspan=\"2\" style=\"text-align:left;\">\n"
								."Comments: <input type=\"text\" name=\"comments_".$suffix."\" id=\"comments_".$suffix."\" value=\"".$_SESSION['form_memory'][$which_form]['comments_'.$suffix]."\" tabindex=\"-1\" style=\"width:99%\"></td></tr>\n"
				."</table>\n";
	return $text;
}
function create_right_rap($stick) {
	global $which_form;
	//The input string 'stick' should be one of the following: '1', '2' or '3'
	$suffix = "stick".$stick."_right";
	if($_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename'] == '') $_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename'] = 'images/hrap_headshots/nobody.jpg';
	
	$text =	"<table style=\"margin:0 auto 0 0; border:2px dashed #555555; width:200px;\">\n"
					."<tr><td style=\"text-align:left;vertical-align:top;\">"
								."<h3>".num_suffix($stick)." Stick</h3><br>"
								."<img src=\"".$_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename']."\" id=\"".$suffix."_headshot\" name=\"".$suffix."_headshot\" style=\"border:2px solid #555555; width:75px; height:75px;\">\n"
								."<input type=\"hidden\" id=\"".$suffix."_headshot_filename\" name=\"".$suffix."_headshot_filename\" value=\"".$_SESSION['form_memory'][$which_form][$suffix.'_headshot_filename']."\"></td>\n"
								
						."<td rowspan=\"2\" style=\"text-align:left; vertical-align:top;\">\n";
	if($stick > 1) {
		// Add a javascript to the hidden field that will copy the Rope info from the 1st stick into this
		// stick after a rappeller is selected for this stick.
		$text .= "Name:<br><input type=\"text\" name=\"name_".$suffix."_text\" id=\"name_".$suffix."_text\" value=\"".
				$_SESSION['form_memory'][$which_form]['name_'.$suffix.'_text']."\" style=\"width:100px;margin-bottom:5px;\" "
				."onkeyup=\"javascript:autosuggest('name_".$suffix."','hrap_for_rappel');\" onFocus=\"this.select()\" "
				."onChange=\"copyRopeFrom1stStick(".$stick.",'right');\"><br>\n";
	}
	else {
		// If this is the 1st stick, omit the javascript to copy rope data.
		$text .= "Name:<br><input type=\"text\" name=\"name_".$suffix."_text\" id=\"name_".$suffix."_text\" value=\"".
				$_SESSION['form_memory'][$which_form]['name_'.$suffix.'_text']."\" style=\"width:100px;margin-bottom:5px;\" "
				."onkeyup=\"javascript:autosuggest('name_".$suffix."','hrap_for_rappel');\" onFocus=\"this.select()\"><br>\n";
	}

	$text .= "<input type=\"hidden\" name=\"name_".$suffix."_id\" id=\"name_".$suffix."_id\" value=\"".$_SESSION['form_memory'][$which_form]['name_'.$suffix.'_id']."\">\n"
								."<div id=\"name_".$suffix."_results\" class=\"results\"></div>\n\n"
								
								."Genie:<br><input type=\"text\" name=\"genie_".$suffix."_text\" id=\"genie_".$suffix."_text\" value=\"".$_SESSION['form_memory'][$which_form]['genie_'.$suffix.'_text']."\" style=\"width:75px;\" "
											."onkeyup=\"javascript:autosuggest('genie_".$suffix."','genie');\"  onFocus=\"this.select()\" >"
								."<input type=\"hidden\" name=\"genie_".$suffix."_id\" id=\"genie_".$suffix."_id\" value=\"".$_SESSION['form_memory'][$which_form]['genie_'.$suffix.'_id']."\">\n"
								."<div id=\"genie_".$suffix."_results\" class=\"results\"></div>\n\n"
								
								."Rope:<br><input type=\"text\" name=\"rope_".$suffix."_text\" id=\"rope_".$suffix."_text\" value=\"".$_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_text']."\" style=\"width:75px;margin-bottom:5px;\" "
											."onkeyup=\"javascript:autosuggest('rope_".$suffix."','rope');\"  onFocus=\"this.select()\" ><br>\n"
								."<input type=\"hidden\" name=\"rope_".$suffix."_id\" id=\"rope_".$suffix."_id\" value=\"".$_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_id']."\">\n"
								."<div id=\"rope_".$suffix."_results\" class=\"results\"></div>\n\n"
								
								."Rope End:<br>"
									."A<input type=\"radio\" name=\"rope_".$suffix."_end\" id=\"rope_".$suffix."_end_a\" value=\"a\" ";
								if($_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_end'] == "a") $text .= "checked";
								$text .= "> &nbsp;"
									."B<input type=\"radio\" name=\"rope_".$suffix."_end\" id=\"rope_".$suffix."_end_b\" value=\"b\" ";
								if($_SESSION['form_memory'][$which_form]['rope_'.$suffix.'_end'] == "b") $text .= "checked";
								$text .= "></td></tr>\n\n"
									
					."<tr><td style=\"text-align:right;\">\n"
								."Knot:<input type=\"checkbox\" name=\"knot_".$suffix."\" id=\"knot_".$suffix."\" value=\"1\" tabindex=\"-1\" ";
								if($_SESSION['form_memory'][$which_form]['knot_'.$suffix] == "1") $text .= "checked";
								$text .= "><br>\n"
								."ETO:<input type=\"checkbox\" name=\"eto_".$suffix."\" id=\"eto_".$suffix."\" value=\"1\" tabindex=\"-1\" ";
								if($_SESSION['form_memory'][$which_form]['eto_'.$suffix] == "1") $text .= "checked";
								$text .= "></td></tr>\n"	
							
					."<tr><td colspan=\"2\" style=\"text-align:left;\">\n"
								."Comments: <input type=\"text\" name=\"comments_".$suffix."\" id=\"comments_".$suffix."\" value=\"".$_SESSION['form_memory'][$which_form]['comments_'.$suffix]."\" tabindex=\"-1\" style=\"width:99%\"></td></tr>\n"
				."</table>\n";
	return $text;
}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: confirm_rappel() *********************************************************/
/*******************************************************************************************************************************/
	function confirm_rappel() {
		// Check rappel ID for validity
		// Check user credentials to see if they are allowed to confirm the requested rappel
		// Confirm the rappel
		// Display a success / failure message
		// Provide a link to view the Operation that contains the requested rappel
		try {
			$rap = new rappel;
			$rap->load($_GET['rap_id']);
			if($rap->is_confirmable()) $rap->save();
			else throw new Exception('You do not have permission to Confirm this rappel.');
			
			echo "You have successfully confirmed this rappel information!<br>\n"
				."<a href=\"view_rappels.php?op=".$rap->get('operation_id')."\">View this Rappel</a>\n";
			
		} catch (Exception $e) {
			echo "<br><div class=\"error_msg\">".$e->getMessage()."</div><br>\n"
				."<a href=\"view_rappels.php?op=".$rap->get('operation_id')."\">View this Rappel</a>\n";
		}

	} // End: confirm_rappel()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_delete_rappel_form() *******************************************************/
/*******************************************************************************************************************************/
	function show_delete_rappel_form($msg="") {
		// Check rappel ID for validity
		// Check user credentials to see if they are allowed to delete the requested rappel
		// Delete the rappel
		// Display a success / failure message
		// Provide a link to view the Operation that contained the requested rappel
		try {
			$rap = new rappel;
			$rap->load($_GET['rap_id']);
			$rap->delete(); // This function performs its own check on user credentials
			
			echo "You have successfully deleted this rappel!<br>\n"
				."<a href=\"view_rappels.php?op=".$rap->get('operation_id')."\">View the affected Operation</a>\n";
			
		} catch (Exception $e) {
			echo "<br><div class=\"error_msg\">".$e->getMessage()."</div><br>\n"
				."<a href=\"view_rappels.php?op=".$rap->get('operation_id')."\">View the affected Operation</a>\n";
		}

	} // End: show_delete_rappel_menu()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_rappel_menu() **************************************************************/
/*******************************************************************************************************************************/
	function show_rappel_menu($msg="") {
		// View all rappels for the specified Crew
		// View all rappels for the specified HRAP
		echo $msg;
		// Tailnumber Lookup: http://www.landings.com/evird.acgi?pass=121140738&ref=-&mtd=41&cgi=%2Fcgi-bin%2Fnph-search_nnr&var=0&buf=66&src=_landings%2Fpages%2Fsearch_nnr.html&nnumber=TAILNUMBER_GOES_HERE
		// OR: http://registry.faa.gov/aircraftinquiry/NNumSQL.asp?NNumbertxt=TAILNUMBER_GOES_HERE&cmndfind.x=0&cmndfind.y=0
		

	} // End: show_rappel_menu()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: remember_form_data() ************************************************************/
/*******************************************************************************************************************************/
	function remember_form_data() {
		global $which_form;
		
		if(isset($_POST)) {
			extract($_POST, EXTR_PREFIX_ALL, 'post');
			
			initialize_form_memory($which_form,'force');
			
			foreach($_SESSION['form_memory'][$which_form] as $key=>$value) {
				if(isset($_POST[$key]) && ($_POST[$key] != '')) $_SESSION['form_memory'][$which_form][$key] = $_POST[$key];
			}
							
			
		}
		else {
			// Do Nothing - if POST is not set, there is no form data to remember
			//throw new Exception('No POST data was passed to \'remember_form_data()\' in update_rappels.php');
		}

	} // End: remember_form_data()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: initialize_form_memory() ********************************************************/
/*******************************************************************************************************************************/
	function initialize_form_memory($which_form,$mode='update') {
		// $which_form designates the specific form_memory to act on
		// This function operates in one of two possible modes, specified by the input parameter $mode.
		// $mode == 'force'	:	All elements of the $_SESSION['form_memory'][$which_form] array are reset to an empty string ''
		// $mode == 'update':	Elements of the $_SESSION['form_memory'][$which_form] array which have not yet been
		//						initialized are set to an empty string, but no existing values are overwritten
		
		$form_memory_keys = array(
							'date',
							'operation_type',
							'inc_1',
							'inc_2',
							'inc_3',
							'location',
							'height',
							'canopy_opening',
							'pilot_name',
							'aircraft_type',
							'configuration',
							'tailnumber',
							
							'name_spotter_text',
							'name_spotter_id',
							'spotter_headshot_filename',
							
							'name_stick1_left_text',
							'name_stick1_left_id',
							'genie_stick1_left_text',
							'genie_stick1_left_id',
							'rope_stick1_left_text',
							'rope_stick1_left_id',
							'rope_stick1_left_end',
							'knot_stick1_left',
							'eto_stick1_left',
							'stick1_left_headshot_filename',
							'comments_stick1_left',
							
							'name_stick1_right_text',
							'name_stick1_right_id',
							'genie_stick1_right_text',
							'genie_stick1_right_id',
							'rope_stick1_right_text',
							'rope_stick1_right_id',
							'rope_stick1_right_end',
							'knot_stick1_right',
							'eto_stick1_right',
							'stick1_right_headshot_filename',
							'comments_stick1_right',
							
							'name_stick2_left_text',
							'name_stick2_left_id',
							'genie_stick2_left_text',
							'genie_stick2_left_id',
							'rope_stick2_left_text',
							'rope_stick2_left_id',
							'rope_stick2_left_end',
							'knot_stick2_left',
							'eto_stick2_left',
							'stick2_left_headshot_filename',
							'comments_stick2_left',
							
							'name_stick2_right_text',
							'name_stick2_right_id',
							'genie_stick2_right_text',
							'genie_stick2_right_id',
							'rope_stick2_right_text',
							'rope_stick2_right_id',
							'rope_stick2_right_end',
							'knot_stick2_right',
							'eto_stick2_right',
							'stick2_right_headshot_filename',
							'comments_stick2_right',
							
							'name_stick3_left_text',
							'name_stick3_left_id',
							'genie_stick3_left_text',
							'genie_stick3_left_id',
							'rope_stick3_left_text',
							'rope_stick3_left_id',
							'rope_stick3_left_end',
							'knot_stick3_left',
							'eto_stick3_left',
							'stick3_left_headshot_filename',
							'comments_stick3_left',
							
							'name_stick3_right_text',
							'name_stick3_right_id',
							'genie_stick3_right_text',
							'genie_stick3_right_id',
							'rope_stick3_right_text',
							'rope_stick3_right_id',
							'rope_stick3_right_end',
							'knot_stick3_right',
							'eto_stick3_right',
							'stick3_right_headshot_filename',
							'comments_stick3_right',
							
							'letdown_count',
							'letdown_1_text',
							'letdown_2_text',
							'letdown_3_text',
							'letdown_4_text',
							'letdown_5_text',
							'letdown_6_text',
							'letdown_1_id',
							'letdown_2_id',
							'letdown_3_id',
							'letdown_4_id',
							'letdown_5_id',
							'letdown_6_id');
		
		switch($mode) {
		case 'force':
			foreach($form_memory_keys as $key) {
				$_SESSION['form_memory'][$which_form][$key] = '';
			}
			break;
		
		case 'update':
			foreach($form_memory_keys as $key) {
				if(!isset($_SESSION['form_memory'][$which_form][$key])) $_SESSION['form_memory'][$which_form][$key] = '';
			}
			break;
		}// End: switch($mode)
		
	} // End: initialize_form_memory()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: clear_id_for_blank_text_fields() ************************************************/
/*******************************************************************************************************************************/
	function clear_id_for_blank_text_fields() {

		$sticks= array(1,2,3);
		$doors = array('left','right');
		
		foreach($sticks AS $stick) {
			foreach($doors AS $door) {
				foreach(array('name','genie','rope') AS $section) {
					$position = 'stick'.$stick.'_'.$door;
					
					$id_field = $section."_".$position."_id";
					$text_field=$section."_".$position."_text";
					
					if(!isset($_POST[$text_field]) || ($_POST[$text_field] == "")) {
						$_POST[$id_field] = ""; // Clear the POST'ed ID if the text field was blank
						if($section == 'name') $_POST[$position."_headshot_filename"] = ""; //Also clear the headshot image if the text field is blank
					}
				} // End: foreach(array('name','genie','rope') AS $section) {
			} // End: foreach($doors AS $door)
		} // End: foreach($sticks AS $stick)

		// Check for letdown line id mismatch (like above)
		for($i=1; $i<=6; $i++) {
			if($_POST['letdown_'.$i.'_text'] == "") $_POST['letdown_'.$i.'_id'] = "";
		}
		
		// Check for blank spotter name
		if($_POST['name_spotter_text'] == "") {
			$_POST['name_spotter_id'] = "";
			$_POST['spotter_headshot_filename'] = "";
		}
		
		return true;
	} // End: function clear_id_for_blank_text_fields()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: check_add_rappel_form_data() ****************************************************/
/*******************************************************************************************************************************/
	function check_add_rappel_form_data() {
		global $which_form;
		
		//Make sure info for each HRAP is either complete or non-existant (no partial info, all or nothing)
		$incomplete_sections = NULL;
		$id_mismatch_fields = NULL;
		$duplicate_operations = array();
		
		$sticks= array(1,2,3);
		$doors = array('left','right');
		//print_r($_POST);
		foreach($sticks AS $stick) {
			foreach($doors AS $door) {
				$position = 'stick'.$stick.'_'.$door;
				
				$name_id_field = 'name_'.$position.'_id';
				$name_text_field = 'name_'.$position.'_text';
				
				$genie_id_field= 'genie_'.$position.'_id';
				$genie_text_field= 'genie_'.$position.'_text';
				
				$rope_id_field = 'rope_'.$position.'_id';
				$rope_text_field = 'rope_'.$position.'_text';
				
				$rope_end_field = 'rope_'.$position.'_end';
				
				if(array_key_exists($name_id_field,$_POST)) {
				  $null_so_far = true;
				  $partial = false;
	  
				  if( $_POST[$name_id_field] != '') $null_so_far = false;
				  if((!isset($_POST[$genie_text_field]) || $_POST[$genie_text_field] == '') != ($null_so_far)) $partial = true; // Use the text field to identify rope & genie (instead of the dB ID number). This allows a
				  if((!isset($_POST[$rope_text_field]) || $_POST[$rope_text_field]  == '') != ($null_so_far)) $partial = true;	// rope to be entered in this field that does not exist in the dB.  It will be added implicitly.
				  if(!isset($_POST[$rope_end_field]) && (!$null_so_far)) $partial = true; // It's OK if ONLY the rope_end is selected, since there's no way to unselect a radio button
				  
				  //****************************************************************************************
				  //***** ALLOW MISSING ROPE AND GENIE INFO ************************************************
				  //***** A 'rappel' can be entered without rope and genie info (Just rappeller name) *****
				  //****************************************************************************************
				  /*
				  if($partial) {
					  if(strlen($incomplete_sections) > 1) $incomplete_sections .= ", ";
					  $incomplete_sections .= "Stick ".$stick.":".$door;
				  }
				  */
				  
				  if(!$partial && !$null_so_far) {
					  // Check each rope and genie serial_num to see if they exist in the database.
					  // If an item is NOT found in the database, create one
					  $genie_query = "SELECT items.serial_num as genie
									  FROM items
									  WHERE LOWER(items.serial_num) = '".strtolower(mydb::cxn()->real_escape_string($_POST[$genie_text_field]))."' and item_type = 'genie'";
					  $result = mydb::cxn()->query($genie_query);
					  if(mydb::cxn()->affected_rows < 1) {
						  // The genie does not exist in the database.  Create it.
						  $new_genie = new genie;
						  $new_genie->set('serial_num',$_POST[$genie_text_field]);
						  $new_genie->save();
						  echo "NEW GENIE CREATED (".$new_genie->get('serial_num').")";
					  }
					  
					  $rope_query =  "SELECT items.serial_num as rope
									  FROM items
									  WHERE LOWER(items.serial_num) = '".strtolower(mydb::cxn()->real_escape_string($_POST[$rope_text_field]))."' and item_type = 'rope'";
					  $result = mydb::cxn()->query($rope_query);
					  if(mydb::cxn()->affected_rows < 1) {
						  // The rope does not exist in the database.  Create it.
						  $new_rope = new rope;
						  $new_rope->set('serial_num',$_POST[$rope_text_field]);
						  $new_rope->save();
					  }
					  
					  // Ensure that each hrap has matching _text and _id fields.
					  // For example: name_stick1_left_text and name_stick1_left_id should describe the same HRAP.  However, if a user types an HRAP name
					  // in the name_stick1_left_text field but that HRAP does not exist, the name_stick1_left_id field will never be populated since the ID
					  // field is only populated when the user clicks on an entry in the context menu.
					  //
					  // This section will check each _text/_id field pair for agreement.  If a discrepancy is found, return to the form and allow user
					  // to manually correct the issue.
	  
					  $hrap_query = "SELECT CONCAT(hraps.firstname,' ',hraps.lastname) as name
									 FROM hraps
									 WHERE hraps.id = '".$_POST[$name_id_field]."'";

					  $hrap_db = NULL;
					  
					  $result = mydb::cxn()->query($hrap_query);
					  if(mydb::cxn()->affected_rows > 0) {
						  $row = $result->fetch_assoc();
						  $hrap_db = $row['name'];
					  }
	  
					  if(!isset($_POST[$name_text_field])) $_POST[$name_text_field] = NULL;
					  if(strtolower($_POST[$name_text_field]) != strtolower($hrap_db)) {
						  if(strlen($id_mismatch_fields) > 1) $id_mismatch_fields .= "<br>\n";
						  $id_mismatch_fields .= "Stick ".$stick.":".$door." Name";
					  }
				  } // End: if(!$partial && !null_so_far)
				} // End: if(array_key_exists($name_id_field,$_POST))
				
				// Check for duplicate entries already in the databse. Build an array of rappels
				// in the db that match the rappels for each stick/door POST'ed.
				if(isset($_POST[$name_id_field]) && $_POST[$name_id_field] != '') {
				  $query = "SELECT rappels.operation_id "
						  ."FROM rappels INNER JOIN operations on rappels.operation_id = operations.id "
						  ."WHERE "
						  ."DATE_FORMAT(operations.date,'%c/%d/%Y') = '".$_POST['date']."'"
						  ." && rappels.hrap_id = '".$_POST[$name_id_field]."'";
						  
				  if(isset($_POST[$rope_id_field]) && $_POST[$rope_id_field] != '') $query .= " && rappels.rope_id = ".$_POST[$rope_id_field];
				  if(isset($_POST[$rope_end_field]) && $_POST[$rope_end_field] != '') $query .= " && rappels.rope_end = '".$_POST[$rope_end_field]."'";
				  if(isset($_POST[$genie_id_field]) && $_POST[$genie_id_field] != '') $query .= " && rappels.genie_id = ".$_POST[$genie_id_field];
				  if(isset($_GET['op']) && $_GET['op'] != '') $query .= " && (rappels.operation_id != ".$_GET['op'].")";
				  
				  $result = mydb::cxn()->query($query);
				  while($row = $result->fetch_assoc()) {
					  array_push($duplicate_operations,$row['operation_id']);
				  }
					
				} // End: if(isset($_POST[$name_id_field]) && $_POST[$name_id_field] != '')
			} // End: foreach($doors AS $door)
		} // End: foreach($sticks AS $stick)

		// Check for spotter id mismatch (like above)
		// Allow blank Spotter field (because not all spotters have profiles in the RapRec program).
		if( isset($_POST['name_spotter_id']) && $_POST['name_spotter_id'] != '') {
		  $spotter_query = "	SELECT CONCAT(hraps.firstname,' ',hraps.lastname) as name
									  FROM hraps
									  WHERE hraps.id = '".$_POST['name_spotter_id']."'";
		  $spotter_db = '';
		  $result = mydb::cxn()->query($spotter_query);
					  if(mydb::cxn()->affected_rows > 0) {
						  $row = $result->fetch_assoc();
						  $spotter_db = $row['name'];
					  }
		  if(strtolower($_POST['name_spotter_text']) != strtolower($spotter_db)) {
						  if(strlen($id_mismatch_fields) > 1) $id_mismatch_fields .= "<br>\n";
						  $id_mismatch_fields .= "Spotter Name";
					  }
		} // END if( !isset($_POST['name_spotter_id'])...
		
		// Throw the appropriate exceptions, if necessary
		if(strlen($incomplete_sections) > 1) {
			throw new Exception('Partial entries are not allowed.<br>Please complete or clear the following sections:<br><br>'.$incomplete_sections);
			return 0;
		}
		if(strlen($id_mismatch_fields) > 1) {
			throw new Exception('You must make your selection by choosing an item off the popup menu.<br>The following fields were not properly selected:<br><br>'.$id_mismatch_fields);
			return 0;
		}
		
		// Check for a duplicate entry already in the database
		// Oprations with the same date, rappellers, ropes and genies will be considered duplicates
		if(count($duplicate_operations) > 0) {
			$duplicate_operations = array_unique($duplicate_operations);
			$message = "You may be entering a duplicate record. Review the following rappels before saving this entry:<br />\n";
			foreach($duplicate_operations as $d) {
				$message .= "<a href=\"view_rappels.php?op=".$d."\">Operation #".$d."</a><br />\n";
			}
			$message .= "</ul>\n";
			
			throw new Exception($message);
			return 0;
		}
		
	} // End: check_add_rappel_form_data()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: add_operation_to_db() ***********************************************************/
/*******************************************************************************************************************************/
	function add_operation_to_db() {
		global $which_form;
		
		try {
			$success_message = "";
			
			$new_op = new operation;
			$new_op->set('date', $_POST['date']);
			$new_op->set('type', $_POST['operation_type']);
			if($_POST['operation_type'] == 'operational') $new_op->set('incident_number', $_POST['inc_1']."-".$_POST['inc_2']."-".$_POST['inc_3']);
			$new_op->set('height', $_POST['height']);
			$new_op->set('canopy_opening', $_POST['canopy_opening']);
			//$new_op->set('weather', $_POST['weather']);
			$new_op->set('location', $_POST['location']);
			//$new_op->set('comments', $_POST['comments']);
			$new_op->set('pilot_name', $_POST['pilot_name']);
			$new_op->set('aircraft_type', $_POST['aircraft_type']);
			$new_op->set('aircraft_configuration', $_POST['configuration']);
			$new_op->set('aircraft_tailnumber', $_POST['tailnumber']);
			$new_op->set('spotter', $_POST['name_spotter_id']);
			
			$new_op->save(); // Enter the info we have so far. This will also set the operation_id from the autoincrement database ID, which we need to bind each rappel below
			
			// Create a RAPPEL OBJECT for each rappel that was $_POST'ed.
			// We can use the NAME field ($_POST['name_stick1_left_text']) to tell if a rappel is populated or not 
			// because we have already checked for partially-completed rappel boxes. So if the NAME field is filled out, we
			// know that the rest of the required information for that rappel is also present.
			$stick_options= array(1,2,3);
			$door_options = array('left','right');
			foreach($stick_options AS $stick) {
				foreach($door_options AS $door) {
					$position = 'stick'.$stick.'_'.$door;
					$stick_name = 'name_'.$position.'_text';
					if(isset($_POST[$stick_name]) && ($_POST[$stick_name] != "")) {
						$new_rap = new rappel;
						$new_rap->set('operation_id',$new_op->get('id')); // Associate this rappel with the operation
						$new_rap->set('hrap_id', $_POST['name_'.$position.'_id']);
						$new_rap->set('genie_by_serial_num', $_POST['genie_'.$position.'_text']);	//Associate genie by serial_num (not ID)
						$new_rap->set('rope_by_serial_num', $_POST['rope_'.$position.'_text']);		//Associate rope by serial_num (not ID)
						$new_rap->set('rope_end',$_POST['rope_'.$position.'_end']);
						$new_rap->set('knot', (isset($_POST['knot_'.$position]) ? $_POST['knot_'.$position] : 0));
						$new_rap->set('eto',  (isset($_POST['eto_'.$position]) ? $_POST['eto_'.$position] : 0));
						$new_rap->set('comments', $_POST['comments_'.$position]);
						$new_rap->set('stick', $stick);
						$new_rap->set('door', $door);
						
						$new_rap->save();  // Save this rappel to the database
						
					} // End: if()
				} // End: foreach($door_options)
			} // End: foreach($stick_options)
			
			// Add each $_POST'ed Letdown Line ID to the $new_op->letdowns array
			for($ldid=1;$ldid<=6;$ldid++) {
				if($_POST['letdown_'.$ldid.'_text'] != "") $new_op->add_letdown($_POST['letdown_'.$ldid.'_text']);	//Associate letdown lines by serial_num (not ID)
			}
			
			$new_op->save(); // Commit this entire OPERATION to the database
			
			$success_message = "All rappel information was successfully added to the database!";
			return $success_message;
			
		} catch (Exception $e) {
			//throw new Exception($e->getMessage()); // Re-Throw any exceptions to the calling function
			if($new_op->get('id') != NULL) $new_op_id = $new_op->get('id');
			else $new_op_id = false;
			delete_partial_entry($new_op_id);		// Cancel the transaction - delete partial entries
			
			throw $e;//new Exception ($e->getMessage());
			return 0;
		}
	} // End: add_operation_to_db()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: delete_partial_entry() **********************************************************/
/*******************************************************************************************************************************/
	function delete_partial_entry($new_op_id) {
		// This function will delete the specified operation, all rappels in $rap_array, and all letdown_events as specified
		// in the event of an incomplete entry.  For instance, if a new operation is added to the database and 3 out of 4 of
		// the rappels are added to the operation, but then the 4th rappel fails, this function will remove the successful
		// entries.
		
		if($new_op_id) {
			$query = "DELETE FROM operations WHERE id = ".$new_op_id;
			$result = mydb::cxn()->query($query);
			
			$query = "DELETE FROM rappels WHERE operation_id = ".$new_op_id;
			$result = mydb::cxn()->query($query);
			
			$query = "DELETE FROM letdown_events WHERE operation_id = ".$new_op_id;
			$result = mydb::cxn()->query($query);
		}
	} // End: function delete_partial_entry()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_edit_op_info_form() ********************************************************/
/*******************************************************************************************************************************/
	function load_op_into_form_memory($operation_id) {
		global $which_form;
		
		$op = new operation;
		$op->load($operation_id);
		$formFieldsToDisable = array();	// This is a comma-separated list of form-field ID's that need to be disabled (prevent editing) because the HRAP is on a different crew than current user
		$date_array = explode('/',$op->get('date'));
		$year = $date_array[2];
		
		if($op->get('incident_number') != "") $inc = explode('-',$op->get('incident_number'));
		else $inc = array(NULL,NULL,NULL);

		$_SESSION['form_memory'][$which_form]['date']			= $op->get('date');
		$_SESSION['form_memory'][$which_form]['operation_type']	= $op->get('type');
		$_SESSION['form_memory'][$which_form]['inc_1']			= $inc[0];
		$_SESSION['form_memory'][$which_form]['inc_2']			= $inc[1];
		$_SESSION['form_memory'][$which_form]['inc_3']			= $inc[2];
		$_SESSION['form_memory'][$which_form]['location']		= $op->get('location');
		$_SESSION['form_memory'][$which_form]['height']			= $op->get('height');
		$_SESSION['form_memory'][$which_form]['canopy_opening']	= $op->get('canopy_opening');
		$_SESSION['form_memory'][$which_form]['pilot_name']		= $op->get('pilot_name');
		$_SESSION['form_memory'][$which_form]['aircraft_type']	= $op->get('aircraft_type');
		$_SESSION['form_memory'][$which_form]['configuration']	= $op->get('aircraft_configuration');
		$_SESSION['form_memory'][$which_form]['tailnumber']		= $op->get('aircraft_tailnumber');
									
		$_SESSION['form_memory'][$which_form]['name_spotter_text']	= $op->spotter->get('firstname')." ".$op->spotter->get('lastname');
		$_SESSION['form_memory'][$which_form]['name_spotter_id']		= $op->spotter->get('id');
		$_SESSION['form_memory'][$which_form]['spotter_headshot_filename']	= $op->spotter->get('headshot_filename');
		
		$sticks = array(1,2,3);
		$doors = array('left','right');
		foreach($sticks as $stick) {
			foreach($doors as $door) {
				try {
					$position = 'stick'.$stick.'_'.$door;
					$rap = $op->get_rappel($stick,$door);
					$_SESSION['form_memory'][$which_form]['name_'.$position.'_text'] = $rap->get('hrap')->get('firstname')." ".$rap->get('hrap')->get('lastname');
					$_SESSION['form_memory'][$which_form]['name_'.$position.'_id']	= $rap->get('hrap')->get('id');
					$_SESSION['form_memory'][$which_form]['genie_'.$position.'_text']= $rap->get('genie')->get('serial_num');
					$_SESSION['form_memory'][$which_form]['genie_'.$position.'_id']	= $rap->get('genie')->get('id');
					$_SESSION['form_memory'][$which_form]['rope_'.$position.'_text']	= $rap->get('rope')->get('serial_num');
					$_SESSION['form_memory'][$which_form]['rope_'.$position.'_id']	= $rap->get('rope')->get('id');
					$_SESSION['form_memory'][$which_form]['rope_'.$position.'_end']	= $rap->get('rope_end');
					$_SESSION['form_memory'][$which_form]['knot_'.$position]	= $rap->get('knot');
					$_SESSION['form_memory'][$which_form]['eto_'.$position]	= $rap->get('eto');
					$_SESSION['form_memory'][$which_form][$position.'_headshot_filename']	= $rap->get('hrap')->get('headshot_filename');
					$_SESSION['form_memory'][$which_form]['comments_'.$position]	= $rap->get('comments');
					
					if($_SESSION['current_user']->account_type == 'crew_admin') {
						$hrap = new hrap;
						$hrap->load($_SESSION['form_memory'][$which_form]['name_'.$position.'_id']);
						if($hrap->get_crew_by_year($year) != $_SESSION['current_user']->get('crew_affiliation_id')) {
							$formFieldsToDisable[] = $position;	//Make this section of the form un-editable to crew-admins of OTHER crews
						}
					}
				} catch (Exception $e) {}
			}
		}

		// Load letdown events
		$i = 1;
		foreach($op->get('letdowns') as $letdown) {
			$_SESSION['form_memory'][$which_form]['letdown_'.$i.'_text'] = $letdown->get('serial_num');
			$_SESSION['form_memory'][$which_form]['letdown_'.$i.'_id'] = $letdown->get('id');
			$i++;
		}
		
		echo "<form name=\"formFieldsToDisable\"><input type=\"hidden\" name=\"listOfFields\" id=\"listOfFields\" value=\"".implode(',',$formFieldsToDisable)."\"></form>\n";
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: update_operation_in_db() ********************************************************/
/*******************************************************************************************************************************/
	function update_operation_in_db() {
		try {
			$success_message = "";
			$date_array = explode('/',$_POST['date']);
			$year = $date_array[2];
			
			$op = new operation;
			$op->load($_GET['op']);
			
			$op->set('date', $_POST['date']);
			$op->set('type', $_POST['operation_type']);
			if($_POST['operation_type'] == 'operational') $op->set('incident_number', $_POST['inc_1']."-".$_POST['inc_2']."-".$_POST['inc_3']);
			$op->set('height', $_POST['height']);
			$op->set('canopy_opening', $_POST['canopy_opening']);
			//$op->set('weather', $_POST['weather']);
			$op->set('location', $_POST['location']);
			//$op->set('comments', $_POST['comments']);
			$op->set('pilot_name', $_POST['pilot_name']);
			$op->set('aircraft_type', $_POST['aircraft_type']);
			$op->set('aircraft_configuration', $_POST['configuration']);
			$op->set('aircraft_tailnumber', $_POST['tailnumber']);
			$op->set('spotter', $_POST['name_spotter_id']);
			
			$op->save(); // Enter the info we have so far. This will also set the operation_id from the autoincrement database ID, which we need to bind each rappel below
			
			// Create a RAPPEL OBJECT for each rappel that was $_POST'ed.
			// We can use the NAME field ($_POST['name_stick1_left_text']) to tell if a rappel is populated or not 
			// because we have already checked for partially-completed rappel boxes. So if the NAME field is filled out, we
			// know that the rest of the required information for that rappel is also present.
			$stick_options= array(1,2,3);
			$door_options = array('left','right');
			foreach($stick_options AS $stick) {
				foreach($door_options AS $door) {
					$position = 'stick'.$stick.'_'.$door;
					$stick_name = 'name_'.$position.'_text';
					if(isset($_POST[$stick_name]) && ($_POST[$stick_name] != "")) {
						$hrap = new hrap;
						$hrap->load($_POST['name_'.$position.'_id']);
						if( (($_SESSION['current_user']->get('account_type') == 'crew_admin') && ($hrap->get_crew_by_year($year) == $_SESSION['current_user']->get('crew_affiliation_id'))) 
							|| ($_SESSION['current_user']->get('account_type') == 'admin')) {
						
							$new_rap = new rappel;
							try {
								$new_rap->load($op->get_rappel($stick,$door)->get('id'));
							} catch (Exception $e) {/* If the requested rappel does not already exist, the rappel object will be CREATED instead of MODIFIED */}
							
							$new_rap->set('operation_id',$op->get('id')); // Associate this rappel with the operation
							$new_rap->set('hrap_id', $_POST['name_'.$position.'_id']);
							$new_rap->set('genie', $_POST['genie_'.$position.'_id']);
							$new_rap->set('rope', $_POST['rope_'.$position.'_id']);
							$new_rap->set('rope_end',$_POST['rope_'.$position.'_end']);
							if(isset($_POST['knot_'.$position])) $new_rap->set('knot', $_POST['knot_'.$position]);
							if(isset($_POST['eto_'.$position])) $new_rap->set('eto', $_POST['eto_'.$position]);
							$new_rap->set('comments', $_POST['comments_'.$position]);
							$new_rap->set('stick', $stick);
							$new_rap->set('door', $door);
							
							$new_rap->save();  // Save this rappel to the database
						}
						
					} // End: if($_POST[$stick_name] != "")
				} // End: foreach($door_options)
			} // End: foreach($stick_options)
			
			// Add each $_POST'ed Letdown Line ID to the $new_op->letdowns array
			for($ldid=1;$ldid<=6;$ldid++) {
				if($_POST['letdown_'.$ldid.'_id'] != "") $op->add_letdown($_POST['letdown_'.$ldid.'_id']);
			}
			
			$op->save(); // Commit this entire OPERATION to the database
			
			$success_message = "All rappel information was successfully added to the database!";
			return $success_message;
			
		} catch (Exception $e) {
			//throw new Exception($e->getMessage()); // Re-Throw any exceptions to the calling function
			if($op->get('id') != NULL) $op_id = $op->get('id');
			else $op = false;
			delete_partial_entry($op_id);		// Cancel the transaction - delete partial entries
			
			throw new Exception ($e->getMessage());
			return 0;
		}
	}
?>
