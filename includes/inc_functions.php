<?php
/*
  A database connection must exist
  (mydb_class.php must be loaded)
*/

function my_is_int($var) {
	return (is_numeric($var)&&(intval($var)==floatval($var)));
}


//==========================================================================================================
function get_incidents($crewmember_id = -1) {

// Build a database query based on a user-specified sort field
// Run display_inv to step through each row and display the inventory data

	$query = "	SELECT	incidents.idx,
					unix_timestamp(incidents.date) as date,
					incidents.event_type,
					incidents.number,
					incidents.name,
					incidents.code,
					incidents.override,
					incidents.size,
					incidents.type,
					incidents.fuel_models,
					incidents.description,
					incidents.latitude_degrees,
					incidents.latitude_minutes,
					incidents.longitude_degrees,
					incidents.longitude_minutes
			FROM incidents
			WHERE year(date) = '".$_SESSION['incident_year']."'";

	$sort_by = $_SESSION['sort_view_by'];

	switch($sort_by) {
		case "number":
			$query .= " ORDER BY number, date";
			break;
		case "name":
			$query .= " ORDER BY name, number, date";
			break;
		case "override":
			$query .= " ORDER BY override, number, date";
			break;
		case "event_type":
			$query .= " ORDER BY event_type, number, date";
			break;
		case "date":
		default:
			$query .= " ORDER BY date, number";
			$_SESSION['sort_view_by'] = "date";
			break;
	}

	$result = mydb::cxn()->query($query) or die("dB query failed (Retrieving incidents): " . mydb::cxn()->error);

	$incident_array = array();

	$i = 0;
	while($row = $result->fetch_assoc()) {
		$query2 = "	SELECT CONCAT(crewmembers.firstname,' ',crewmembers.lastname) AS name, crewmembers.id
					FROM crewmembers	INNER JOIN incident_roster	ON crewmembers.id = incident_roster.crewmember_id
										INNER JOIN incidents		ON incident_roster.idx = incidents.idx
					WHERE incident_roster.idx LIKE '".$row['idx']."' ORDER BY name";

		$result2 = mydb::cxn()->query($query2) or die("dB query failed (Retrieving incident_roster): " . mydb::cxn()->error);


		//If a specific crewmember has been specified, only return incidents where that crewmember was present
		if($crewmember_id != -1) $searching = 1;
		else $searching = 0;

		$found = 0;
		while($roster_row = $result2->fetch_assoc()) {
			$one_incident_roster[] = $roster_row['name'];
			if($crewmember_id == $roster_row['id']) $found = 1;
		}

		if(!$searching || ($searching && $found)) {
			$incident_array[$i] = $row;
			$incident_array[$i]['roster'] = $one_incident_roster;
			$i++;
		}

		$one_incident_roster = array();
	}

	return $incident_array;
}


//==========================================================================================================
function display_incidents($incident_array, $php_self) {

// Display the incidents, sorted by a user-specified field
// This function requires that get_incidents has been previously run to submit the initial database query

	$sort_by = $_SESSION['sort_view_by'];
	echo "<span class=\"highlight1\" style=\"display:block\">Incidents are sorted by " . $sort_by . "</span>\n";


	//Create dropdown menu to select year

	echo "<table><tr><td>";
	$result = mydb::cxn()->query("SELECT DISTINCT year(date) as year FROM incidents ORDER BY date DESC") or die("Error retrieving dates for year selection menu: " . mydb::cxn()->error);
	echo "<form action=\"".$php_self."\" method=\"GET\">"
		."<select name=\"year\">\n";

	while($row = $result->fetch_assoc()) {
		if($row['year'] == $_SESSION['incident_year']) echo "<option value=\"".$row['year']."\" SELECTED>".$row['year']."</option>\n";
		else echo "<option value=\"".$row['year']."\">".$row['year']."</option>\n";
	}

	echo "</select>"
		."<input type=\"submit\" value=\"View\">"
		."</form>\n\n";
	echo "</td><td>";

	//Get current roster
	$result = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . $_SESSION['incident_year'] . "' ORDER BY name");

	$option_menu = 	"<select name=\"crewmember_id\">\n"
					."<option value=\"-1\">&nbsp;</option>\n";

	while($row = $result->fetch_assoc()) {
		if(isset($_GET['crewmember_id']) && ($row['id'] == $_GET['crewmember_id'])) $option_menu .= "<option value=\"" . $row['id'] . "\" SELECTED>" . $row['name'] . "</option>\n";
		else $option_menu .= "<option value=\"" . $row['id'] . "\">" . $row['name'] . "</option>\n";
	}

	$option_menu .= "</select>\n";

	echo "<form action=\"".$php_self."\" method=\"GET\">\n"
		."<input type=\"hidden\" name=\"function\" value=\"view_crewmembers_incidents\">\n"
		. $option_menu
		."<input type=\"submit\" value=\"View\">\n"
		."</form>\n";

	echo "</td></tr></table>\n";

	echo "<table>\n";

	//INITIALIZE PHP VARIABLES
	$row_count = 0;
	$last_cat_title = "~`$(*#)*@(!-_~}}"; //Junk - an unlikely initial value
	$table_headers = "	<tr><th><a href=\"" . $php_self . "?sort_by=date\">Date</a></th>
							<th><a href=\"" . $php_self . "?sort_by=event_type\">Event Type</a></th>
							<th><a href=\"" . $php_self . "?sort_by=number\">Inc #</a></th>
							<th><a href=\"" . $php_self . "?sort_by=name\">Name</a></th>
							<th>Charge Code</th>
							<th><a href=\"" . $php_self . "?sort_by=override\">Override</a></th>
							<th>Size</th>
							<th>Type</th>
							<th>Roster</th>
							<th>Fuel Models</th>
							<th>Description</th>
						</tr>\n";

	//Print table headers ONCE at the top when sorting by date
	if($sort_by == 'date') {
		//$cur_cat_title = date('d-M-Y',$row['date']);
		//echo "<tr class=\"new_cat_row\"><td class=\"new_cat_cell\" colspan=\"9\">".$cur_cat_title."</td></tr>\n";
		echo $table_headers;
	}

	foreach($incident_array as $row) {
		$row_count++;
		$namelist = '';

		if($row['size'] <= 0.25) $size_class = 'A';
		elseif(($row['size'] >  0.25) && ($row['size'] <   10)) $size_class = 'B';
		elseif(($row['size'] >=   10) && ($row['size'] <  100)) $size_class = 'C';
		elseif(($row['size'] >=  100) && ($row['size'] <  300)) $size_class = 'D';
		elseif(($row['size'] >=  300) && ($row['size'] < 1000)) $size_class = 'E';
		elseif(($row['size'] >= 1000) && ($row['size'] < 5000)) $size_class = 'F';
		elseif($row['size']  >= 5000) $size_class = 'G';
		else $size_class = '?';

		if($sort_by == 'date') $cur_cat_title = date('d-M-Y',$row[$sort_by]);
		else $cur_cat_title = $row[$sort_by];

		if(($last_cat_title != $cur_cat_title) && ($sort_by != 'date')) { //Don't reprint table headers when sorting by date
			echo "<tr class=\"new_cat_row\"><td class=\"new_cat_cell\" colspan=\"9\">".htmlentities($cur_cat_title)."</td></tr>\n";
			echo $table_headers;
		}

		$last_cat_title = $cur_cat_title;


		// Shorten notes to 25 characters to save space on screen
		$full_desc = htmlentities($row['description']);
		$short_desc = substr($full_desc,0,25);
		if(strlen($full_desc) > strlen($short_desc)) $short_desc .= "...";

		// Color code alternating rows
		if($row_count % 2 == 0) echo "<tr class=\"evn\">";
		else echo "<tr class=\"odd\">";

		echo "  <td>".date('d-M-Y',$row['date'])."</td>\n"
			."<td style=\"text-transform:uppercase;\">".$row['event_type']."</td>\n"
			."<td style=\"text-transform:uppercase;\"><a href=\"".$php_self."?function=edit_line&idx=".$row['idx']."\">".htmlentities($row['number'])."</a></td>\n"
			."<td>".htmlentities($row['name'])."</td>\n"
			."<td style=\"text-transform:uppercase;\">".$row['code']."</td>\n"
			."<td>".$row['override']."</td>\n"
			."<td>".$row['size']." (".$size_class.")</td>\n"
			."<td>".$row['type']."</td>\n"
			."<td>";

		foreach($row['roster'] as $name) {
			$namelist .= $name . ", ";
		}

		if(strlen($namelist)>0) $namelist = substr($namelist,0,strlen($namelist)-2); //Strip last comma & space
		echo $namelist;
		echo "	</td>\n"
				."<td>".$row['fuel_models']."</td>\n"
				."<td style=\"text-transform:none;\">".$short_desc."</td>\n"
				."</tr>\n";

	} //END foreach($incident_array as $row)

	echo "</table>";

	$_SESSION['form_field1'] = '';
	$_SESSION['form_field2'] = '';
	$_SESSION['form_field3'] = '';
	$_SESSION['form_field4'] = '';
	$_SESSION['form_field5'] = '';
	$_SESSION['form_field6'] = '';
	$_SESSION['form_field7'] = '';
	$_SESSION['form_field8'] = '';
	$_SESSION['form_field9'] = '';
	$_SESSION['form_field10'] = '';
	$_SESSION['form_field11'] = '';
}


