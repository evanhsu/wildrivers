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
	
	require("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	require_once("includes/aircraft_layouts.php");
	
	// Make sure this user is allowed to access this page
	if($_SESSION['logged_in'] == 1) {
		// ACCESS GRANTED!
		global $crew;
		$crew = new crew;
		
		global $allow_edit;
		$allow_edit = 0;
	
		global $op;			// If a specific operation is requested, this will hold all the details
		
		// Check this user's permissions to determine whether to show an 'Edit' link by each rappel record
		if(isset($_SESSION['current_view']['crew'])) {
			if(check_access('crew_admin',$_SESSION['current_view']['crew']->get('id'))) $allow_edit = 1;
		}
		elseif(isset($_GET['crew']) && check_crew($_GET['crew'])) {
			$allow_edit = 1;
		}
	}
	else {
		// ACCESS DENIED!
		// Users who are NOT LOGGED IN are not allowed to view the details of individual rappels. They can only view cumulative data and statistics (on the index.php page)
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
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, record, history" />
<meta name="Description" content="The National Rappel Record Website - This page is used to view rappel records." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if($_SESSION['mobile'] == 1) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script type="text/javascript" src="scripts/searchautosuggest/lib/ajax_framework.js"></script>

<script type="text/javascript">
	AC_FL_RunContent = 0;
	DetectFlashVer = 0;
</script>
<script type="text/javascript" src="includes/charts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="includes/charts/hrap_stat_charts.js"></script>

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

//Determine whether to show rappels for the whole REGION, the current CREW, the selected HRAP, or a specific OPERATION
$zoom_level = false;

try {
	$op = new operation;
	isset($_GET['op']) ? $op->load($_GET['op']) : $op->load(false);
} catch(Exception $e) {/* No OPERATION was specified - check for REGION or CREW_ID below */}

if($op->get('id') !== NULL) $zoom_level = 'operation';
elseif(isset($_SESSION['current_view']['region']) && $_SESSION['current_view']['region'] !== NULL) {
	if(isset($_SESSION['current_view']['crew']) && $_SESSION['current_view']['crew'] !== NULL) {
		if(($_SESSION['current_view']['hrap'] !== NULL) && ($_SESSION['current_view']['hrap']->get('id') !== NULL)) $zoom_level = 'hrap';
		else $zoom_level = 'crew';
	}
	else $zoom_level = 'region';
}

switch($zoom_level) {
/*------------------------------------------------------------*/
case 'region':
	$query = "SELECT * FROM view_rappels WHERE year = '".$_SESSION['current_view']['year']."' AND region = ".$_SESSION['current_view']['region']." ORDER BY date DESC,operation_id DESC";
	$result = mydb::cxn()->query($query);
	
	$text = "<br>\n"
			."<div style=\"width:100%;text-align:left;\">\n"
			."<h1>Rappel History</h1>\n"
			."<br>\n"
			."<h2>Region ".$_SESSION['current_view']['region']." -- ".$_SESSION['current_view']['year']."</h2>\n"
			."</div>\n\n";
			
	$text .= "<br>\n"
		  ."<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
		  ."<th>+</th>"
		  ."<th>Date</th>"
		  ."<th>Type</th>"
		  ."<th>Crew</th>"
		  ."<th>HRAP</th>"
		  ."<th>Aircraft</th>"
		  ."<th>Comments</th></tr>\n";
	
	$current_row = 0;
	$last_operation_id = -1;
	$class = "";
	while($row = $result->fetch_assoc()) {
		$current_row++;
/*
		if($current_row % 2 == 0) $class = "class=\"evn\"";
		else $class = "class=\"odd\"";
*/
		// Group rappels from the same operation by color
		if($row['operation_id'] != $last_operation_id) {
			if($class == "class=\"evn\"") $class = "class=\"odd\"";
			else $class = "class=\"evn\"";
			
			$text .= "<tr ".$class.">\n"
					."<td style=\"text-align:center;height:1.8em;\">"
					."<a href=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&op=".$row['operation_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\" title=\"View Rappel\"></a></td>\n"
					."<td>".$row['date']."</td>\n"
					."<td>".ucwords(str_replace("_"," ",$row['operation_type']))."</td>\n";
		}
		else {
			$text .= "<tr ".$class.">\n"
				."<td colspan=\"3\" style=\"height:1.8em;\">&nbsp;</td>\n";
			
		}
		$text .= "<td><a href=\"view_rappels.php?crew=".$row['crew_id']."\">".$row['crew_name']."</a></td>"
				."<td><a href=\"view_rappels.php?hrap=".$row['hrap_id']."\">".$row['hrap_name']."</a></td>"
				."<td style=\"text-transform:uppercase\">".$row['aircraft_tailnumber']."</td>"
				."<td>".$row['comments']."</td>\n"
				."</tr>\n\n";
				
		$last_operation_id = $row['operation_id'];
	}
	$text .= "</table><br>\n\n";
	break;

/*------------------------------------------------------------*/
case 'crew':

	$query = "SELECT * FROM view_rappels WHERE year = '".$_SESSION['current_view']['year']."' AND crew_id = ".$_SESSION['current_view']['crew']->get('id')." ORDER by date DESC, operation_id";
	$result = mydb::cxn()->query($query);

	$year_str = "";
	if($year_array = $_SESSION['current_view']['crew']->get_rap_history_years()) {
		foreach($year_array as $year) {
			$year_str .= "<a href=\"".$_SERVER['PHP_SELF']."?crew=".$_GET['crew']."&year=".$year."\">".$year."</a> | ";
		}
		$year_str = substr($year_str,0,strlen($year_str)-3); // Strip the last pipe divider off the string
	}
	else $year_str = "This crew currently has no Rappel History";
	
	$text = "<br>\n"
			."<div style=\"width:100%;text-align:left;\">\n"
			."<h1>Rappel History</h1>\n"
			."<br>\n"
			."<h2>".$_SESSION['current_view']['crew']->get('name')." -- ".$_SESSION['current_view']['year']."</h2>\n"
			."</div>\n\n";
			
	$text .= "<div style=\"width:80%; padding:2px; text-align:left; background-color:#eeeeee; border:2px solid #cccccc;\">This Crew has rappel info for the following years:<br>\n".$year_str."</div>\n\n";
	
	$text .= "<br>\n"
		."<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
		."<th>+</th>"
		."<th>Date</th>"
		."<th>Type</th>"
		."<th>Location</th>"
		."<th>Aircraft</th>"
		."<th>Spotter</th>"
		."<th>HRAP</th>"
		."<th>Comments</th></tr>\n";
	
	$current_row = 0;
	$last_operation_id = -1;
	$class = "";
	while($row = $result->fetch_assoc()) {
		$current_row++;
/*
		if($current_row % 2 == 0) $class = "class=\"evn\"";
		else $class = "class=\"odd\"";
*/
		// Group rappels from the same operation by color
		if($row['operation_id'] != $last_operation_id) {
			if($class == "class=\"evn\"") $class = "class=\"odd\"";
			else $class = "class=\"evn\"";
			
			$text .= "<tr ".$class.">\n"
					."<td style=\"text-align:center;height:1.8em;\">"
					."<a href=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&op=".$row['operation_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\" title=\"View Rappel\"></a></td>\n"
					."<td>".$row['date']."</td>\n"
					."<td>".ucwords(str_replace("_"," ",$row['operation_type']))."</td>\n"
					."<td>".$row['location']."</td>\n"
					."<td style=\"text-transform:uppercase;\">".$row['aircraft_tailnumber']."</td>\n"
					."<td>".$row['spotter_name']."</td>\n";
		}
		else {
			$text .= "<tr ".$class.">\n"
				."<td colspan=\"6\" style=\"height:1.8em;\">&nbsp;</td>\n";
			
		}
		$text .= "<td><a href=\"view_rappels.php?hrap=".$row['hrap_id']."\">".$row['hrap_name']."</a></td>"
				."<td>".$row['comments']."</td>\n"
				."</tr>\n\n";
				
		$last_operation_id = $row['operation_id'];
	}
	$text .= "</table><br>\n\n";
	break;

/*------------------------------------------------------------*/
case 'hrap':
	$query = "SELECT `rope_end`, `knot`, `eto`, `comments`, `confirmed_by`, `hrap_id`, `hrap_name`, view_rappels.`gender`, view_rappels.`headshot_filename`, `rope_id`, `rope_num`, `genie_id`, `genie_num`, `crew_id`, `crew_name`, `region`, `crew_logo`, `aircraft_type_id`, `aircraft_fullname`, `aircraft_type`, `year`, `operation_id`, `incident_number`, `aircraft_tailnumber`, `spotter_id`, `pilot_name`, `height`, `location`, `canopy_opening`, `date`, `operation_type`, `rappel_id`, `door`, `stick`, CONCAT(hraps.firstname,' ',hraps.lastname) as spotter_name FROM `view_rappels` left outer join hraps on hraps.id = view_rappels.spotter_id WHERE view_rappels.year = '".$_SESSION['current_view']['year']."' AND view_rappels.hrap_id = ".$_SESSION['current_view']['hrap']->get('id')." ORDER BY date DESC";
	$result = mydb::cxn()->query($query);

	$text = "<br>\n"
			."<div style=\"width:100%;text-align:left;\">\n"
			."<h1>".$_SESSION['current_view']['hrap']->get('name')." -- ".$_SESSION['current_view']['year']."</h1>\n"
			."</div>\n\n";
			
	$text .= generate_hrap_stats();
	
	$text .= "<br>\n"
			."<div style=\"width:100%;text-align:left;\">\n"
			."<h2>Career Totals</h2><br />\n"
			."Rappels/Operationals: ".$_SESSION['current_view']['hrap']->get('raps_all_time_live')."/".$_SESSION['current_view']['hrap']->get('raps_all_time_operational')."<br />\n"
			."Spots/Operationals: ".$_SESSION['current_view']['hrap']->get('spots_all_time_live')."/".$_SESSION['current_view']['hrap']->get('spots_all_time_operational')."<br /><br />\n";
			
	$text .= "<h2>Rappel History (".$_SESSION['current_view']['year'].")</h2><br />\n"
			."Operationals (".$_SESSION['current_view']['year']."): ".$_SESSION['current_view']['hrap']->get('raps_this_year_operational')."<br />\n"
			."Proficiencies (".$_SESSION['current_view']['year']."): ".$_SESSION['current_view']['hrap']->get('raps_this_year_proficiency')."<br />\n"
			."Total: (".$_SESSION['current_view']['year']."): ".$_SESSION['current_view']['hrap']->get('raps_this_year_live')."<br />\n"
			."</div>\n\n";
			
	$text .= "<br>\n"
		."<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
		."<th>+</th>"
		."<th>Date</th>"
		."<th>Type</th>"
		."<th>Location</th>"
		."<th>Pilot</th>"
		."<th>Aircraft</th>"
		."<th>Spotter</th>"
		."<th>Comments</th></tr>\n";
	
	$current_row = 0;
	while($row = $result->fetch_assoc()) {
		$current_row++;
		
		if($current_row % 2 == 0) $class = "class=\"evn\"";
		else $class = "class=\"odd\"";

		$text .= "<tr ".$class.">\n"
				."<td style=\"text-align:center;\">"
					."<a href=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&op=".$row['operation_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\"></a></td>"
				."<td>".$row['date']."</td>"
				."<td>".ucwords(str_replace("_"," ",$row['operation_type']))."</td>"
				."<td>".$row['location']."</td>"
				."<td>".$row['pilot_name']."</td>"
				."<td>".$row['aircraft_tailnumber']." (".$row['aircraft_fullname'].")</td>"
				."<td>".$row['spotter_name']."</td>"
				."<td>".$row['comments']."</td>\n"
				."</tr>\n\n";
		
	}
	$text .= "</table><br>\n\n";
	
	//If this HRAP is a spotter, display a history of the operations they have spotted
	if($_SESSION['current_view']['hrap']->get('spotter') > 0) {
		$spots = $_SESSION['current_view']['hrap']->get_rap_history('spotter',$_SESSION['current_view']['year']);
				
		$spotter_list = "<br>\n"
			."<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
			."<th>+</th>"
			."<th>Date</th>"
			."<th>Type</th>"
			."<th>Aircraft</th></tr>\n";
		
		foreach($spots as $row) {
			$current_row++;
	
			if($current_row % 2 == 0) $class = "class=\"evn\"";
			else $class = "class=\"odd\"";
	
			$spotter_list .= "<tr ".$class.">\n"
					."<td style=\"text-align:center;\">"
						."<a href=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&op=".$row['operation_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\"></a></td>"
					."<td>".$row['date']."</td>"
					."<td style=\"text-transform:capitalize\">".str_replace('_',' ',$row['operation_type'])."</td>"
					."<td>".$row['aircraft_fullname']."</td>"
					."</tr>\n\n";
			
		}
		$text .= "<br>\n"
				."<div style=\"width:100%;text-align:left;\">\n"
				."<h2>Spotter History (".$_SESSION['current_view']['year'].")</h2><br />\n"
				."Operationals (".$_SESSION['current_view']['year']."): ".$_SESSION['current_view']['hrap']->get('spots_this_year_operational')."<br />\n"
				."Proficiencies (".$_SESSION['current_view']['year']."): ".$_SESSION['current_view']['hrap']->get('spots_this_year_proficiency')."<br />\n"
				."Total (".$_SESSION['current_view']['year']."): ".$_SESSION['current_view']['hrap']->get('spots_this_year_live')."<br />\n"
				."</div>\n\n"
				.$spotter_list
				."</table><br>\n\n";
	}
	
	break;

/*------------------------------------------------------------*/
case 'operation':
	// Determine EDIT privileges: (A hyperlink will be displayed for each editable section if the user has privileges)
	//   Edits can only be performed by CREW_ADMIN and ADMIN users
	//   A CREW_ADMIN can edit info in the 'Operation Info' and 'Pilot & Aircraft' sections if one of their crewmembers rappelled in this operation.
	//   A CREW_ADMIN can edit RAPPEL info for their crewmembers ONLY
	//   An ADMIN can ALWAYS edit info in the 'Operation Info' and 'Pilot & Aircraft' sections
	//   An ADMIN can edit RAPPEL info for any of the rappels
	
	// If current user is a CREW_ADMIN, check that AT LEAST ONE of the rappellers in this operation is on the same crew as this user.
	$general_edit_link = "";
	if($_SESSION['current_user']->account_type == 'crew_admin') {
		foreach($op->rappels as $rap) {
			if($rap->crew->get('id') == $_SESSION['current_user']->get('crew_affiliation_id')) {
				$general_edit_link = "<a href=\"update_rappels.php?function=edit_operation_info&op=".$op->get('id')."\" style=\"font-weight:bold;\">Edit this Info</a>\n";
				break; // Exit the foreach loop, we've found what we were looking for
			}
		} // End: foreach()
	}
	elseif($_SESSION['current_user']->account_type == 'admin') $general_edit_link = "<a href=\"update_rappels.php?function=edit_operation_info&op=".$op->get('id')."\" style=\"font-weight:bold;\">Edit this Info</a>\n";
	
	$text = "<br><br>\n\n"
			.$general_edit_link."<br><br>\n";

	$text .= "<table id=\"form_table\" class=\"form_table\" style=\"margin:0 auto 0 auto; border:3px solid #bbbbbb;\">\n"
			."<tr><th colspan=\"3\" style=\"text-align:left\">Operation Info</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"text-align:center\">\n"
					."<table style=\"margin:0 auto 0 auto;\">\n"
						."<tr>	<td style=\"text-align:right;width:250px;\">Date</td>\n"
						."		<td style=\"width:10px;\">:</td>\n"
						."		<td style=\"text-align:left;width:250px;\">".$op->get('date')."</td></tr>\n"
						."<tr><td style=\"text-align:right;\">Type</td><td>:</td>\n"
							."<td style=\"text-align:left;text-transform:capitalize;\">\n";
							
							$text .= str_replace('_',' ',$op->get('type'))
								."</td></tr>\n\n";
							
							$text .= "<tr><td style=\"text-align:right;\">Incident #</td><td>:</td>"
								."	<td style=\"text-align:left;\">"
									.$op->get('incident_number')
								."	</td>\n"
								."</tr>\n\n";

					$text .= "<tr>	<td style=\"text-align:right;\">Height</td>\n"
						."		<td>:</td>\n"
						."		<td style=\"text-align:left;\">".$op->get('height')." ft.</td></tr>\n"
						."<tr>\n"
						."	<td style=\"text-align:right;\">Canopy Opening</td>\n"
						."	<td>:</td>\n"
						."	<td style=\"text-align:left;\">".$op->get('canopy_opening')." ft<sup>2</sup></td></tr>\n\n"
					."</table></td></tr>\n\n"
				."<tr><th colspan=\"3\" style=\"text-align:left;\">Pilot & Aircraft</th></tr>\n"
				."<tr><td colspan=\"3\" style=\"text-align:center\">\n";

				if($op->get('type') == "proficiency_tower") {
					$text .= "There is no pilot or aircraft information for a tower rappel.\n";
				}
				else {
					$text .= "<table style=\"margin:0 auto 0 auto;;\">\n"
							."<tr><td style=\"text-align:right;width:250px;\">Pilot</td>\n"
							."		<td style=\"width:10px;\">:</td>\n"
							."		<td style=\"text-align:left;width:250px;\">".$op->get('pilot_name')."</td></tr>\n"
							
							."<tr><td style=\"text-align:right;\">Aircraft Type</td><td>:</td><td style=\"text-align:left; text-transform:capitalize;\">\n"
								.str_replace('_',' ',$op->get('aircraft_type'))."</td></tr>\n";
								
					$text .= "<tr>	<td style=\"text-align:right;\">Tailnumber</td>\n"
						."		<td>:</td>\n"
						."		<td style=\"text-align:left;text-transform:uppercase;\"><a href=\"http://registry.faa.gov/aircraftinquiry/NNum_Results.aspx?NNumbertxt=".$op->get('aircraft_tailnumber')."\" target=\"_BLANK\" title=\"FAA Lookup\">".$op->get('aircraft_tailnumber')."</a></td></tr>\n"
						."</table>\n";
				}
			$text .= "</td></tr>\n\n"
			."<tr><th colspan=\"3\" style=\"text-align:left;\">Rappeller Configuration</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"text-align:center;\" id=\"rappeller_configuration\">\n\n";

			$config_to_load = $op->get('aircraft_type').'_'.$op->get('aircraft_configuration');
			if(function_exists($config_to_load)) $text .= $config_to_load();
			else {
				$text .= "<br><div class=\"error_msg\">There was a problem loading the aircraft layout.</div><br><br>\n\n";
			}

			$text .= "</td></tr>\n\n"
			."<tr><th colspan=\"3\" style=\"text-align:left;\" >Cargo Letdown</th></tr>\n"
			."<tr><td colspan=\"3\" style=\"border-bottom:2px solid #bbbbbb;\">\n";
			
			$i = 0;
			foreach($op->get('letdowns') as $letdown) {
				$i++;
				$text .= "Letdown #".$i.": ".strtoupper($letdown->get('serial_num'))."<br>\n";
			}
			if($i == 0) $text .= "There is no Cargo Letdown information for this Operation<br>";
			
			$text .= "</td></tr>\n"
				."</table>\n\n";
	
	
	break;
	
/*------------------------------------------------------------*/
default:
	// No REGION has been selected by the user
	$text = "\n\n<br><br>\n<div class=\"error_msg\">You must select a Region, a Crew, or an HRAP before viewing the rappel history.</div><br>\n"
			."<a href=\"index.php\">Return to the Region-selection menu</a>\n";
	break;
} // End: switch($zoom_level)


echo $text;


/* -------------------------------------------------<< END CONTENT >>--------------------------------------------------------------*/
?>  
    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>

<?php
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_header() *******************************************************************/
/*******************************************************************************************************************************/
/*
	function show_header() {
		global $crew;

		$year_str = "";
		$hrap = "";
		if(isset($_GET['hrap'])) $hrap = "&hrap=".$_GET['hrap'];
		if($year_array = $crew->get_roster_years()) {
			foreach($year_array as $year) {
				$year_str .= "<a href=\"".$_SERVER['PHP_SELF']."?crew=".$_GET['crew'].$hrap."&year=".$year."&function=".$_GET['function']."\">".$year."</a> | ";
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

*/

/*******************************************************************************************************************************/
/*********************************** FUNCTION: create_spotter() ****************************************************************/
/*******************************************************************************************************************************/
function create_spotter($position) {
	global $op;
	
	// Get spotter information
try {
	$spotter = $op->spotter;
}
catch (Exception $e) {echo $e;}
	// Input 'position' must be one of the following: 'left', 'center', 'right'
	// This value describes which side of the aircraft schematic the spotter's info will appear on
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
	$spotter_link_open = "<a href=\"".$_SERVER['PHP_SELF']."?hrap=".$spotter->get('id')."\">";
	$text= "<table style=\"margin:".$margin."; border:2px dashed #555555;\">\n"
			."<tr><td colspan=\"2\" style=\"text-align:".$align.";\"><h3>Spotter</h3></td></tr>\n"
			."<tr><td style=\"text-align:".$align.";\">\n"
			.$spotter_link_open."<img src=\"".$spotter->get('headshot_filename')."\" style=\"border:2px solid #555555; width:75px; height:75px;\"></a></td></tr>\n"
			."<tr><td colspan=\"2\" style=\"color:#557799;text-align:".$align.";\">Name:<div style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$spotter_link_open.$spotter->get('name')."</a></div>\n"
			."</td></tr>\n"
		."</table>\n";
	return $text;
}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: create_left_rap() ***************************************************************/
/*******************************************************************************************************************************/
function create_left_rap($stick) {
	global $op;
	
	$name = "--";
	$hrap_link_open = "";
	$hrap_link_close = "";
	$genie= "--";
	$rope = "--";
	$rope_end = "--";
	$knot = "--";
	$eto = "--";
	$comments = "";
	$confirmable = false;
	$confirmed_by= 'blank';
	$headshot_filename = 'images/hrap_headshots/nobody.jpg';
	
	try {
		$rap = new rappel;
		$rap = $op->get_rappel($stick,'left');
		$confirmable = $rap->is_confirmable();
		$name = $rap->get('hrap')->get('name');
		$hrap_link_open = "<a href=\"".$_SERVER['PHP_SELF']."?hrap=".$rap->get('hrap')->id."\">";
		$hrap_link_close = "</a>";
		$genie= "<a href=\"view_equipment.php?crew=".$rap->get('genie')->get('crew_affiliation_id')."&eq_type=genie&eq_id=".$rap->get('genie')->get('id')."\">".strtoupper($rap->get('genie')->get('serial_num'))."</a>";
		$rope = "<a href=\"view_equipment.php?crew=".$rap->get('rope')->get('crew_affiliation_id')."&eq_type=rope&eq_id=".$rap->get('rope')->get('id')."\">".strtoupper($rap->get('rope')->get('serial_num'))."</a>";
		$rope_end = strtoupper($rap->get('rope_end'));
		$knot = $rap->get('knot') ? 'Yes' : 'No';
		$eto = $rap->get('eto') ? 'Yes' : 'No';
		$comments = $rap->get('comments');
		$headshot_filename = $rap->get('hrap')->get('headshot_filename');
		
		if($rap->get('confirmed_by') != false) {
			$confirmed_by = $rap->get('confirmed_by')->get('firstname') . " " . $rap->get('confirmed_by')->get('lastname');
		}
		else $confirmed_by = 'unconfirmed';
		
	} catch(Exception $e) {}
	
	//The input string 'stick' should be one of the following: '1', '2' or '3'
	$suffix = "stick".$stick."_left";
	
	if($confirmed_by == 'blank') $text = "<div class=\"unconfirmed\"></div>\n"; /* This rappel is blank, so no confirmation statement should appear */
	elseif($confirmed_by == 'unconfirmed') {
		if($confirmable) {
			$text = "<div class=\"unconfirmed\">This rappel is unconfirmed "
					."<a href=\"update_rappels.php?op=".$_GET['op']."&function=confirm_rappel&rap_id=".$rap->get('id')."\")\"><img src=\"images/check_mark.png\" title=\"Confirm\"></a> | "
					."<a href=\"update_rappels.php?op=".$_GET['op']."&function=delete_rappel&rap_id=".$rap->get('id')."\")\"><img src=\"images/trash.png\" title=\"Delete\"></a></div>\n";
		}
		else $text = "<div class=\"unconfirmed\">This rappel is unconfirmed</div>\n";
		
	}
	else $text = "<div class=\"confirmed\">Confirmed by <div class=\"name\">".$confirmed_by."</div></div>\n";
	$text .=	"<table style=\"margin:0 0 0 auto; border:2px dashed #555555; width:200px;\">\n"
					."<tr><td style=\"text-align:left; vertical-align:top; color:#557799; width:125px;\">\n"
								."Name:<br><div style=\"font-weight:normal; color:#444444;margin:1px 0px 5px 0px;\">".$hrap_link_open.$name.$hrap_link_close."</div>\n"
								."Genie:<br><div style=\"font-weight:normal; color:#444444;margin:1px 0px 5px 0px;\">".$genie."</div>\n"
								."Rope (End):<br><div style=\"font-weight:normal; color:#444444;margin:1px 0px 0px 0px;\">".$rope." (".$rope_end.")</div>\n"
								."</td>\n\n"
								
						."<td style=\"text-align:right;vertical-align:top;\">"
								."<h3>".num_suffix($stick)." Stick</h3><br>"
								.$hrap_link_open."<img src=\"".$headshot_filename."\" style=\"border:2px solid #555555; width:75px; height:75px;\">".$hrap_link_close."\n"
								."</td></tr>\n"
					."<tr><td colspan=\"2\" style=\"text-align:left;color:#557799;\">\n"
								."<div style=\"float:right; text-align:right; margin-left:5px;\">\n"
									."Knot: <span style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$knot."</span><br>\n"
									."ETO: <span style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$eto."</span>\n"
								."</div>\n"
								."Comments: <div style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$comments."</div>\n"
								."</td></tr>\n"
				."</table>\n";
	return $text;
}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: create_right_rap() **************************************************************/
/*******************************************************************************************************************************/
function create_right_rap($stick) {
	global $op;
	
	$name = "--";
	$hrap_link_open = "";
	$hrap_link_close = "";
	$genie= "--";
	$rope = "--";
	$rope_end = "--";
	$knot = "--";
	$eto = "--";
	$comments = "";
	$confirmable = false;
	$confirmed_by= 'blank';
	$headshot_filename = 'images/hrap_headshots/nobody.jpg';
	
	try {
		$rap = new rappel;
		$rap = $op->get_rappel($stick,'right');
		$confirmable = $rap->is_confirmable();
		$name = $rap->get('hrap')->get('name');
		$hrap_link_open = "<a href=\"".$_SERVER['PHP_SELF']."?hrap=".$rap->get('hrap')->id."\">";
		$hrap_link_close = "</a>";
		$genie= "<a href=\"view_equipment.php?crew=".$rap->get('genie')->get('crew_affiliation_id')."&eq_type=genie&eq_id=".$rap->get('genie')->get('id')."\">".strtoupper($rap->get('genie')->get('serial_num'))."</a>";
		$rope = "<a href=\"view_equipment.php?crew=".$rap->get('rope')->get('crew_affiliation_id')."&eq_type=rope&eq_id=".$rap->get('rope')->get('id')."\">".strtoupper($rap->get('rope')->get('serial_num'))."</a>";
		$rope_end = strtoupper($rap->get('rope_end'));
		$knot = $rap->get('knot') ? 'Yes' : 'No';
		$eto = $rap->get('eto') ? 'Yes' : 'No';
		$comments = $rap->get('comments');
		$headshot_filename = $rap->get('hrap')->get('headshot_filename');

		if($rap->get('confirmed_by') != false) {
			$confirmed_by = $rap->get('confirmed_by')->get('firstname') . " " . $rap->get('confirmed_by')->get('lastname');
		}
		else $confirmed_by = 'unconfirmed';
		
	} catch(Exception $e) {}
	
	//The input string 'stick' should be one of the following: '1', '2' or '3'
	$suffix = "stick".$stick."_right";

	if($confirmed_by == 'blank') $text = "<div class=\"unconfirmed\"></div>\n"; /* This rappel is blank, so no confirmation statement should appear */
	elseif($confirmed_by == 'unconfirmed') {
		if($confirmable) {
			$text = "<div class=\"unconfirmed\">This rappel is unconfirmed "
					."<a href=\"update_rappels.php?op=".$_GET['op']."&function=confirm_rappel&rap_id=".$rap->get('id')."\")\"><img src=\"images/check_mark.png\" title=\"Confirm\"></a> | "
					."<a href=\"update_rappels.php?op=".$_GET['op']."&function=delete_rappel&rap_id=".$rap->get('id')."\")\"><img src=\"images/trash.png\" title=\"Delete\"></a></div>\n";
		}
		else $text = "<div class=\"unconfirmed\">This rappel is unconfirmed</div>\n";
		
	}
	else $text = "<div class=\"confirmed\">Confirmed by <div class=\"name\">".$confirmed_by."</div></div>\n";
	
	$text .=	"<table style=\"margin:0 auto 0 0; border:2px dashed #555555; width:200px;\">\n"
					."<tr><td style=\"text-align:left;vertical-align:top;\">"
							."<h3>".num_suffix($stick)." Stick</h3><br>"
							.$hrap_link_open."<img src=\"".$headshot_filename."\" style=\"border:2px solid #555555; width:75px; height:75px;\">".$hrap_link_close."</td>\n"
								
						."<td style=\"text-align:left; vertical-align:top; color:#557799;width:125px;\">\n"
							."Name:<div style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$hrap_link_open.$name.$hrap_link_close."</div>\n"
							."Genie:<div style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$genie."</div>\n"
							."Rope (End):<div style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$rope." (".$rope_end.")</div>\n"
						."</td></tr>\n\n"
									
					."<tr><td colspan=\"2\" style=\"text-align:left; color:#557799;\">\n"
							."<div style=\"float:right; text-align:right; margin-left:5px;\">\n"
								."Knot: <span style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$knot."</span><br>\n"
								."ETO: <span style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$eto."</span>\n"
							."</div>\n"
							."Comments:\n"
							."<div style=\"font-weight:normal; color:#444444;margin:1px 0px 3px 0px;\">".$comments."</div></td></tr>\n"
				."</table>\n";
	return $text;
}	

/*******************************************************************************************************************************/
/*********************************** FUNCTION: generate_hrap_stats() ***********************************************************/
/*******************************************************************************************************************************/

function generate_hrap_stats() {
	// This function generates the section at the top of the individual HRAP Stats page.  This section shows 6 different charts with personal stats for this HRAP

	$text = "<table style=\"width:100%; border:2px solid #555555; vertical-align:top;\">\n";
	
	$text .= "<tr style=\"background-color:#cccccc; font-size:12px;\"><th>Rappel Totals (".$_SESSION['current_view']['year'].")</th><th>Favorite Seats (All-Time)</th><th>Buddies (All-Time)</th></tr>\n"
			."	<td style=\"width:33%;\"><script type=\"text/javascript\">hrap_numRappels_chart();</script></td>"
			."	<td><img src=\"scripts/fav_pos.php\"></td>"
			."	<td style=\"width:33%;\"><script type=\"text/javascript\">hrap_buddies_chart();</script></td>\n"
			."</tr>\n";
	
	$text .= "</table>\n";

	return $text;
}
?>
