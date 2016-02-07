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
	require_once("includes/php_doc_root.php");
	
	// Must load class definitions BEFORE session_start() so that any objects stored in the SESSION array will be recognized
	require_once("classes/mydb_class.php");
	require_once("classes/user_class.php");
	require_once("classes/email_class.php");
	require_once("classes/hrap_class.php");
	require_once("classes/crew_class.php");
	
	session_name('raprec');
	session_start();
	if(isset($_GET['logout']) && ($_GET['logout'] == 1)) {
		session_destroy();
		session_name('raprec');
		session_start();
	}
	
	require("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	
	if(isset($_POST['mobile'])) $_SESSION['mobile'] = $_POST['mobile'];
	elseif(isset($_GET['mobile'])) $_SESSION['mobile'] = $_GET['mobile'];
	
	//Check for login attempts so redirects can be processed now
	if(isset($_POST['username']) && isset($_POST['passwd'])) {
		try {
			login($_POST['username'], $_POST['passwd']);
		} catch (Exception $e) {}
	}
	if(consult_intended_location() && isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == 1)) header('location:'.$_SESSION['intended_location']);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, history" />
<meta name="Description" content="The National Rappel Record Website. This site is used to record & view all of the helicopter rappels that are performed by the US Forest Service." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if(isset($_SESSION['mobile']) && ($_SESSION['mobile'] == 1)) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script type="text/javascript" src="includes/region_map.js"></script>

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
		if($_SESSION['current_view']['crew'] !== NULL) {
			// REGION:	selected
			// CREW:	selected
			// ACTION:	Display crew cumulatives & crew roster for selected year
			show_crew_cumulatives();
		}
		elseif($_SESSION['current_view']['region'] != NULL) {
			// REGION:	selected
			// CREW:	not selected
			// ACTION:	Display crew selection menu
			show_crew_selection_menu();
		}
		else {
			// REGION:	not selected
			// CREW:	not selected
			// ACTION:	Display region selection menu (map of USA)
			show_region_selection_menu();
		}
?>


    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>


<?php

function show_crew_cumulatives() {
	$crew = new crew;
	$crew->load($_SESSION['current_view']['crew']->get('id'));
	
	// Display Crew Cumulatives
	echo "<div style=\"text-align:center;\">\n";
	
	echo "<table style=\"margin:0 auto 0 auto;\">\n"
		."<tr><td><img src=\"".$crew->logo_filename."\"></td>\n"
		."<td><h1>".$crew->name."</h1>"
		."<table style=\"margin:0 auto 0 auto;\">\n"
		."<tr><td style=\"text-align:right\">Crew Size in ".$_SESSION['current_view']['year'].":</td><td style=\"text-align:right\">".$crew->crewmember_count."</td></tr>\n"
		."<tr><td style=\"text-align:right\">Total Rappels in ".$_SESSION['current_view']['year'].":</td><td style=\"text-align:right\">".$crew->raps_this_year_total."</td></tr>\n"
		."<tr><td style=\"text-align:right\">Operationals in ".$_SESSION['current_view']['year'].":</td><td style=\"text-align:right\">".$crew->raps_this_year_operational."</td></tr>\n"
		."<tr><td style=\"text-align:right\">Operationals-per-Person in ".$_SESSION['current_view']['year'].":</td><td style=\"text-align:right; padding-left:10px;\">".$crew->raps_this_year_per_person_operational."</td></tr>\n"
		."<tr><th colspan=\"2\" style=\"padding-top:10px;border-bottom:1px solid #555555;\">Demographics</th></tr>\n"
		."<tr><td style=\"text-align:right\">Average Age:</td><td style=\"text-align:right\">".$crew->avg_age."</td></tr>\n"
		."<tr><td style=\"text-align:right\">Crewmembers Who Are Male:</td><td style=\"text-align:right\">".$crew->gender_ratio."%</td></tr>\n"
		."<tr><td style=\"text-align:right\">Rappels by Men / Women in ".$_SESSION['current_view']['year'].":</td><td style=\"text-align:right\">".$crew->male_rappels." / ".$crew->female_rappels."</td></tr>\n"
		."</table>\n"
		."</td></tr>\n\n"
		."</table></div>\n\n";
	
	echo "<hr style=\"width:75%; height:3px; \">\n\n";
	
	echo "This crew has roster information for the following years:<br>\n";
	$year_str = "";
	if($year_array = $crew->get_roster_years()) {
		foreach($year_array as $year) {
			$year_str .= "<a href=\"".$_SERVER['PHP_SELF']."?region=".$_SESSION['current_view']['region']."&crew=".$_SESSION['current_view']['crew']->get('id')."&year=".$year."\">".$year."</a> | ";
		}
		$year_str = substr($year_str,0,strlen($year_str)-3); // Strip the last pipe divider off the string
	}
	echo $year_str."\n<br><br>\n";
	
	
	// Display crew roster - if user is logged in, each crewmember image will be a link to their rappel history. If NOT logged in, images are not links.
	try {
		if($crew->get_crewmembers($_SESSION['current_view']['year'])) {
			$col_count = 1;
			echo "<table style=\"margin:0 auto 0 auto;\">\n";
			foreach($crew->crewmembers as $hrap) {
				if(($col_count-1) % 5 == 0) echo "<tr>\n";
				echo "<td class=\"roster_thumbnail\">";
				if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) echo "<a href=\"view_rappels.php?hrap=".$hrap->get('id')."\">";
				echo "<img src=\"".$hrap->headshot_filename."\">";
				if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) echo "</a>";
/*				echo "<br>\n"
					."<table><tr><td colspan=\"2\" style=\"text-align:center;font-weight:bold;\">".$hrap->name."</td></tr>\n"
					."<tr><td style=\"text-align:right;\">Operationals:</td><td style=\"text-align:left;\">".$hrap->raps_all_time_operational."</td></tr></table>\n</td>";
*/
				echo "<br>\n"
					."<span style=\"font-weight:bold;\">".$hrap->name."</span><br>\n"
					.$hrap->raps_all_time_operational." Ops / ".$hrap->raps_all_time_live." Total</td>\n";
				if($col_count % 5 == 0) echo "\n</tr>\n";
				$col_count++;
			} // End: foreach
			echo "</table></div><br>\n";
		}
	} catch (Exception $e) {
		echo $e->getMessage()."<br>\n"; //Display message if there is no data for the selected year
	}
} // End: function show_crew_cumulatives()