//==========================================================================================================
function view_crewmembers_incidents($incident_array, $crewmember_id, $php_self) {

	//Lookup crewmember's name from the crewmember_id
	$result = mydb::cxn()->query("SELECT CONCAT(firstname,' ',lastname) as name FROM crewmembers WHERE id LIKE '".$crewmember_id."'");
	if(mydb::cxn()->error != '') {
		die("Error retrieving crewmember name: " . mydb::cxn()->error . "<br>\n".$query);
	}
	$crewmember = $result->fetch_assoc();

	if($crewmember_id != -1) {
		echo "<span class=\"highlight1\" style=\"display:block\">Viewing incidents where " . $crewmember['name'] . " was present</span>\n";
		echo "<a href=\"incidents/iqcs_form.php?crewmember_id=".$crewmember_id."\" target=\"_new\">IQCS Update Form</a></br>\n";
	}
	else echo "<span class=\"highlight1\" style=\"display:block\">Viewing all incidents</span>\n";


	//Create dropdown menu to select year
	echo "<table><tr><td>";
	$result = mydb::cxn()->query("SELECT DISTINCT year(date) as year FROM incidents ORDER BY date DESC");
	if(mydb::cxn()->error != '') {
		die("Error retrieving dates for year selection menu: " . mydb::cxn()->error . "<br>\n".$query);
	}
	echo "<form action=\"".$php_self."\" method=\"GET\">"
		."<select name=\"year\">\n";

	while($row = $result->fetch_assoc()) {
		if($row['year'] == $_SESSION['incident_year']) echo "<option value=\"".$row['year']."\" SELECTED>".$row['year']."</option>\n";
		else echo "<option value=\"".$row['year']."\">".$row['year']."</option>\n";
	}

	echo "</select>"
		."<input type=\"submit\" value=\"View\">"
		."</form>\n\n";
	echo "</td><td>";


	//Get current roster
	$result = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
							FROM crewmembers inner join roster
							ON crewmembers.id = roster.id
							WHERE roster.year like '" . $_SESSION['incident_year'] . "' ORDER BY name");

	$option_menu = 	"<select name=\"crewmember_id\">\n"
					."<option value=\"-1\">&nbsp;</option>\n";

	while($row = $result->fetch_assoc()) {
		if($row['id'] == $_GET['crewmember_id']) $option_menu .= "<option value=\"" . $row['id'] . "\" SELECTED>" . $row['name'] . "</option>\n";
		else $option_menu .= "<option value=\"" . $row['id'] . "\">" . $row['name'] . "</option>\n";
	}

	$option_menu .= "</select>\n";

	echo "<form action=\"".$php_self."\" method=\"GET\">\n"
		."<input type=\"hidden\" name=\"function\" value=\"view_crewmembers_incidents\">\n"
		. $option_menu
		."<input type=\"submit\" value=\"View\">\n"
		."</form>\n";

	echo "</td></tr></table>\n";
	echo "<table>\n";

	//INITIALIZE PHP VARIABLES
	$row_count = 0;
	$table_headers = "	<tr>	<th><a href=\"" . $php_self . "?sort_by=date\">Date</a></th>
						<th><a href=\"" . $php_self . "?sort_by=event_type\">Event Type</a></th>
						<th><a href=\"" . $php_self . "?sort_by=number\">Inc #</a></th>
						<th><a href=\"" . $php_self . "?sort_by=name\">Name</a></th>
						<th>Charge Code</th>
						<th><a href=\"" . $php_self . "?sort_by=override\">Override</a></th>
						<th>Size</th>
						<th>ICT</th>
						<th>Role</th>
						<th>Q/T</th>
						<th>Shifts</th>
						<th>Roster</th>
						<th>Fuel Models</th>
						<th>Description</th>
						</tr>\n";

	echo $table_headers;

	foreach($incident_array as $row) {
		$row_count++;
		$namelist = '';

		// Shorten notes to 25 characters to save space on screen
		$full_desc = htmlentities($row['description']);
		$short_desc = substr($full_desc,0,25);

		if(strlen($full_desc) > strlen($short_desc)) $short_desc .= "...";

		// Color code alternating rows
		if($row_count % 2 == 0) echo "<tr class=\"evn\">";
		else echo "<tr class=\"odd\">";

		$result = mydb::cxn()->query("	SELECT role,qt,shifts
						FROM incident_roster
						WHERE idx like '".$row['idx']."' and crewmember_id like '".$crewmember_id."'");

		$row2 = $result->fetch_assoc();

		echo "	<td style=\"padding:1px 2px 1px 2px\">".date('d-M-Y',$row['date'])."</td>
				<td style=\"padding:1px 2px 1px 2px; text-transform:uppercase;\">".$row['event_type']."</td>
				<td style=\"padding:1px 2px 1px 2px; text-transform:uppercase;\"><a href=\"".$php_self."?function=edit_line&idx=".$row['idx']."\">".$row['number']."</a></td>
				<td style=\"padding:1px 2px 1px 2px\">".htmlentities($row['name'])."</td>
				<td style=\"padding:1px 2px 1px 2px; text-transform:uppercase;\">".$row['code']."</td>
				<td style=\"padding:1px 2px 1px 2px\">".$row['override']."</td>
				<td style=\"padding:1px 2px 1px 2px\">".$row['size']."</td>
				<td style=\"padding:1px 2px 1px 2px\">".$row['type']."</td>
				<td style=\"padding:1px 2px 1px 2px;text-transform:uppercase;\">".$row2['role']."</td>
				<td style=\"padding:1px 2px 1px 2px\">".$row2['qt']."</td>
				<td style=\"padding:1px 2px 1px 2px\">".$row2['shifts']."</td>
				<td style=\"padding:1px 2px 1px 2px\">";

		foreach($row['roster'] as $name) {
			if($name == $crewmember['name']) $namelist .= "<span style=\"background-color:#55cc55;\">".$name."</span>, ";
			else $namelist .= $name . ", ";
		}

		if(strlen($namelist)>0) $namelist = substr($namelist,0,strlen($namelist)-2); //Strip last comma & space
		echo $namelist;
		echo "	</td>
				<td>".$row['fuel_models']."</td>
				<td style=\"text-transform:none;\">".$short_desc."</td>
				</tr>\n";

	} //END foreach($incident_array as $row)

	echo "</table>";

}


//==========================================================================================================
function add_line_form($php_self) {

	//Get current roster
	$result = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . $_SESSION['incident_year'] . "'
									ORDER BY name");

	$roster_menu = "<table style=\"width:100%\"><tr><td style=\"width:auto\">Name</td><td style=\"width:40px\">Role</td><td style=\"width:30px\">Q/T</td><td style=\"width:20px\">Shifts</td></tr>\n";

	while($roster_row = $result->fetch_assoc()) {
		if($_SESSION['form_field_qt-'.$roster_row['id']] == "q") {
			$q_selected = " selected";
			$t_selected = "";
		}
		elseif($_SESSION['form_field_qt-'.$roster_row['id']] == "t") {
			$q_selected = "";
			$t_selected = " selected";
		}
		else {
			$q_selected = "";
			$t_selected = "";
		}
		
		if($_SESSION['form_field_'.$roster_row['id']] == "on") $name_checked = " checked";
		else $name_checked = "";
		
		$qt_menu =	"<SELECT name=\"qt-".$roster_row['id']."\" class=\"entry_cell\" style=\"width:39px\">\n"
				.	"<option value=\"q\"".$q_selected.">Q</option>\n"
				.	"<option value=\"t\"".$t_selected.">T</option>\n"
				.	"</SELECT>\n";

		$roster_menu .= 	"<tr><td><input type=\"checkbox\" name=\"".$roster_row['id']."\" class=\"chkbox\"".$name_checked
					.	">".htmlentities($roster_row['name'])."</td>\n"
					.	"<td><input type=\"text\" class=\"entry_cell\" style=\"width:40px\" name=\"role-".$roster_row['id']."\" value=\"".$_SESSION['form_field_role-'.$roster_row['id']]."\"></td>"
					.	"<td>".$qt_menu."</td>"
					.	"<td><input type=\"text\" class=\"entry_cell\" style=\"width:20px\" name=\"shifts-".$roster_row['id']."\" value=\"".$_SESSION['form_field_shifts-'.$roster_row['id']]."\"></td></tr>\n";
	}

	$roster_menu .= "</table>\n";

	$months = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
	$month_menu = "<select name=\"month\" class=\"entry_cell\" style=\"width:55px\">\n";

	foreach($months as $num=>$text) {
		$month_menu .= "<option value=\"".$num."\"";
		if($num == $_SESSION['form_field1']) $month_menu .=" SELECTED";
		$month_menu .= ">".$text."</option>\n";
	}

	$month_menu .= "</select>\n";
	$day_menu = "	<select name=\"day\" class=\"entry_cell\" style=\"width:45px\">\n";

	for($i=1;$i<=31;$i++) {
					$day_menu .= "<option value=\"".$i."\"";
					if($_SESSION['form_field2'] == $i) $day_menu .= " SELECTED";
					$day_menu .= ">".$i."</option>\n";
	}

	$day_menu .= "</select>\n";
	$event_type_menu = 	 "<input type=\"radio\" name=\"event_type\" value=\"wf\" checked=\"checked\" style=\"vertical-align:middle\">WF<br>\n"
						."<input type=\"radio\" name=\"event_type\" value=\"rx\" style=\"vertical-align:middle\">Rx\n";

	$table_1_headers = "<tr>\n"
					."	<th>Month</th>\n"
					."	<th>Day</th>\n"
					."	<th>Year</th>\n"
					."	<th>Event Type</th>\n"
					."	<th>Inc. #<br><span style=\"font-size:9px;font-weight:normal;\">(OR-OCF-000123)</span></th>\n"
					."	<th>Inc. Name</th>\n"
					."	<th>Charge Code</th>\n"
					."	<th>Override</th>\n"
					."	<th>Acres</th>\n"
					."	<th>ICT</th>\n"
					."</tr>\n";

	$table_2_headers = "<tr>\n"
					."	<th colspan=\"4\">Roster</th>\n"
					."	<th>Fuel Models</th>\n"
					."	<th>Description</th>\n"
					."</tr>\n";

	echo "	<form action=\"". $php_self . "?function=add_line\" method=\"post\">
			<table style=\"width:600px; margin:0 auto 0 auto;\">\n"
			. $table_1_headers
			."<tr><td class=\"form\" style=\"width:55px;\">".$month_menu."</td>\n"
				."<td class=\"form\" style=\"width:45px;\">".$day_menu."</td>\n"
				."<td class=\"form\" style=\"width:45px;\"><input type=\"text\" class=\"entry_cell\" style=\"width:50px\" name=\"year\" id=\"year\" value=\"".$_SESSION['incident_year']."\" onChange=\"refresh_date();\"></td>\n"
				."<td class=\"form\" style=\"width:60px;\">".$event_type_menu."</td>\n"
				."<td class=\"form\" style=\"width:100px;\"><input type=\"text\" name=\"number\" value=\"".$_SESSION['form_field3']."\" class=\"entry_cell\" style=\"width:100px;text-transform:uppercase;\"></td>\n"
				."<td class=\"form\" style=\"width:80px;\"><input type=\"text\" name=\"name\" value=\"".$_SESSION['form_field4']."\" class=\"entry_cell\" style=\"width:80px;\"></td>\n"
				."<td class=\"form\" style=\"width:55px;\"><input type=\"text\" name=\"code\" value=\"".$_SESSION['form_field5']."\" class=\"entry_cell\" style=\"width:55px;text-transform:uppercase;\"></td>\n"
				."<td class=\"form\" style=\"width:70px;\"><input type=\"text\" name=\"override\" value=\"".$_SESSION['form_field6']."\" class=\"entry_cell\" style=\"width:70px;\"></td>\n"
				."<td class=\"form\" style=\"width:50px;\"><input type=\"text\" name=\"size\" value=\"".$_SESSION['form_field7']."\" class=\"entry_cell\" style=\"width:55px;\"></td>\n"
				."<td class=\"form\" style=\"width:40px;\"><input type=\"text\" name=\"type\" value=\"".$_SESSION['form_field8']."\" class=\"entry_cell\" style=\"width:40px;\"></td>\n"
			."</tr></table>\n<br>\n";

	if($_SESSION['form_field_grass'] == "on") $grass_check = " checked";
	else $grass_check = "";
	if($_SESSION['form_field_shrub'] == "on") $shrub_check = " checked";
	else $shrub_check = "";
	if($_SESSION['form_field_timber'] == "on") $timber_check = " checked";
	else $timber_check = "";
	if($_SESSION['form_field_slash'] == "on") $slash_check = " checked";
	else $slash_check = "";
	if($_SESSION['form_field_rocks'] == "on") $rocks_check = " checked";
	else $rocks_check = "";
	
	echo "<table style=\"width:600px; margin:0 auto 0 auto;\">\n"
				. $table_2_headers
				."<tr><td colspan=\"4\" rowspan=\"3\" class=\"form\" style=\"\">" . $roster_menu . "</td>\n"
				."<td class=\"form\" style=\"width:100px;\">\n"
				."	<table style=\"margin:0; padding:0; width:100px;\">\n"
					."	<tr><td><input type=\"checkbox\" name=\"fuel_model_1\" class=\"chkbox\"".$grass_check.">1 - Grass</td></tr>\n"
					."	<tr><td><input type=\"checkbox\" name=\"fuel_model_2\" class=\"chkbox\"".$shrub_check.">2 - Shrub</td></tr>\n"
					."	<tr><td><input type=\"checkbox\" name=\"fuel_model_3\" class=\"chkbox\"".$timber_check.">3 - Timber</td></tr>\n"
					."	<tr><td><input type=\"checkbox\" name=\"fuel_model_4\" class=\"chkbox\"".$slash_check.">4 - Slash</td></tr>\n"
					."	<tr><td><input type=\"checkbox\" name=\"fuel_model_5\" class=\"chkbox\"".$rocks_check.">5 - Rocks</td></tr>\n"
				."	</table>\n"
				."</td>\n"
				."<td class=\"form\" style=\"width:200px;\"><textarea rows=\"8\" cols=\"20\" name=\"description\" style=\"width:200px;font-size:11px;\">".$_SESSION['form_field9']."</textarea></td>\n"
			."</tr>\n";

	echo "<tr><td colspan=\"2\">"
		."	<table style=\"width:100%;\"><tr><th style=\"width:100px;\">Coordinates</th></tr>\n"
		."			<tr><td style=\"width:100px;\">Latitude (DD&deg; MM.MMMM')<br />"
		."					D:<input type=\"text\" name=\"latitude_degrees\" class=\"entry_cell\" style=\"width:30px;display:inline;\" value=\"".$_SESSION['form_field_latitude_degrees']."\">&nbsp;&nbsp;"
		."					M:<input type=\"text\" name=\"latitude_minutes\" class=\"entry_cell\" style=\"width:50px;display:inline;\" value=\"".$_SESSION['form_field_latitude_minutes']."\">&nbsp;&nbsp;"
		//."					S:<input type=\"text\" name=\"latitude_seconds\" class=\"entry_cell\" style=\"width:30px;display:inline;\" value=\"".$_SESSION['form_field_latitude_seconds']."\">&nbsp;&nbsp;"
		."			</td></tr>\n"
		."			<tr><td>Longitude (DD&deg; MM.MMMM')<br />"
		."					D:<input type=\"text\" name=\"longitude_degrees\" class=\"entry_cell\" style=\"width:30px;display:inline;\" value=\"".$_SESSION['form_field_longitude_degrees']."\">&nbsp;&nbsp;"
		."					M:<input type=\"text\" name=\"longitude_minutes\" class=\"entry_cell\" style=\"width:50px;display:inline;\" value=\"".$_SESSION['form_field_longitude_minutes']."\">&nbsp;&nbsp;"
		//."					S:<input type=\"text\" name=\"longitude_seconds\" class=\"entry_cell\" style=\"width:30px;display:inline;\" value=\"".$_SESSION['form_field_longitude_seconds']."\">&nbsp;&nbsp;"
		."			</td></tr>";
		
		
	echo "<tr><td colspan=\"2\">"
		."	<table style=\"width:100%;\"><tr><th style=\"width:100px;\">Attached Files</th></tr>\n"
		."			<tr><td style=\"width:100px;\">You must save this incident first before attaching files.<br />"
		."  </table>\n"
		."</td></tr>";

	echo "	</table>"
		."</td></tr>\n";
		
	echo "<tr><td class=\"form\" colspan=\"6\"><hr style=\"border:none; height:2px; width:100%; color:#555; background-color:#555;margin:0 auto 0 0;\"></td></tr>\n"
		."<tr>\n"
		."	<td class=\"form\" colspan=\"6\"><input type=\"submit\" value=\"Save This Incident\" style=\"font-size:15px;width:75px:height:20px;background-color:#bbddbb;border:2px solid #666;\"></td>\n"
		."</tr>\n"
		."</table>\n\n"
		."<input type=\"hidden\" name=\"status\" value=\"insert\">\n"
		."</form>\n<br>\n\n"
		."<table style=\"width:600px; margin:0 auto 0 auto;\">\n"
		."<tr><th>IC Types (WF)</th><th>Complexity Levels (Rx)</th></tr>\n"
		."<tr><td>TYPE A - National area command team assigned</td><td>Type 1</td></tr>\n"
		."<tr><td>TYPE 1 - National type 1 team assigned</td><td>Type 2</td></tr>\n"
		."<tr><td>TYPE 2 - Regional type 2 team assigned</td><td>Type 3</td></tr>\n"
		."<tr><td>TYPE 3 - Extended attack with multiple resources</td><td>&nbsp;</td></tr>\n"
		."<tr><td>TYPE 4 - Initial attack</td><td>&nbsp;</td></tr>\n"
		."<tr><td>TYPE 5 - Initial attack with very few resources</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
}


//==========================================================================================================
function add_line() {
	$error = '';
	$description = mydb::cxn()->real_escape_string($_POST['description']); 
	$fuel_model_list = "";
	$latitude_decimal = "";
	$longitude_decimal = "";
	if($_POST['latitude_degrees'] != "") {
		$_POST['longitude_degrees'] < 0 ? true : $_POST['longitude_degrees'] = $_POST['longitude_degrees'] * -1; // Longitude is negative in the western hemisphere
		$latitude_decimal = $_POST['latitude_degrees'] + $_POST['latitude_minutes']/60;
		$longitude_decimal= $_POST['longitude_degrees']+ $_POST['longitude_minutes']/60;
	}

	if($_POST['fuel_model_1'] == "on") $fuel_model_list .= "1,";
	if($_POST['fuel_model_2'] == "on") $fuel_model_list .= "2,";
	if($_POST['fuel_model_3'] == "on") $fuel_model_list .= "3,";
	if($_POST['fuel_model_4'] == "on") $fuel_model_list .= "4,";
	if($_POST['fuel_model_5'] == "on") $fuel_model_list .= "5,";

	if(strlen($fuel_model_list)>0) $fuel_model_list = substr($fuel_model_list,0,strlen($fuel_model_list)-1); //Strip last comma
	else $fuel_model_list = "";
	//else $error .= "You must select at least one fuel model<br>\n";

	$unix_date = strtotime($_POST['year']."-".$_POST['month']."-".$_POST['day']); //Convert date into unix timestamp

	//Check for at least one crewmember on the roster
	$need_crewmembers = 1;
	$result = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . $_POST['year'] . "'
									ORDER BY name");
	$roster_ids = array();
	while($row = $result->fetch_assoc()) {
		if($_POST[$row['id']] == "on") $need_crewmembers = 0;
		$roster_ids[] = $row['id'];
	}

	if($need_crewmembers) $error .= "You must select at least one crewmember<br>\n";

	//Check the rest of the fields
	if(!preg_match("/\b[a-zA-Z]{2}-\b[a-zA-Z0-9]{3,5}-\b[0-9]{2,6}/i",trim($_POST['number']))) $error .= "Incident number must be in the form: OR-OCF-123456 (You entered: ".$_POST['number'].")<br>\n";
	
	$size = preg_replace("/[^0-9.]/", "", $_POST['size']);
	
/*	if(!my_is_int(trim($_POST['number'])) || ($_POST['number']=='')) $error .= "Incident number must be a numeric value! (You entered: ".$_SESSION['form_field3'].")<br>\n";
	if(!preg_match("/\b[0-9a-zA-Z]{6}\b/i",$_POST['code'])) $error .= "P-Code must be 6 characters! (You entered: ".$_SESSION['form_field5'].")<br>\n";
	if(!preg_match("/\b[0-9]{4}\b/",$_POST['override'])) $error .= "Override Code must be a 4-digit number! (You entered: ".$_SESSION['form_field6'].")<br>\n";
	if(!preg_match('/\b[0-9]*\.?[0-9]+\b/',$size)) $error .= "Acreage must be a numeric value! (You entered: ".$_SESSION['form_field7'].")<br>\n";
	if(!preg_match('/\b[1-5]{1}\b/',$_POST['type'])) $error .= "ICT (Management Type) must be a numeric value, 1 - 5 (You entered: ".$_SESSION['form_field8'].")<br>\n";
*/

	if($error == '') {
		$insert_query = "insert into incidents (date, event_type, number, name, code, override, size, type, fuel_models, description, latitude_degrees, latitude_minutes, longitude_degrees, longitude_minutes)
					values (from_unixtime(".$unix_date."),
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['event_type'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['number'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['name'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['code'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['override'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($size)))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['type'])))."',
							'".$fuel_model_list."',
							'".$description."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['latitude_degrees'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['latitude_minutes'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['longitude_degrees'])))."',
							'".mydb::cxn()->real_escape_string(strtolower(trim($_POST['longitude_minutes'])))."')";
		mydb::cxn()->query($insert_query) or die("Error adding item to the incidents database: " . mydb::cxn()->error);

		$incident_idx = mydb::cxn()->insert_id;  //Get the idx of the new 'incidents' entry


		//Get current roster
		$result = mydb::cxn()->query("	SELECT firstname, lastname, concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
										FROM crewmembers inner join roster
										ON crewmembers.id = roster.id
										WHERE roster.year like '" . $_SESSION['incident_year'] . "'");

		$roster_string = '';
		$max_shifts = 0; //Record the max number of shifts spent on this incident by any crewmember
		while($row = $result->fetch_assoc()) {
			if($_POST[$row['id']] == "on") {
				if($_POST['shifts-'.$row['id']] == '') $shifts = 'null';
				else {
					$shifts = $_POST['shifts-'.$row['id']];
					if($shifts > $max_shifts) $max_shifts = $shifts;
				}

				$query = "insert into incident_roster (idx, crewmember_id, role, qt, shifts)
						values (".$incident_idx.",".$row['id'].",'".$_POST['role-'.$row['id']]."','".$_POST['qt-'.$row['id']]."',".$shifts.")";
				mydb::cxn()->query($query) or die("Error adding incident roster: ".$query." -- : -- ".mydb::cxn()->error);

				$roster_string .= $row['name']." (".strtoupper($_POST['role-'.$row['id']])." (".strtoupper($_POST['qt-'.$row['id']])."), ".$shifts." shifts)\n";
			}
		}


		//Add this new incident to the Google Calendar
/*
		if(trim($_POST['name']) != '') $g_title = ucwords(trim($_POST['name']));
		else $g_title = strtoupper(trim($_POST['number']));

		$g_start_date =date('Y-m-d',$unix_date);
		$g_end_date = date('Y-m-d',mktime(0, 0, 0, date("m",$unix_date)  , date("d",$unix_date)+$max_shifts, date("Y",$unix_date)));

		if(strtolower(trim($_POST['name'])) != "") $g_fire_name = " (".ucwords(trim($_POST['name'])).")";
		else $g_fire_name = "";

		$g_description =	 "Incident: ".strtoupper(trim($_POST['number']))
							.$g_fire_name.",\n"
							.strtoupper(trim($_POST['code']))." / "
							.strtolower(trim($_POST['override'])).",\n"
							.strtolower(trim($_POST['size']))." Acres,\n"
							."Complexity: ".strtolower(trim($_POST['type']))."\n\n"
							.$description."\n\n"
							.$roster_string;
		
		if($latitude_decimal != "") $g_where = $latitude_decimal . " " . $longitude_decimal;
		else $g_where = "";
		
		$new_cal_id = g_cal_createEvent (g_cal_authenticate(), $g_title, $g_description, $g_where, $g_start_date,'0', $g_end_date,'0','-08');
		$result = mydb::cxn()->query("UPDATE incidents SET g_cal_eventUrl = \"".$new_cal_id."\" WHERE idx = ".$incident_idx);
*/

		$_SESSION['form_field1'] = '';
		$_SESSION['form_field2'] = '';
		$_SESSION['form_field3'] = '';
		$_SESSION['form_field4'] = '';
		$_SESSION['form_field5'] = '';
		$_SESSION['form_field6'] = '';
		$_SESSION['form_field7'] = '';
		$_SESSION['form_field8'] = '';
		$_SESSION['form_field9'] = '';
		$_SESSION['form_field10'] = '';
		$_SESSION['form_field11'] = '';
		$_SESSION['form_field12'] = '';
		$_SESSION['form_field13'] = '';
		$_SESSION['form_field14'] = '';
		$_SESSION['form_field15'] = '';
		$_SESSION['form_field16'] = '';
		$_SESSION['form_field_latitude_degrees'] = '';
		$_SESSION['form_field_latitude_minutes'] = '';
		$_SESSION['form_field_latitude_seconds'] = '';
		$_SESSION['form_field_longitude_degrees'] = '';
		$_SESSION['form_field_longitude_minutes'] = '';
		$_SESSION['form_field_longitude_seconds'] = '';
		$_SESSION['form_field_grass'] = '';
		$_SESSION['form_field_shrub'] = '';
		$_SESSION['form_field_timber'] = '';
		$_SESSION['form_field_slash'] = '';
		$_SESSION['form_field_rocks'] = '';
		
		foreach($roster_ids as $id) {
			$_SESSION['form_field_'.$id] = '';
			$_SESSION['form_field_qt-'.$id] = '';
			$_SESSION['form_field_shifts-'.$id] = '';
			$_SESSION['form_field_role-'.$id] = '';
			
		} //End foreach($roster_ids as $id)

		echo "<span class=\"highlight1\" style=\"display:block\">Incident successfully added</span><br />";

	}//END if($error == 0)

	else {
		echo "<span class=\"highlight1\" style=\"display:block\">".$error."</span><br />";
		//Repopulate form fields with current values to make it easy to correct
		$_SESSION['form_field1'] = $_POST['month'];
		$_SESSION['form_field2'] = $_POST['day'];
		$_SESSION['form_field3'] = htmlentities($_POST['number']);
		$_SESSION['form_field4'] = htmlentities($_POST['name']);
		$_SESSION['form_field5'] = htmlentities($_POST['code']);
		$_SESSION['form_field6'] = htmlentities($_POST['override']);
		$_SESSION['form_field7'] = htmlentities($_POST['size']);
		$_SESSION['form_field8'] = htmlentities($_POST['type']);
		$_SESSION['form_field9'] = htmlentities($_POST['description']);
		//$_SESSION['form_field10'] = htmlentities($_POST['event_type']); //Unnecessary, included for completeness
		$_SESSION['form_field_latitude_degrees'] = htmlentities($_POST['latitude_degrees']);
		$_SESSION['form_field12'] = htmlentities($_POST['latitude_minutes']);
		//$_SESSION['form_field_latitude_seconds'] = htmlentities($_POST['latitude_seconds']);
		$_SESSION['form_field_longitude_degrees'] = htmlentities($_POST['longitude_degrees']);
		$_SESSION['form_field15'] = htmlentities($_POST['longitude_minutes']);
		//$_SESSION['form_field_longitude_seconds'] = htmlentities($_POST['longitude_seconds']);
		$_SESSION['form_field_grass'] = htmlentities($_POST['fuel_model_1']);
		$_SESSION['form_field_shrub'] = htmlentities($_POST['fuel_model_2']);
		$_SESSION['form_field_timber'] = htmlentities($_POST['fuel_model_3']);
		$_SESSION['form_field_slash'] = htmlentities($_POST['fuel_model_4']);
		$_SESSION['form_field_rocks'] = htmlentities($_POST['fuel_model_5']);
		
		foreach($roster_ids as $id) {
			$_SESSION['form_field_'.$id] = htmlentities($_POST[$id]);
			$_SESSION['form_field_qt-'.$id] = htmlentities($_POST['qt-'.$id]);
			$_SESSION['form_field_shifts-'.$id] = htmlentities($_POST['shifts-'.$id]);
			$_SESSION['form_field_role-'.$id] = htmlentities($_POST['role-'.$id]);
			
		} //End foreach($roster_ids as $id)
	}
	return;
}


//==========================================================================================================
function rm_line_form($idx, $php_self) {

	//Get current incident info
	$query = "	SELECT	incidents.idx,
					unix_timestamp(incidents.date) as date,
					incidents.event_type,
					incidents.number,
					incidents.name,
					incidents.code,
					incidents.override,
					incidents.size,
					incidents.type,
					incidents.fuel_models,
					incidents.description
				FROM incidents
				WHERE incidents.idx like '".$idx."'";

	$result = mydb::cxn()->query($query) or die("Error retrieving info to verify item delete: " . mydb::cxn()->error);
	$row = $result->fetch_assoc();

	$query_roster = "	SELECT CONCAT(crewmembers.firstname,' ',crewmembers.lastname) AS name
						FROM crewmembers INNER JOIN incident_roster ON crewmembers.id = incident_roster.crewmember_id
										INNER JOIN incidents ON incident_roster.idx = incidents.idx
						WHERE incident_roster.idx LIKE '".$idx."' ORDER BY name";
	$roster_result = mydb::cxn()->query($query_roster) or die("dB query failed (Retrieving incident_roster): " . mydb::cxn()->error);

	// Shorten notes to 25 characters to save space on screen
	$full_desc = htmlentities($row['description']);
	$short_desc = substr($full_desc,0,25);

	if(strlen($full_desc) > strlen($short_desc)) $short_desc .= "...";

	$table_headers = "	<tr>  <th>Date</th>
						<th>Event Type</th>
						<th>Incident #</th>
						<th>Name</th>
						<th>Charge Code</th>
						<th>Override</th>
						<th>Size</th>
						<th>Type</th>
						<th>Roster</th>
						<th>Fuel Models</th>
						<th>Description</th>
						</tr>\n";

	echo "	<form action=\"".$php_self."?function=rm_line\" method=\"POST\">
			<table>\n";

	echo $table_headers;

	echo "	<td>".date('d-M-Y',$row['date'])."</td>
			<td style=\"text-transform:uppercase;\">".$row['event_type']."</td>
			<td style=\"text-transform:uppercase;\">".htmlentities($row['number'])."</td>
			<td>".htmlentities($row['name'])."</td>
			<td style=\"text-transform:uppercase;\">".$row['code']."</td>
			<td>".$row['override']."</td>
			<td>".$row['size']."</td>
			<td>".$row['type']."</td>
			<td>";

	while($roster = $roster_result->fetch_assoc()) {
		$namelist .= $roster['name'] . ", ";
	}

	if(strlen($namelist)>0) $namelist = substr($namelist,0,strlen($namelist)-2); //Strip last comma & space

	echo $namelist;
	echo "	</td>
			<td>".$row['fuel_models']."</td>
			<td style=\"text-transform:none;\">".$short_desc."</td>
			</tr>\n";

	echo "<tr>	<td><input type=\"submit\" value=\"Delete This Incident\" style=\"font-size:15px;font-weight:bold;width:80px:background-color:#396;border:2px solid #666;\"></td>
				<td colspan=\"11\">&nbsp;</td>
		</tr>
		</table>

		<input type=\"hidden\" name=\"status\" value=\"remove\">
		<input type=\"hidden\" name=\"idx\" value=\"".$idx."\">

		</form>\n";

	echo "	<br><br><p style=\"font-size:12px;\">
			This operation cannot be undone!<br>
			Please make sure that you are deleting the correct entry.</p>";
}


//==========================================================================================================
function rm_line() {
	
//	$result = mydb::cxn()->query("SELECT g_cal_eventUrl FROM incidents WHERE idx = ".$_POST['idx']);
//	$row = $result->fetch_assoc();
//	$eventUrl = $row['g_cal_eventUrl'];
	
	$result = mydb::cxn()->query("DELETE from incidents WHERE idx like '".$_POST['idx']."'") or die("Error removing entry from 'incidents': " . mydb::cxn()->error);
	$result = mydb::cxn()->query("DELETE from incident_roster WHERE idx like '".$_POST['idx']."'") or die("Error removing entry from 'incident_history': " . mydb::cxn()->error);

//g_cal_deleteEventByUrl(g_cal_authenticate(), $eventUrl);
	
	echo "<span class=\"highlight1\" style=\"display:block\">Entry Removed.</span><br />";
}


//==========================================================================================================
function show_incident_details($idx, $php_self, $allow_edit) {

	//Get current entry info
	$query = "	SELECT	incidents.idx,
					unix_timestamp(incidents.date) as date,
					incidents.event_type,
					incidents.number,
					incidents.name,
					incidents.code,
					incidents.override,
					incidents.size,
					incidents.type,
					incidents.fuel_models,
					incidents.description,
					incidents.latitude_degrees,
					incidents.latitude_minutes,
					incidents.longitude_degrees,
					incidents.longitude_minutes

				FROM incidents
				WHERE incidents.idx like '".$idx."'";

	$result = mydb::cxn()->query($query) or die("Error retrieving incident details: " . mydb::cxn()->error);
	$row = $result->fetch_assoc();

	//Build incident roster
	$query_roster = "	SELECT CONCAT(crewmembers.firstname,' ',crewmembers.lastname) AS name
						FROM crewmembers INNER JOIN incident_roster ON crewmembers.id = incident_roster.crewmember_id
										INNER JOIN incidents ON incident_roster.idx = incidents.idx
						WHERE incident_roster.idx LIKE '".$idx."' ORDER BY name";

	$roster_result = mydb::cxn()->query($query_roster) or die("dB query failed (Retrieving incident_roster): " . mydb::cxn()->error);

	$table_headers = "	<tr>	<th>Date</th>
						<th>Event Type</th>
						<th>Incident #</th>
						<th>Name</th>
						<th>Charge Code</th>
						<th>Override</th>
						<th>Size</th>
						<th>Type</th>
						<th>Roster</th>
						<th>Fuel Models</th>
						<th>Description</th>";

	if($allow_edit) $table_headers .= "	<th>&nbsp;</th>";
	$table_headers .= "	\n</tr>\n";


	echo "<table>\n";
	echo $table_headers;
	echo "<tr class=\"evn\"><td>".date('d-M-Y',$row['date'])."</td>
					<td style=\"text-transform:uppercase;\">".$row['event_type']."</td>
					<td style=\"text-transform:uppercase;\">".htmlentities($row['number'])."</td>
					<td>".htmlentities($row['name'])."</td>
					<td style=\"text-transform:uppercase;\">".$row['code']."</td>
					<td>".$row['override']."</td>
					<td>".$row['size']."</td>
					<td>".$row['type']."</td>
					<td>";

	while($roster = $roster_result->fetch_assoc()) {
		$namelist .= $roster['name'] . ", ";
	}

	if(strlen($namelist)>0) $namelist = substr($namelist,0,strlen($namelist)-2); //Strip last comma & space

	echo $namelist;

	echo "</td>\n"
		."<td>".$row['fuel_models']."</td>\n"
		."<td style=\"text-transform:none;\">".htmlentities($row['description'])."</td>\n";

	if($allow_edit) echo "<td><a href=\"".$php_self."?function=rm_line&idx=".$idx."\" style=\"font-size:9px\">delete</a></td>\n";

	echo "</tr>\n"
		."</table>";
}


//==================================================================================================================================
function edit_line_form($idx, $php_self) {

	//Build a roster of personnel who were on this incident
	$query = "	SELECT crewmember_id,role,qt,shifts
				FROM incident_roster
				WHERE idx like '".$idx."'";

	$result = mydb::cxn()->query($query) or die("Error retrieving incident roster: " . mydb::cxn()->error);

	while($inc_roster_row = $result->fetch_assoc()) {
		$temp_id[]		= $inc_roster_row['crewmember_id'];
		$temp_role[]	= $inc_roster_row['role'];
		$temp_qt[]		= $inc_roster_row['qt'];
		$temp_shifts[]	= $inc_roster_row['shifts'];
	}

	$inc_roster['id']		= $temp_id;
	$inc_roster['role']		= $temp_role;
	$inc_roster['qt']		= $temp_qt;
	$inc_roster['shifts']	= $temp_shifts;

	//Get current entry info
	$query = "	SELECT	incidents.idx,
					unix_timestamp(incidents.date) as date,
					incidents.event_type,
					incidents.number,
					incidents.name,
					incidents.code,
					incidents.override,
					incidents.size,
					incidents.type,
					incidents.fuel_models,
					incidents.description,
					incidents.latitude_degrees,
					incidents.latitude_minutes,
					incidents.longitude_degrees,
					incidents.longitude_minutes
				FROM incidents

				WHERE incidents.idx like '".$idx."'";

	$result = mydb::cxn()->query($query) or die("Error retrieving incident details: " . mydb::cxn()->error);
	$row = $result->fetch_assoc();

	$_SESSION['form_field1'] = date('n',$row['date']);
	$_SESSION['form_field2'] = date('j',$row['date']);
	$_SESSION['incident_year']=date('Y',$row['date']);
	$_SESSION['form_field3'] = $row['number'];
	$_SESSION['form_field4'] = $row['name'];
	$_SESSION['form_field5'] = $row['code'];
	$_SESSION['form_field6'] = $row['override'];
	$_SESSION['form_field7'] = $row['size'];
	$_SESSION['form_field8'] = $row['type'];
	$_SESSION['form_field9'] = $row['description'];
	$_SESSION['form_field10']= $row['event_type'];
	$_SESSION['form_field11'] = $row['latitude_degrees'];
	$_SESSION['form_field12'] = $row['latitude_minutes']; //Should be formatted as decimal minutes
	//$_SESSION['form_field13'] = $row['latitude_seconds'];
	$_SESSION['form_field14'] = $row['longitude_degrees'];
	$_SESSION['form_field15'] = $row['longitude_minutes']; //Should be formatted as decimal minutes
	//$_SESSION['form_field16'] = $row['longitude_seconds'];

	$fuel_models = explode(',',$row['fuel_models']);

	$latitude_decimal = "";
	$longitude_decimal = "";
	if($row['latitude_degrees'] != "") {
		$row['longitude_degrees'] < 0 ? true : $row['longitude_degrees'] = $row['longitude_degrees'] * -1; // Longitude is negative in the western hemisphere
		$latitude_decimal = $row['latitude_degrees'] + $row['latitude_minutes']/60;
		$longitude_decimal= $row['longitude_degrees']- $row['longitude_minutes']/60;
	}
	
	//Get attached files
	$query = "SELECT id, file_path, file_description FROM incident_files WHERE incident_id = ".$idx;
	$result = mydb::cxn()->query($query);
	while($row = $result->fetch_assoc()) {
		$attached_files[] = array("id"=>$row['id'], "path"=>$row['file_path'], "description"=>$row['file_description']);
	}
	//Get current crew roster (everyone on the crew this season - the 'incident roster' is a subset of this list)
	$result = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . $_SESSION['incident_year'] . "'
									ORDER BY name");

	$roster_menu = "<table style=\"width:100%\"><tr><td style=\"width:auto\">Name</td><td style=\"width:40px\">Role</td><td style=\"width:40px\">Q/T</td><td style=\"width:20px\">Shifts</td></tr>\n";

	while($roster_row = $result->fetch_assoc()) {
		$roster_menu .= "<tr><td><input type=\"checkbox\" name=\"".$roster_row['id']."\" class=\"chkbox\"";

		if(in_array($roster_row['id'], $inc_roster['id'])) $roster_menu .= " checked"; //If this crewmember was on the incident, check their box

		if(array_search($roster_row['id'],$inc_roster['id']) === false) $roster_key = -1;
		else $roster_key = array_search($roster_row['id'],$inc_roster['id']);

		$qt_menu =	"<SELECT name=\"qt-".$roster_row['id']."\" class=\"entry_cell\" style=\"width:40px\">"
				.	"<option value=\"q\"";

		if(($roster_key != -1) && ($inc_roster['qt'][$roster_key] == "q")) $qt_menu .= " SELECTED";

		$qt_menu .=	">Q</option>\n"
				.	"<option value=\"t\"";

		if(($roster_key != -1) && ($inc_roster['qt'][$roster_key] == "t")) $qt_menu .= " SELECTED";

		$qt_menu .=	">T</option>\n"
				.	"</SELECT>\n";

		if($roster_key != -1) {
			$roster_menu .=	">".htmlentities($roster_row['name'])."</td>\n"
					  .	"<td><input type=\"text\" class=\"entry_cell\" style=\"width:40px;text-transform:uppercase;\" name=\"role-".$roster_row['id']."\" value=\"".$inc_roster['role'][$roster_key]."\"></td>"
					  .	"<td>".$qt_menu."</td>"
					  .	"<td><input type=\"text\" class=\"entry_cell\" style=\"width:20px\" name=\"shifts-".$roster_row['id']."\" value=\"".$inc_roster['shifts'][$roster_key]."\"></td></tr>\n";
		}
		else {
			$roster_menu .=	">".htmlentities($roster_row['name'])."</td>\n"
					  .	"<td><input type=\"text\" class=\"entry_cell\" style=\"width:40px;text-transform:uppercase;\" name=\"role-".$roster_row['id']."\" value=\"\"></td>"
					  .	"<td>".$qt_menu."</td>"
					  .	"<td><input type=\"text\" class=\"entry_cell\" style=\"width:20px\" name=\"shifts-".$roster_row['id']."\" value=\"\"></td></tr>\n";
		}
	}

	$roster_menu .= "</table>\n";

	$months = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
	$month_menu = "<select name=\"month\" class=\"entry_cell\" style=\"width:55px\">\n";

	foreach($months as $num=>$text) {
		$month_menu .= "<option value=\"".$num."\"";
		if($num == $_SESSION['form_field1']) $month_menu .=" SELECTED";
		$month_menu .= ">".$text."</option>\n";
	}

	$month_menu .= "</select>\n";
	$day_menu = "	<select name=\"day\" class=\"entry_cell\" style=\"width:45px\">\n";

	for($i=1;$i<=31;$i++) {
					$day_menu .= "<option value=\"".$i."\"";
					if($_SESSION['form_field2'] == $i) $day_menu .= " SELECTED";
					$day_menu .= ">".$i."</option>\n";
	}

	$day_menu .= "</select>\n";
	$event_type_menu = "<input type=\"radio\" name=\"event_type\" value=\"wf\"";

	if( ($_SESSION['form_field10'] == "wf") || ($_SESSION['form_field10'] == "")) $event_type_menu .= " checked=\"checked\"";

	$event_type_menu 	.=" style=\"vertical-align:middle\">WF<br>\n"
						."<input type=\"radio\" name=\"event_type\" value=\"rx\"";

	if($_SESSION['form_field10'] == "rx") $event_type_menu .= " checked=\"checked\"";

	$event_type_menu .= " style=\"vertical-align:middle\">Rx\n";

	$table_1_headers = "<tr>\n"
						."	<th>Month</th>\n"
						."	<th>Day</th>\n"
						."	<th>Year</th>\n"
						."	<th>Event Type</th>\n"
						."	<th>Inc. #<br><span style=\"font-size:9px;font-weight:normal;\">(OR-OCF-000123)</span></th>\n"
						."	<th>Inc. Name</th>\n"
						."	<th>Charge Code</th>\n"
						."	<th>Override</th>\n"
						."	<th>Acres</th>\n"
						."	<th>ICT</th>\n"
						."</tr>\n";


	$table_2_headers = "<tr>\n"
						."	<th colspan=\"4\">Roster</th>\n"
						."	<th>Fuel Models</th>\n"
						."	<th>Description</th>\n"
						."</tr>\n";

	echo "	<form enctype=\"multipart/form-data\" action=\"". $php_self . "?function=edit_line\" method=\"post\">
			<table style=\"width:600px; margin:0 auto 0 auto;\">\n"
		."<input type=\"hidden\" name=\"idx\" value=\"".$idx."\">\n"
		. $table_1_headers
		."<tr><td class=\"form\" style=\"width:55px;\">".$month_menu."</td>\n"
			."<td class=\"form\" style=\"width:45px;\">".$day_menu."</td>\n"
			."<td class=\"form\" style=\"width:45px;\"><input type=\"text\" class=\"entry_cell\" style=\"width:50px\" name=\"year\" id=\"year\" value=\"".$_SESSION['incident_year']."\" onChange=\"form.submit();\"></td>\n"
			."<td class=\"form\" style=\"width:50px;\">".$event_type_menu."</td>\n"
			."<td class=\"form\" style=\"width:100px;\"><input type=\"text\" name=\"number\" value=\"".$_SESSION['form_field3']."\" class=\"entry_cell\" style=\"width:100px;text-transform:uppercase;\"></td>\n"
			."<td class=\"form\" style=\"width:80px;\"><input type=\"text\" name=\"name\" value=\"".$_SESSION['form_field4']."\" class=\"entry_cell\" style=\"width:80px;\"></td>\n"
			."<td class=\"form\" style=\"width:55px;\"><input type=\"text\" name=\"code\" value=\"".$_SESSION['form_field5']."\" class=\"entry_cell\" style=\"width:55px;text-transform:uppercase;\"></td>\n"
			."<td class=\"form\" style=\"width:70px;\"><input type=\"text\" name=\"override\" value=\"".$_SESSION['form_field6']."\" class=\"entry_cell\" style=\"width:70px;\"></td>\n"
			."<td class=\"form\" style=\"width:50px;\"><input type=\"text\" name=\"size\" value=\"".$_SESSION['form_field7']."\" class=\"entry_cell\" style=\"width:55px;\"></td>\n"
			."<td class=\"form\" style=\"width:40px;\"><input type=\"text\" name=\"type\" value=\"".$_SESSION['form_field8']."\" class=\"entry_cell\" style=\"width:40px;\"></td>\n"
		."</tr></table>\n<br>\n";

	echo "<table style=\"width:600px; margin:0 auto 0 auto;\">\n"
				. $table_2_headers
				."<tr><td colspan=\"4\" rowspan=\"3\" class=\"form\" style=\"\">" . $roster_menu . "</td>\n"
				."<td class=\"form\" style=\"width:100px;\">\n"
				."	<table style=\"margin:0; padding:0; width:100px;\">\n";

			echo "	<tr><td><input type=\"checkbox\" name=\"fuel_model_1\" class=\"chkbox\"";
			if(in_array(1,$fuel_models)) echo " checked";
			echo ">1 - Grass</td></tr>\n";

			echo "	<tr><td><input type=\"checkbox\" name=\"fuel_model_2\" class=\"chkbox\"";
			if(in_array(2,$fuel_models)) echo " checked";
			echo ">2 - Shrub</td></tr>\n";

			echo "	<tr><td><input type=\"checkbox\" name=\"fuel_model_3\" class=\"chkbox\"";
			if(in_array(3,$fuel_models)) echo " checked";
			echo ">3 - Timber</td></tr>\n";

			echo "	<tr><td><input type=\"checkbox\" name=\"fuel_model_4\" class=\"chkbox\"";
			if(in_array(4,$fuel_models)) echo " checked";
			echo ">4 - Slash</td></tr>\n";

			echo "	<tr><td><input type=\"checkbox\" name=\"fuel_model_5\" class=\"chkbox\"";
			if(in_array(5,$fuel_models)) echo " checked";
			echo ">5 - Rocks</td></tr>\n";
			
			echo "<tr><td>&nbsp;</td></tr>\n";

			echo "	</table>\n"
				."</td>\n"
				."<td class=\"form\" style=\"width:200px;\"><textarea rows=\"8\" cols=\"20\" name=\"description\" style=\"width:200px;font-size:11px;\">".$_SESSION['form_field9']."</textarea><br /></td>\n"
			."</tr>\n";
			
	echo "<tr><td colspan=\"2\">"
		."	<table style=\"width:100%;\"><tr><th style=\"width:100px;\">Coordinates</th></tr>\n"
		."			<tr><td style=\"width:100px;\">Latitude (DD&deg; MM.MMMM')<br />"
		."					D:<input type=\"text\" name=\"latitude_degrees\" value=\"".$_SESSION['form_field11']."\" class=\"entry_cell\" style=\"width:30px;display:inline;\">&nbsp;&nbsp;"
		."					M:<input type=\"text\" name=\"latitude_minutes\" value=\"".$_SESSION['form_field12']."\" class=\"entry_cell\" style=\"width:50px;display:inline;\">&nbsp;&nbsp;"
		."			</td></tr>\n"
		."			<tr><td>Longitude (DD&deg; MM.MMMM')<br />"
		."					D:<input type=\"text\" name=\"longitude_degrees\" value=\"".$_SESSION['form_field14']."\" class=\"entry_cell\" style=\"width:30px;display:inline;\">&nbsp;&nbsp;"
		."					M:<input type=\"text\" name=\"longitude_minutes\" value=\"".$_SESSION['form_field15']."\" class=\"entry_cell\" style=\"width:50px;display:inline;\">&nbsp;&nbsp;"
		."			</td></tr>";
		
	if($latitude_decimal != "") {
		echo "<tr><td><a href=\"http://maps.google.com/maps?hl=en&q=".$latitude_decimal.",".$longitude_decimal."+(".str_replace(" ","+",$_SESSION['form_field4']).")&ll=".$latitude_decimal.",".$longitude_decimal."\" target=\"_blank\">View Map</a></td></tr>\n";
	}
		
	echo "	</table>"
		."</td></tr>\n";
	
	echo "<tr><td colspan=\"2\">"
		."	<table style=\"width:100%;\"><tr><th colspan=\"2\">Attached Files</th></tr>\n";
	
	echo "  <tr><td><form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n"
		."	<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000000\" />"
		."	Upload a new file (max filesize: 5MB)<br />"
		."	<input type=\"file\" name=\"uploadedfile\"><br />\n"
		."	Description:<input type=\"text\" name=\"file_description\" style=\"width:100%;\"></form></td></tr>\n";
	if(count($attached_files) > 0) {
	  foreach($attached_files as $a_file) {
			echo "<tr><td><a href=\"".$a_file['path']."\">".$a_file['description']."</a></td>"
				."	<td><form name=\"delete_attached_file_form\" action=\"".$php_self."?function=delete_attached_file&idx=".$idx."\" method=\"post\">\n"
				."		<input type=\"hidden\" name=\"file_id\" value=\"".$a_file['id']."\">"
				."		<input type=\"submit\" value=\"Delete\"></form></td></tr>\n";
	  }
	}
	echo "  </table></form></td></tr>\n";
	
	echo "<tr><td class=\"form\" colspan=\"6\"><hr style=\"border:none; height:2px; width:100%; color:#555; background-color:#555;margin:0 auto 0 0;\"></td></tr>\n"
		."<tr>\n"
		."	<td class=\"form\" colspan=\"6\">"
		."		<input type=\"submit\" value=\"Save This Incident\" style=\"font-size:15px;width:75px:height:20px;background-color:#bbddbb;border:2px solid #666;margin-right:5px;\">"
		."		<a href=\"". $php_self . "?function=rm_line&idx=".$idx."\"><div style=\"font-size:12px;width:75px;height:20px;background-color:#dd3333;border:2px solid #666;padding:2px;text-align:center;color:black;display:inline;\">Delete</div></a>\n"
		."</td></tr>\n"
		."</table>\n\n"
		."<input type=\"hidden\" name=\"status\" value=\"edit\">\n"
		."</form>\n"
		."\n";

}


//==========================================================================================================
function edit_line($idx, $php_self) {

	$error = '';
	$description = mydb::cxn()->real_escape_string($_POST['description']);
	$fuel_model_list = "";

	if(isset($_POST['fuel_model_1']) && ($_POST['fuel_model_1'] == "on")) $fuel_model_list .= "1,";
	if(isset($_POST['fuel_model_2']) && ($_POST['fuel_model_2'] == "on")) $fuel_model_list .= "2,";
	if(isset($_POST['fuel_model_3']) && ($_POST['fuel_model_3'] == "on")) $fuel_model_list .= "3,";
	if(isset($_POST['fuel_model_4']) && ($_POST['fuel_model_4'] == "on")) $fuel_model_list .= "4,";
	if(isset($_POST['fuel_model_5']) && ($_POST['fuel_model_5'] == "on")) $fuel_model_list .= "5,";

	if(strlen($fuel_model_list)>0) $fuel_model_list = substr($fuel_model_list,0,strlen($fuel_model_list)-1); //Strip last comma
	else $error .= "You must select at least one fuel model<br>\n";

	$unix_date = strtotime($_POST['year']."-".$_POST['month']."-".$_POST['day']); //Convert date into unix timestamp
	
	$latitude_decimal = "";
	$longitude_decimal = "";
	if($_POST['latitude_degrees'] != "") {
		$_POST['longitude_degrees'] < 0 ? true : $_POST['longitude_degrees'] = $_POST['longitude_degrees'] * -1; // Longitude is negative in the western hemisphere
		$latitude_decimal = $_POST['latitude_degrees'] + $_POST['latitude_minutes']/60;
		$longitude_decimal= $_POST['longitude_degrees']+ $_POST['longitude_minutes']/60;
	}

	//Deal with uploaded files
	if($_FILES['uploadedfile']['name'] != "") {
	  $targets = format_filename(basename( $_FILES['uploadedfile']['name']));
	  $target_path = $targets['base'] . $targets['filename'];
	  if(trim($_POST['file_description']) == '') $file_description = basename($_FILES['uploadedfile']['name']);
	  else $file_description = mydb::cxn()->real_escape_string($_POST['file_description']);
	  
	  $status = check_uploaded_file($_FILES['uploadedfile']['tmp_name']); // $status['success'] (0,1) - $status['desc'] (text)
  
	  if($status['success']) {
  
		  if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			  $status['success'] = 0;
			  $status['desc'] = "Unable to accept file, try again later.<br>\n";
		  }
		  else {
			  // File successfully uploaded, now add an entry in the database
			  $result = mydb::cxn()->query("insert into incident_files(file_path,file_description, incident_id) "
										  ."values(\"assets/".$targets['filename']."\",\""
										  .$file_description."\","
										  .$idx.")")
				  or die("Saving file failed: " . mydb::cxn()->error);
		  }
	  }// end 'if($status['success'])'
	}
	
	//Check for at least one crewmember on the roster
	$need_crewmembers = 1;
	$result = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . $_POST['year'] . "'
									ORDER BY name");

	while($row = $result->fetch_assoc()) {
		if(isset($_POST[$row['id']]) && ($_POST[$row['id']] == "on")) $need_crewmembers = 0;
	}

	if($need_crewmembers) $error .= "You must select at least one crewmember<br>\n";

	//Check the rest of the fields
	if(!preg_match("/\b[a-zA-Z]{2}-\b[a-zA-Z0-9]{3,5}-\b[0-9]{6}/i",trim($_POST['number']))) $error .= "Incident number must be in the form: OR-OCF-123456 (You entered: ".$_POST['number'].")<br>\n";
/*	if(!preg_match("/\b[0-9a-zA-Z]{6}\b/i",$_POST['code'])) $error .= "P-Code must be 6 characters! (You entered: ".$_SESSION['form_field5'].")<br>\n";
	if(!preg_match("/\b[0-9]{4}\b/",$_POST['override'])) $error .= "Override Code must be a 4-digit number! (You entered: ".$_SESSION['form_field6'].")<br>\n";
	if(!preg_match('/\b[0-9]*\.?[0-9]+\b/',$_POST['size'])) $error .= "Acreage must be a numeric value! (You entered: ".$_SESSION['form_field7'].")<br>\n";
	if(!preg_match('/\b[1-5]{1}\b/',$_POST['type'])) $error .= "ICT (Management Type) must be a numeric value, 1 - 5 (You entered: ".$_SESSION['form_field8'].")<br>\n";
*/

	if($error == '') {
		$insert_query = "	UPDATE incidents
							SET date	= from_unixtime(".$unix_date."),
							event_type	= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['event_type'])))."',
							number		= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['number'])))."',
							name		= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['name'])))."',
							code		= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['code'])))."',
							override	= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['override'])))."',
							size		= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['size'])))."',
							type		= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['type'])))."',
							fuel_models = '".$fuel_model_list."',
							description = '".$description."',
							latitude_degrees = '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['latitude_degrees'])))."',
							latitude_minutes = '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['latitude_minutes'])))."',
							longitude_degrees= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['longitude_degrees'])))."',
							longitude_minutes= '".mydb::cxn()->real_escape_string(strtolower(trim($_POST['longitude_minutes'])))."'
							WHERE idx LIKE '".$idx."'";

		mydb::cxn()->query($insert_query) or die("Error updating item in the incidents database: " . mydb::cxn()->error);

		//Clear the current incident roster before setting the new roster
		$result = mydb::cxn()->query("	DELETE from incident_roster
										WHERE idx like '".$idx."'");

		//Get current crew roster & create new incident roster
		$result = mydb::cxn()->query("	SELECT firstname, lastname, concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
										FROM crewmembers inner join roster
										ON crewmembers.id = roster.id
										WHERE roster.year like '" . $_POST['year'] . "'");

		
		$max_shifts = 0;
		$roster_string = "";
		while($row = $result->fetch_assoc()) {
			if(isset($_POST[$row['id']]) && ($_POST[$row['id']] == "on")) {
				if($_POST['shifts-'.$row['id']] == '') $shifts = 'null';
				else {
					$shifts = $_POST['shifts-'.$row['id']];
					if($shifts > $max_shifts) $max_shifts = $shifts;
				}
				$query = "insert into incident_roster (idx, crewmember_id, role, qt, shifts)
							 values (".$idx.",".$row['id'].",'".$_POST['role-'.$row['id']]."','".$_POST['qt-'.$row['id']]."',".$shifts.")";

				mydb::cxn()->query($query) or die("Error adding incident roster: ".$query." -- : -- ".mydb::cxn()->error);
				
				$roster_string .= $row['name']." (".strtoupper($_POST['role-'.$row['id']])." (".strtoupper($_POST['qt-'.$row['id']])."), ".$shifts." shifts)\n";
			}
		}

		//Delete the Google Calendar entry for this event, then create a new Calendar event with the updated details
/*		if(trim($_POST['name']) != '') $g_title = ucwords(trim($_POST['name']));
		else $g_title = strtoupper(trim($_POST['number']));

		$g_start_date =date('Y-m-d',$unix_date);
		$g_end_date = date('Y-m-d',mktime(0, 0, 0, date("m",$unix_date)  , date("d",$unix_date)+$max_shifts, date("Y",$unix_date)));

		if(strtolower(trim($_POST['name'])) != "") $g_fire_name = " (".ucwords(trim($_POST['name'])).")";
		else $g_fire_name = "";

		$g_description =	 "Incident: ".strtoupper(trim($_POST['number']))
							.$g_fire_name.",\n"
							.strtoupper(trim($_POST['code']))." / "
							.strtolower(trim($_POST['override'])).",\n"
							.strtolower(trim($_POST['size']))." Acres,\n"
							."Complexity: ".strtolower(trim($_POST['type']))."\n\n"
							.$description."\n\n"
							.$roster_string;

		$result = mydb::cxn()->query("SELECT g_cal_eventUrl FROM incidents WHERE idx = ".$idx);
		$row = $result->fetch_assoc();
		$eventUrl = $row['g_cal_eventUrl'];
		
		//g_cal_deleteEventByUrl(g_cal_authenticate(), $eventUrl);
	
		if($latitude_decimal != "") $g_where = $latitude_decimal . " " . $longitude_decimal;
		else $g_where = "";
		
		$new_cal_id = g_cal_createEvent (g_cal_authenticate(), $g_title, $g_description, $g_where, $g_start_date,'0', $g_end_date,'0','-08');
		$result = mydb::cxn()->query("UPDATE incidents SET g_cal_eventUrl = \"".$new_cal_id."\" WHERE idx = ".$idx);
*/	
		$_SESSION['form_field1'] = '';
		$_SESSION['form_field2'] = '';
		$_SESSION['form_field3'] = '';
		$_SESSION['form_field4'] = '';
		$_SESSION['form_field5'] = '';
		$_SESSION['form_field6'] = '';
		$_SESSION['form_field7'] = '';
		$_SESSION['form_field8'] = '';
		$_SESSION['form_field9'] = '';
		$_SESSION['form_field10'] = '';
		$_SESSION['form_field11'] = '';
		$_SESSION['form_field12'] = '';
		$_SESSION['form_field13'] = '';
		$_SESSION['form_field14'] = '';
		$_SESSION['form_field15'] = '';
		$_SESSION['form_field16'] = '';

		echo "<span class=\"highlight1\" style=\"display:block\">Incident successfully updated!</span><br />";

	}//END if($error == 0)

	else {
		echo "<span class=\"highlight1\" style=\"display:block\">".$error."</span><br />";

		//Repopulate form fields with current values to make it easy to correct
		$_SESSION['form_field1'] = $_POST['month'];
		$_SESSION['form_field2'] = $_POST['day'];
		$_SESSION['form_field3'] = htmlentities($_POST['number']);
		$_SESSION['form_field4'] = htmlentities($_POST['name']);
		$_SESSION['form_field5'] = htmlentities($_POST['code']);
		$_SESSION['form_field6'] = htmlentities($_POST['override']);
		$_SESSION['form_field7'] = htmlentities($_POST['size']);
		$_SESSION['form_field8'] = htmlentities($_POST['type']);
		$_SESSION['form_field9'] = htmlentities($_POST['description']);
		//$_SESSION['form_field10'] = htmlentities($_POST['event_type']); //Handled
		$_SESSION['form_field11'] = htmlentities($_POST['latitude_degrees']);
		$_SESSION['form_field12'] = htmlentities($_POST['latitude_minutes']);
		$_SESSION['form_field13'] = htmlentities($_POST['latitude_seconds']);
		$_SESSION['form_field14'] = htmlentities($_POST['longitude_degrees']);
		$_SESSION['form_field15'] = htmlentities($_POST['longitude_minutes']);
		$_SESSION['form_field16'] = htmlentities($_POST['longitude_seconds']);
	}

	return;
}


