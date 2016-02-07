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
	
	if(isset($_GET['logout']) && ($_GET['logout'] == 1)) {
		session_destroy();
		session_name('raprec');
		session_start();
	}
	
	if($_SESSION['logged_in'] != 1) {
		store_intended_location();  //Redirect user back to their intended location after they log in
		header('location: index.php');
	}
	
	
	if(isset($_POST['mobile'])) $_SESSION['mobile'] = $_POST['mobile'];
	elseif(isset($_GET['mobile'])) $_SESSION['mobile'] = $_GET['mobile'];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Proficiency Report :: RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="proficiency, proficient, fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, history" />
<meta name="Description" content="This page shows a collection of rappellers alongside their most recent rappel out of each different type of helicopter." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if(isset($_SESSION['mobile']) && ($_SESSION['mobile'] == 1)) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

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
	try {	
		if($_SESSION['current_view']['crew'] != NULL) {
			// REGION:	selected
			// CREW:	selected
			// ACTION:	Display crew proficieny overview
			show_proficiency_report('crew',$_SESSION['current_view']['crew']->get('id'));
		}
		elseif($_SESSION['current_view']['region'] != NULL) {
			// REGION:	selected
			// CREW:	not selected
			// ACTION:	Display regional proficiency overview
			show_proficiency_report('region',$_SESSION['current_view']['region']);
		}
		elseif(isset($_SESSION['current_user']) && ($_SESSION['current_user']->get('crew_affiliation_id') != "")) {
			// REGION:	not selected
			// CREW:	not selected
			// ACTION:	Attempt to determine the current user's crew affiliation, then Display crew proficieny overview
			show_proficiency_report('crew',$_SESSION['current_user']->get('crew_affiliation_id'));
		}
		else {
			// REGION:	not selected
			// CREW:	not selected
			// ACTION:	Direct to index.php
			echo "<br /><h2>You must select either a Crew of a Region before requesting a proficiency report</h2><br>\n"
				."<a href=\"index.php\">Return to the region-selection menu</a>\n";
		}
	} catch (Exception $e) {
		echo "<div class=\"error_msg\">An error occurred while generating the proficiency report.  Please select another Crew or Region.</div>\n";
	}
?>

    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>


<?php

