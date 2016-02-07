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
require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");
require_once("classes/user_class.php");

require_once("includes/constants.php");
require_once("includes/auth_functions.php");

function make_menu() {
/******* PROCESS LOGIN ATTEMPT ***************************/
/*
		if(isset($_POST['username']) && isset($_POST['passwd'])) $login_result = login($_POST['username'], $_POST['passwd']);
		elseif(!isset($_SESSION['logged_in']) && !isset($login_result)) $login_result = array(-1,"No login attempt made yet");
		else $login_result = array(-1,"No login attempt made yet");
*/
/******* MAKE LOCATION BAR ***************************/
	if(isset($_GET['year']) && check_year($_GET['year'])) $_SESSION['current_view']['year'] = $_GET['year'];
	elseif(!isset($_SESSION['current_view']['year'])) $_SESSION['current_view']['year'] = date('Y');

	if(!isset($_SESSION['mobile'])) $_SESSION['mobile'] = false;

	$current_file = explode('/',$_SERVER['PHP_SELF']);
	$current_file = strtolower($current_file[sizeof($current_file)-1]); // Get the filename of the script that called this function (no path info, just the filename)

	$region = NULL;
	$crew = new crew;
	$hrap = new hrap;
	$op = NULL;

	try {
		//This exception below is meant solely to trigger the 'catch' block.  The message is never displayed to the user.
		if($current_file == "index.php" || $current_file == "proficiency_report.php") throw new Exception('You cannot specify an individual HRAP on the index or proficiency page.');

		isset($_GET['hrap']) ? $hrap->load($_GET['hrap']) : $hrap->load(false);
		$crew->load($hrap->get_crew_by_year($_SESSION['current_view']['year']));

		$_SESSION['current_view']['hrap'] = $hrap;
		if(isset($crew->id)) {
			$_SESSION['current_view']['region'] = $crew->region;
			$_SESSION['current_view']['crew'] = $crew;
			$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
			$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->region."\">R".$crew->region."</a>";
			$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->region."&crew=".$crew->id."\">".$crew->name."</a>";
			$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->region."&crew=".$crew->id."&hrap=".$hrap->id."\">".$hrap->name."</a>";
		}
		else {
			/* The requested HRAP is not assigned to a crew for the requested year, maintain the previous 'current_view' (do nothing)*/
		}
		
	} catch (Exception $e) {
		if(isset($_GET['eq_type'])) {
			// The current view is on a piece of equipment, retain CREW and REGION but reset all other 'current_view' parameter
			$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
			if(isset($_GET['eq_id'])) {
				try {
					$eq = new $_GET['eq_type'];
					$eq->load($_GET['eq_id']);
					$_SESSION['current_view']['crew'] = new crew;
					$_SESSION['current_view']['crew']->load($eq->get('crew_affiliation_id'));
					$result = mydb::cxn()->query("SELECT region FROM crews WHERE id = ".$eq->get('crew_affiliation_id'));
					$row = $result->fetch_assoc();
					$_SESSION['current_view']['region'] = $row['region'];
					$_SESSION['location_bar'] .= " / <a href=\"view_equipment.php?eq_type=".$_GET['eq_type']."&region=".$_SESSION['current_view']['region']."\">R".$_SESSION['current_view']['region']."</a>"
												." / <a href=\"view_equipment.php?eq_type=".$_GET['eq_type']."&crew=".$_SESSION['current_view']['crew']->get('id')."\">".$_SESSION['current_view']['crew']->get('name')."</a>"
												." / <a href=\"view_equipment.php?crew=".$_SESSION['current_view']['crew']->get('id')."\">Equipment</a>";
				} catch (Exception $e) {}
			}
			elseif(isset($_GET['crew']) && check_crew($_GET['crew'])) {
				$crew->load($_GET['crew']);
				$_SESSION['current_view']['region'] = $crew->get('region');
				$_SESSION['current_view']['crew'] = $crew;
				$_SESSION['location_bar'] .= " / <a href=\"view_equipment.php?eq_type=".$_GET['eq_type']."&region=".$_SESSION['current_view']['region']."\">R".$_SESSION['current_view']['region']."</a>"
												." / <a href=\"view_equipment.php?eq_type=".$_GET['eq_type']."&crew=".$_SESSION['current_view']['crew']->get('id')."\">".$_SESSION['current_view']['crew']->get('name')."</a>"
												." / <a href=\"view_equipment.php?crew=".$_SESSION['current_view']['crew']->get('id')."\">Equipment</a>";
			}
			elseif(isset($_GET['region']) && is_valid_region($_GET['region'])) {
				$_SESSION['current_view']['region'] = $_GET['region'];
				$_SESSION['location_bar'] .= " / <a href=\"view_equipment.php?eq_type=".$_GET['eq_type']."&region=".$_SESSION['current_view']['region']."\">R".$_SESSION['current_view']['region']."</a>"
											." / <a href=\"view_equipment.php?region=".$_SESSION['current_view']['region']."\">Equipment</a>";
				$_SESSION['current_view']['crew'] = NULL;
			}

			$_SESSION['current_view']['hrap'] = NULL;
			$_SESSION['current_view']['op'] = NULL;
		}
		elseif(isset($_GET['crew']) && check_crew($_GET['crew'])) {
			try {
				$crew->load($_GET['crew']);
			} catch(Exception $e) { }
				
			$_SESSION['current_view']['region'] = $crew->get('region');
			$_SESSION['current_view']['crew'] = $crew;
			$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
			if($current_file != "modify_roster.php") {
				$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->get('region')."\">R".$crew->get('region')."</a>";
			}
			else $_SESSION['location_bar'] .= " / <a href=\"./index.php?region=".$crew->get('region')."\">R".$crew->get('region')."</a>";
			 $_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->get('region')."&crew=".$crew->get('id')."\">".$crew->get('name')."</a>";

			if($current_file == "proficiency_report.php") $_SESSION['location_bar'] .= " / Proficiency Report";
			// Clear the unknown 'current_view' elements
			$_SESSION['current_view']['hrap'] = NULL;
			$_SESSION['current_view']['op'] = NULL;
		}
		elseif(isset($_GET['region']) && is_valid_region($_GET['region'])) {
			$region = $_GET['region'];
			$_SESSION['current_view']['region'] = $region;
			$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
			$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$region."\">R".$region."</a>";

			if($current_file == "proficiency_report.php") $_SESSION['location_bar'] .= " / Proficiency Report";
			// Clear the unknown 'current_view' elements
			$_SESSION['current_view']['crew'] = NULL;
			$_SESSION['current_view']['hrap'] = NULL;
			$_SESSION['current_view']['op'] = NULL;
		}
		elseif(isset($_GET['op']) && operation::exists($_GET['op'])) {
			// If a specific OPERATION is being viewed (and none of the above criteria were met), there is a mix of different CREWS, HRAPS, and possibly REGIONS...
			// So just maintain the same location bar that was shown before the user accessed this page
			$op = $_GET['op'];
			// DO NOTHING
		}
		elseif($current_file == "proficiency_report.php") {
			// Viewing the proficiency report, but no Crew or Region has been specified.
			// 1st - Look for pre-existing crew or region in the $_SESSION['current_view'] array
			// 2nd - Try to determine the current user's crew and use that
			// 3rd - Display the page with no parameters - the page will show an error and offer a link to the Home page
			if(isset($_SESSION['current_view']['crew'])) {
				$crew = $_SESSION['current_view']['crew'];
				$region = $crew->get('region');

				$_SESSION['current_view']['region'] = $region;

				$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
				$_SESSION['location_bar'] .= " / <a href=\"".$current_file."?region=".$region."\">R".$region."</a>";
				$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->get('region')."&crew=".$crew->get('id')."\">".$crew->get('name')."</a>";
				$_SESSION['location_bar'] .= " / Proficiency Report";

				// Clear the unknown 'current_view' elements
				$_SESSION['current_view']['hrap'] = NULL;
				$_SESSION['current_view']['op'] = NULL;
			}
			elseif(isset($_SESSION['current_view']['region'])) {
				$region = $_SESSION['current_view']['region'];

				$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
				$_SESSION['location_bar'] .= " / <a href=\"".$current_file."?region=".$region."\">R".$region."</a>";
				$_SESSION['location_bar'] .= " / Proficiency Report";

				// Clear the unknown 'current_view' elements
				$_SESSION['current_view']['crew'] = NULL;
				$_SESSION['current_view']['hrap'] = NULL;
				$_SESSION['current_view']['op'] = NULL;
			}
			elseif(isset($_SESSION['current_user']) && $_SESSION['current_user']->get('crew_affiliation_id') != false) {
				$crew = new crew;
				$crew->load($_SESSION['current_user']->get('crew_affiliation_id'));
				$region = $crew->get('region');

				$_SESSION['current_view']['crew'] = $crew;
				$_SESSION['current_view']['region'] = $region;


				$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
				$_SESSION['location_bar'] .= " / <a href=\"".$current_file."?region=".$region."\">R".$region."</a>";
				$_SESSION['location_bar'] .= " / <a href=\"./".$current_file."?region=".$crew->get('region')."&crew=".$crew->get('id')."\">".$crew->get('name')."</a>";
				$_SESSION['location_bar'] .= " / Proficiency Report";

				// Clear the unknown 'current_view' elements
				$_SESSION['current_view']['hrap'] = NULL;
				$_SESSION['current_view']['op'] = NULL;
			}

		}
		else {
			// Clear the unknown 'current_view' elements
			$_SESSION['location_bar'] = "Location: <a href=\"./index.php\">Home</a>";
			$_SESSION['current_view']['region'] = NULL;
			$_SESSION['current_view']['crew'] = NULL;
			$_SESSION['current_view']['hrap'] = NULL;
			$_SESSION['current_view']['op'] = NULL;
		}
	} // End: try/catch block

/********************************************************************************************************************************/
/*******************<< USER is not logged in (yet) >>****************************************************************************/
	if(!isset($_SESSION['logged_in']) || ($_SESSION['logged_in'] != 1)) {
		//Initialize the current_user as a GUEST
		$_SESSION['current_user'] = new user('guest');

		/******* PROCESS LOGIN ATTEMPT ***************************/
		try {
			if(!isset($_POST['username']) || !isset($_POST['passwd'])) throw new Exception(''); //No login attempt was made
			login($_POST['username'], $_POST['passwd']);
		} catch (Exception $e) {
			// If this block is reached, either no login attempt was made, or a login attempt failed with an exception.
			echo "<div id=\"left_sidebar_title\">Login</div>\n";

			echo "<form action=\"" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "\" method=\"post\" style=\"margin:0; padding:0;\">\n"
				."	<table style=\"margin:0; padding:0;\">\n"
				."		<tr><td colspan=\"2\"><div id=\"login_result\">".$e->getMessage()."</div></td></tr>\n"
				."		<tr><td>Username:</td><td><input name=\"username\" type=\"text\" class=\"loginfield\" value=\"".(isset($_POST['username']) ? $_POST['username'] : "")."\" /></td></tr>\n"
				."		<tr><td>Password:</td><td><input name=\"passwd\" type=\"password\" class=\"loginfield\" /></td></tr>\n"
				."		<tr><td>&nbsp;</td><td style=\"text-align:right;\"><input type=\"submit\" value=\"Login\" class=\"form_button\" style=\"margin-right:0;\" /></td></tr>\n"
				."	</table>\n"
				."</form>";

			echo "<hr><br>\n"
				."<table>\n"
				."	<tr><td>You are viewing information for the following year:</td></tr>\n"
				."	<tr><td style=\"text-align:center;vertical-align:center;padding:0;\">\n"
				."		<form action=\"" . $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"GET\" id=\"sidebar_year_form\" name=\"sidebar_year_form\">\n"
				."		<input name=\"year\" type=\"text\" value=\"".$_SESSION['current_view']['year']."\" style=\"width:40px; height:1.3em; font-size:1.2em; font-weight:bold; margin:1px;\">\n";

			if($region != NULL) echo "<input type=\"hidden\" name=\"region\" value=\"".$_GET['region']."\">\n";
			if($crew != NULL) echo "<input type=\"hidden\" name=\"crew\" value=\"".$crew->get('id')."\">\n";
			if($hrap != NULL) echo "<input type=\"hidden\" name=\"hrap\" value=\"".$hrap->get('id')."\">\n";
			if($op != NULL) echo "<input type=\"hidden\" name=\"op\" value=\"".$op."\">\n";

			if(isset($_GET['function']) && ($_GET['function'] != '')) echo "<input type=\"hidden\" name=\"function\" value=\"".$_GET['function']."\">\n";

			echo "		<input type=\"button\" value=\"Update\" class=\"form_button\" onClick=' document.forms.sidebar_year_form.submit();'>\n"
				."		</form>\n"
				."		</td>\n"
				."	</tr>\n"
				."</table>\n"
				."<br><hr>\n\n";
		}

	}

/******* USER IS LOGGED IN*******************************************************************************************************/
	if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == 1)) {
		echo "<div id=\"left_sidebar_title\">RapRec Menu</div>\n";

		echo "You are logged in as:<br />".$_SESSION['current_user']->get('firstname')." ".$_SESSION['current_user']->get('lastname')."<br /><i>".$_SESSION['current_user']->get('username')."</i><br><br>\n";

		echo "<a href=\"index.php?logout=1".(isset($_SESSION['mobile']) ? "&mobile=".$_SESSION['mobile'] : "")."\">Logout</a><br>";

		echo "<hr><br>\n"
			."<table>\n"
			."	<tr><td>You are viewing information for the following year:</td></tr>\n"
			."	<tr><td>\n"
			."		<form action=\"" . $_SERVER['PHP_SELF'] . "?". $_SERVER['QUERY_STRING'] . "\" method=\"GET\" id=\"sidebar_year_form\" name=\"sidebar_year_form\">\n"
			."		<input name=\"year\" id=\"sidebar_year\" type=\"text\" size=\"4\" value=\"".$_SESSION['current_view']['year']."\" style=\"width:40px\">\n";

		if($region != NULL) echo "<input type=\"hidden\" name=\"region\" value=\"".$_GET['region']."\">\n";
		if($crew != NULL) echo "<input type=\"hidden\" name=\"crew\" value=\"".$crew->get('id')."\">\n";
		if($hrap != NULL) echo "<input type=\"hidden\" name=\"hrap\" value=\"".$hrap->get('id')."\">\n";
		if($op != NULL) echo "<input type=\"hidden\" name=\"op\" value=\"".$op."\">\n";

		if(isset($_GET['function']) && $_GET['function'] != '') echo "<input type=\"hidden\" name=\"function\" value=\"".$_GET['function']."\">\n";

		echo "<input type=\"button\" value=\"Update\" class=\"form_button\" onClick='document.forms.sidebar_year_form.submit();'></form></td></tr></table><br><hr>\n\n";
/*
		//Decide what to show in the 'Location Bar' when navigating to the 'update_rappels.php' page, since updating rappels is not a crew-dependent operation
		if(isset($_SESSION['current_view']['crew']) && ($_SESSION['current_view']['crew']->get('id') != NULL)) $update_rappels_crew_id = $_SESSION['current_view']['crew']->get('id');
		else $update_rappels_crew_id = $_SESSION['current_user']->get('crew_affiliation_id');
*/
		$crew_id = "";
		$crew_name="None Selected";
		$region = "";
		if(isset($_SESSION['current_view']['crew'])) {
			$crew_id = $_SESSION['current_view']['crew']->get('id');
			$crew_name = $_SESSION['current_view']['crew']->get('name');
		}
		if(isset($_SESSION['current_view']['region'])) {
			$region = $_SESSION['current_view']['region'];
		}
		$academy_id = get_academy_id($region);

		echo "<h3>".ucwords(str_replace("_"," ",$_SESSION['current_user']->get('account_type')))."</h3><br><br>\n"
			."<ul class=\"sidebar_menu\">\n";

		echo "<li>Home\n"
			."<ul>\n"
			."	<li><a href=\"index.php\">National Map</a></li>\n";
			
		if($_SESSION['current_user']->get('crew_affiliation_id')) {
			echo "	<li><a href=\"index.php?crew=".$_SESSION['current_user']->get('crew_affiliation_id')."\">My Crew</a></li>\n";
		}
		
		echo "<li><a href=\"weekly_report.php\">Weekly Report</a></li>\n";
		
		echo "</ul></li>\n";
		
		
/*
			if($crew_id != "") {
				echo "<li><a href=\"modify_roster.php?crew=".$crew_id."\">Current Crew<br><small>(".$crew_name.")</small></a>\n"
					."<ul>\n"
					."	<li><a href=\"modify_roster.php?crew=".$crew_id."\">View / Edit Crewmembers</a></li>\n"
					."	<li><a href=\"modify_roster.php?crew=".$crew_id."&function=add_hrap_menu\">Add Crewmembers</a></li>\n"
					."</ul></li>\n\n";
			}
*/

		if(($region != "") || ($crew_id != "") || ($_SESSION['current_user']->get('account_type') == 'crew_admin')) echo "<li>Rosters\n<ul>\n";
		if($region != "") echo "	<li><a href=\"index.php?region=".$region."\">Regional Crew List</a></li>\n";
		if($crew_id != "") echo "	<li><a href=\"index.php?region=".$region."&crew=".$crew_id."\">Crew Roster</a></li>\n";
		if($_SESSION['current_user']->get('account_type') == 'crew_admin') {
			echo "	<li><a href=\"modify_roster.php?&crew=".$_SESSION['current_user']->get('crew_affiliation_id')."\">Modify My Roster</a></li>\n";
		}
		if(($crew_id != "") && ($_SESSION['current_user']->get('account_type') == 'admin')) {
			echo "	<li><a href=\"modify_roster.php?&crew=".$crew_id."\">Modify This Roster</a></li>\n";
		}
		if(($region != "") || ($crew_id != "") || ($_SESSION['current_user']->get('account_type') == 'crew_admin')) echo "</ul></li>\n";
		
		if(in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) {
			echo "	<li>Rappel Records\n"
				."	<ul>\n"
				."	<li><a href=\"update_rappels.php?function=add_rappel\">Add a New Rappel</a></li>\n";
		}
				
		elseif($crew_id != "" || $region != "") {
			echo "	<li>Rappel Records\n"
					."	<ul>\n";
		}
		
		if($crew_id != "" || $region != "") {
			echo "	<li><a href=\"proficiency_report.php?region=".$region."\">Regional Proficiency Report</a></li>\n"
				."	<li><a href=\"view_rappels.php?region=".$region."\">Regional Rappels</a></li>\n";
		}
		if($crew_id != "") {
			echo "	<li><a href=\"proficiency_report.php?crew=".$crew_id."\">Crew Proficiency Report</a></li>\n"
				."	<li><a href=\"view_rappels.php?crew=".$crew_id."\">Crew Rappels</a></li>\n";
		}
		if($crew_id != "" || $region != "" || in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) {
			echo "</ul></li>\n\n";
		}

		if($crew_id != "" || $region != "") {
			echo "<li>Equipment\n"
				."<ul>\n";
			if(in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) {
				echo "	<li><a href=\"add_new_equipment.php?crew=".$crew->get('id')."\">Add New Equipment</a></li>\n";
			}
			echo "	<li><a href=\"view_equipment.php?region=".$region."\">Regional Equipment</a></li>\n";

			if($academy_id != false) {
				echo "	<li><a href=\"view_equipment.php?crew=".$academy_id."&region=".$region."\">Academy Equipment</a></li>\n";
			}

			if($crew_id != "") echo "	<li><a href=\"view_equipment.php?crew=".$crew_id."\">Crew Equipment</a></li>\n";
			echo "</ul></li>\n\n";
		}

		echo "	<li>Account Management\n"
			."		<ul><li><a href=\"account_management.php?function=edit_account&user_id=".$_SESSION['current_user']->get('id')."\">Edit My Account</a></li>\n";
		if(in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) echo "		<li><a href=\"account_management.php\">All Accounts</a></li>\n";
		if(in_array($_SESSION['current_user']->get('account_type'),array('admin','crew_admin'))) echo "		<li><a href=\"account_management.php?function=create_account\">Create an Account</a></li></ul>\n";
		echo "	</li>\n";

		echo "</ul>\n\n";
	}
}

/************************************************************************************************************/
/************************************************************************************************************/
function get_academy_id($region = NULL) {
	// Academy programs are stored in the CREWS dB table. Example: The Region 6 Rappel Academy has an entry in
	// the CREWS table so that academy-owned equipment can be associated with the Academy
	//
	// This function will return the ID (crews.id) of the Academy program for the requested $region
	// If this region does not have an academy program, this function will return FALSE.

	$output = false;

	if(!is_null($region)) {
		$result = mydb::cxn()->query("SELECT id FROM crews WHERE region = ".mydb::cxn()->real_escape_string($region)." && is_academy = 1 LIMIT 1");
		if(mydb::cxn()->affected_rows > 0) {
			$row = $result->fetch_assoc();
			$output = $row['id'];
		}
	}

	return $output;
}
?>