function show_crew_selection_menu() {
	$i = 0;
	$crews_in_region = array();
	
	$query = "SELECT id FROM crews WHERE region = ".$_SESSION['current_view']['region'] . " && is_academy <> 1";
	$result = mydb::cxn()->query($query);
	while($row = $result->fetch_assoc()) {
		$crews_in_region[$i] = new crew;
		try {
			$crews_in_region[$i]->load($row['id']);
		} catch (Exception $e) {
			echo $e->getMessage()."<br>\n";
		}
		$i++;
	}
	
	// Display a list of all crews in the selected region
	$output = "<h2>Please select your Crew below:</h2><br />\n";
	$col_count = 1;
	$output .= "<div class=\"crew_thumbnails\" style=\"text-align:center;\"><table style=\"margin:0 auto 0 auto;\">\n";
	foreach($crews_in_region as $crew) {
		if(($col_count-1) % 3 == 0) $output .= "<tr>";
		$output .= "<td style=\"vertical-align:bottom; padding:15px;\"><a href=\"./?region=".$crew->region."&crew=".$crew->id."\"><img src=\"".$crew->logo_filename."\"><br><b>".$crew->name."</b></a><br>\n"
				.  "Crew Operationals (".$_SESSION['current_view']['year']."): ".$crew->raps_this_year_operational."<br>\n"
				.  "Avg Raps per Person: ".$crew->raps_this_year_per_person_live;
		if($col_count % 3 == 0) $output .= "</td></tr>\n";
		else $output .= "</td>\n";
		
		$col_count++;
	} // End: foreach($crews_in_region as $crew)
	if(($col_count-1) % 3 != 0) $output .= "</tr>";
	$output .= "</table></div>\n\n";
	
	if($col_count == 1) $output = "<br><br>\n<h2>There are no crews from Region ".$_SESSION['current_view']['region']." registered!</h2>\n";
	
	echo $output;
}

function show_region_selection_menu() {
	echo "<h1>RapRec Central</h1><br />"
		."<h2 style=\"color:#999999\">A national database for tracking helicopter rappel information.</h2><br />"
		."<hr />"
		."<br />";
		
	echo "<h2>Please select your Region below:</h2><br />\n";
	include("includes/region_map.html");
}