function show_proficiency_report($view_type='region', $id=false) {
	// INPUTS:
	//	$view_type is either 'crew' or 'region', specifying the scope of the report
	//	$obj is either an integer region ID or a crew ID
	//
	// OUTPUT:
	//	This function prints the appropriate HTML page content to the screen.
	//	There is no return value.
	$rappel_platform = isset($_GET['rappel_platform']) ? $_GET['rappel_platform'] : 'bell_medium';
	if($rappel_platform == 'bell_medium') $aircraft_criteria = "(aircraft_types.type = '2' OR isNull(aircraft_types.type))";
	else $aircraft_criteria = "(aircraft_types.shortname = '".$rappel_platform."' OR isNull(aircraft_types.type))";
	
	switch($view_type) {
	case 'crew':
		$crew = new crew;
		$crew->load($id);
		$_SESSION['current_view']['crew'] = $crew;
		
		$roster_criteria = "rosters.crew_id = ".$id;
		$text = "<br /><div style=\"width:100%; text-align:left;\">\n"
				."<h1>Proficiency Status</h1><br />\n"
				."<h2>".$crew->get('name')." -- ".$_SESSION['current_view']['year']."</h2>\n"
				."</div>\n";
		break;
		
	case 'region':
	default:
		if(!$id) $id = 6; //This shouldn't happen, but default to region 6 if inputs were incomplete
		
		$roster_criteria = "rosters.crew_id IN (SELECT id FROM crews WHERE region = ".$id.")";
		
		$text = "<br /><div style=\"width:100%; text-align:left;\">\n"
				."<h1>Proficiency Status</h1><br />\n"
				."<h2>Region ".$id." -- ".$_SESSION['current_view']['year']."</h2>\n"
				."<hr style=\"width:100%\">"
				."</div>\n";
		break;
	} // End: switch($view_type)
		
	$text .= build_rappel_platform_dropdown();
	
	$query = "
SELECT 
hraps.id AS hrap_id, 
CONCAT( hraps.firstname,' ', hraps.lastname ) AS name, 
vr1.date, 
vr1.operation_id, 
vr1.aircraft_fullname, 
vr1.aircraft_type, 
aircraft_types.shortname AS aircraft_shortname, 
DATEDIFF( NOW( ) , STR_TO_DATE( vr1.date,  '%m/%d/%Y') ) AS days_ago

FROM 
hraps INNER JOIN rosters ON ((rosters.year = '".$_SESSION['current_view']['year']."') AND (".$roster_criteria.") AND (hraps.id = rosters.hrap_id))
LEFT OUTER JOIN view_rappels as vr1 ON hraps.id = vr1.hrap_id
LEFT OUTER JOIN view_rappels as vr2 ON ((vr1.hrap_id = vr2.hrap_id) AND (STR_TO_DATE( vr1.date,  '%m/%d/%Y' ) < STR_TO_DATE( vr2.date,  '%m/%d/%Y' )))
LEFT OUTER JOIN aircraft_types ON (
		(vr1.aircraft_type_id = aircraft_types.id) AND (".$aircraft_criteria.")
		)
WHERE vr2.hrap_id IS NULL
GROUP BY hraps.id
ORDER BY name";

	$result = mydb::cxn()->query($query);
	
	if(mydb::cxn()->affected_rows < 1) $text .= "<br /><span class=\"error_msg\">This ".$view_type." has no roster information for ".$_SESSION['current_view']['year']."</span>";
	else {
		$text .= "<br>\n"
				."<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
				."<th>+</th>"
				."<th>HRAP</th>"
				."<th>Aircraft</th>"
				."<th>Days Since Last Rap</th>"
				."<th>Days Until Lapse</th>"
				."<th>Status</th></tr>\n";
		
		$current_row = 0;
		while($row = $result->fetch_assoc()) {
			$current_row++;
			
			//if($current_row % 2 == 0) $class = "class=\"evn\"";
			//else $class = "class=\"odd\"";
			$class = "class=\"odd\"";
			
			// Determine whether the current HRAP is proficient or not and HIGHLIGHT this row accordingly
			$highlight = "";
			$p_status = "Proficient";
			if($row['days_ago'] > $_SESSION['proficiency_duration'] || ($row['days_ago']=="")) {
				$highlight = "background-color:#ff6666;";	// Highlight RED if HRAP is past proficiency date
				$p_status = "Not Proficient";
			}
			elseif($row['days_ago'] > ($_SESSION['proficiency_duration'] - 3)) $highlight = "background-color:#ffff66;"; // Highlight YELLOW if HRAP is within 3 days of proficiency date
			
			$text .= "<tr ".$class." style=\"height:1.8em;".$highlight."\">\n"
					."<td style=\"text-align:center;\">";
			
			if($row['operation_id'] != "") $text .= "<a href=\"view_rappels.php?&op=".$row['operation_id']."&hrap=".$row['hrap_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\"></a>";
			$text .= "</td>"
					."<td>".$row['name']."</td>"
					."<td>".$row['aircraft_fullname']."</td>"
					."<td style=\"text-align:center;\">".$row['days_ago']."</td>"
					."<td style=\"text-align:center;\">".($row['days_ago'] == "" ? "0" : max((int)$_SESSION['proficiency_duration'] - (int)$row['days_ago'],0))."</td>"
					."<td>".$p_status."</td>"
					."</tr>\n\n";
			
		}
		$text .= "</table><br>\n\n";
		
		$text .= color_coding_legend();
		
	} // End: else [if(mydb::cxn()->affected_rows < 1)]

	echo $text;
} // End: function show_proficiency_report()

function build_rappel_platform_dropdown() {
	// This function builds a dropdown menu containing the different rappel platform currently in the database.
	// All Bell Medium aircraft are grouped into a "Bell Medium" category, and light aircraft are listed separately.
	
	$query = "SELECT DISTINCT fullname,shortname FROM aircraft_types WHERE type = '3' ORDER BY fullname";
	$result = mydb::cxn()->query($query);
	
	$menu = "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"GET\" name=\"proficiency_aircraft_type_form\" id=\"proficiency_aircraft_type_form\">\n"
			."	<table><tr>	<td>Choose a rappel platform:</td>\n"
			."				<td>\n";
	if(isset($_SESSION['current_view']['crew'])) {
		$menu .= "					<INPUT TYPE=\"hidden\" name=\"crew\" value=\"".$_SESSION['current_view']['crew']->get('id')."\">\n";
	}
	if(isset($_SESSION['current_view']['region'])) {
		$menu .= "					<INPUT TYPE=\"hidden\" name=\"region\" value=\"".$_SESSION['current_view']['region']."\">\n";
	}
	$menu .= "					<SELECT name=\"rappel_platform\" id=\"rappel_platform\" onChange=\"this.form.submit();\">\n"
			."						<OPTION value=\"bell_medium\" >Bell Medium</OPTION>\n";
			
	while($row = $result->fetch_assoc()) {
		$selected = "";
		if(isset($_GET['rappel_platform']) && $_GET['rappel_platform'] == $row['shortname']) $selected = " SELECTED=selected";
		$menu .= "					<OPTION value=\"".$row['shortname']."\" ".$selected.">".$row['fullname']."</OPTION>\n";
	}
	$menu .= "					</SELECT>\n"
			."				</td>\n"
			."			</tr>\n"
			."	</table>\n"
			."</form>\n";
	
	return $menu;
}// End: function build_rappel_platform_dropdown()

function color_coding_legend() {
	$legend = "<br /><div class=\"alternating_rows\" style=\"width:100%;text-align:left;\"><h2>Legend:</h2></div>"
			. "<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
			. "<tr class=\"odd\" style=\"height:1.8em;\"><td>This rappeller is PROFICIENT with the specified rappel platform</td></tr>\n"
			. "<tr class=\"evn\" style=\"height:1.8em;background-color:#ffff66;\"><td>This rappeller will be out of proficient status within the next 3 days</td></tr>\n"
			. "<tr class=\"evn\" style=\"height:1.8em;background-color:#ff6666;\"><td>This rappeller is NOT PROFICIENT</td></tr>\n"
			. "</table>\n\n";
	return $legend;
} // End: color_coding_legend()
?>