//****************************************************************************************
function check_uploaded_file($tmp_name) {

	$pieces = explode('.',$_FILES['uploadedfile']['name']); //Get file extension
	$ext = $pieces[sizeof($pieces)-1];

	switch ($_FILES['uploadedfile']['error']) { //Check for HTML Errors
		case 1:
		case 2:
			$status['success'] = 0;
			$status['desc'] = "The file is too large.<br>\n";
			break;
		case 3: 
			$status['success'] = 0;
			$status['desc'] = 'File only partially uploaded'; 
			break; 
		case 4: 
			$status['success'] = 0;
			$status['desc'] = 'No file uploaded'; 
			break;
		default:
			$status['success'] = 1;
			$status['desc'] = "success";
	}

	if($_FILES['uploadedfile']['size'] > $_POST['MAX_FILE_SIZE']) { //Double-check filesize
		$status['success'] = 0;
		$status['desc'] = "The file is too large.<br>\n";
	}
/*		elseif (strtolower($ext) != "jpg") {
		$status['success'] = 0;
		$status['desc'] = "Only JPEG images are allowed (file extension '.jpg').<br>\n";
	}
*/
	return $status;
} // End 'check_uploaded_file()'


//****************************************************************************************
function format_filename($filename) {

	$base_path = "../assets/"; //Folder to store all uploaded files
	
	$extension = explode(".",$filename);
	$extension = strtolower($extension[count($extension)-1]);
	
	$filename = "inc_file_".date('Y-m-d_H-i-s').".".$extension;

	$target_path = array('base'=>$base_path,'filename'=>$filename);

	return $target_path;
}


//****************************************************************************************
function delete_attached_file($file_id) {
		$query = "SELECT file_path from incident_files where id = ".$file_id;
		$result = mydb::cxn()->query($query);
		if($row = $result->fetch_assoc()) {
			
		  $query = "DELETE from incident_files WHERE id = ".mydb::cxn()->real_escape_string($file_id);
		  $result = mydb::cxn()->query($query);
		  
		  //Delete the file
		  if(!@unlink("../".$row['file_path'])) {
			  $error = 1;
			  $error_msg = "Unable to delete ".$row['path'];
		  }
		}
	}
	
?>
