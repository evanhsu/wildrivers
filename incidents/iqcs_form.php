<?php
	session_start();
	ini_set('include_path',ini_get('include_path').':../includes/'); //Unix
	$template = "../includes/IQCStemplate.pdf";
	
	if($_SESSION['logged_in'] == 1) {
		//require_once("../includes/inc_functions.php");
		require_once("../classes/mydb_class.php");
		require_once("../includes/merge_pdf.php");
	}
	else {
		include_once("../classes/Config.php");
		header('location: ' . ConfigService::getConfig()->app_url . '/admin/index.php');
	}

	$php_self = $_SERVER['PHP_SELF'];
	
	$crewmember_id = mydb::cxn()->real_escape_string($_GET['crewmember_id']);
	$incident_array = get_incidents($crewmember_id);


	//Lookup crewmember's name from the crewmember_id
	$result = mydb::cxn()->query("SELECT firstname, lastname FROM crewmembers WHERE id LIKE '".$crewmember_id."'") or die("Error retrieving crewmember name: ".mydb::cxn()->error);
	$row = $result->fetch_assoc();
	$firstname = $row['firstname'];
	$lastname = $row['lastname'];

	$fields = array();

	$fields['Year'] = $_SESSION['incident_year']; 
	$fields['First Name1'] = $firstname;
	$fields['First Name2'] = $fields['First Name1'];

	$fields['Last Name1'] = $lastname;
	$fields['Last Name2'] = $fields['Last Name1'];

	$fields['District1'] = "Siskiyou Rappellers";
	$fields['District2'] = $fields['District1'];

	$fields['Fitness Rating1'] = "Arduous";
	$fields['Fitness Rating2'] = $fields['Fitness Rating1'];

	$row_count = 1;
	foreach($incident_array as $row) {
		$result = mydb::cxn()->query("	SELECT role,qt,shifts
						FROM incident_roster
						WHERE idx like '".$row['idx']."' and crewmember_id like '".$crewmember_id."'");

		$row2 = $result->fetch_assoc();
		
		$row['fuel_models'] = str_replace(array('1','2','3','4','5'),array("G","B","T","S"," "),$row['fuel_models']); // Convert Siskiyou fuel 
		
		$fields["Job Code".$row_count] = strtoupper($row2['role'])."(".strtoupper($row2['qt']).")";
		$fields["Type of Incident".$row_count] = strtoupper($row['event_type']);
		$fields["Date".$row_count] = date("m/d/Y",$row['date']);
		$fields["State".$row_count] = strtoupper(substr($row['number'],0,2));
		$fields["Operational Periods".$row_count] = $row2['shifts'];
		$fields["Management Type".$row_count] = $row['type'];
		$fields["Fuel Type".$row_count] = $row['fuel_models'];
		$fields["Fire Size".$row_count] = convert_size($row['size']);
		$fields["Incident Name".$row_count] = strtoupper($row['name']);
		$fields["Incident Order #".$row_count] = strtoupper($row['number']);

		
		$row_count++;
		
	} //END foreach($incident_array as $row)

	mergePDF($template,$fields,$_SESSION['incident_year']."-IQCS-Update_".$firstname."_".$lastname.".pdf");
	//print_r($fields);


function convert_size($acres) {
	if($acres <= 0.25) $letter = "A";
	elseif(($acres > 0.25) && ($acres < 10)) $letter = "B";
	elseif(($acres >= 10) && ($acres < 100)) $letter = "C";
	elseif(($acres >= 100)&& ($acres < 300)) $letter = "D";
	elseif(($acres >= 300)&& ($acres < 1000)) $letter = "E";
	elseif(($acres >=1000) && ($acres < 5000)) $letter = "F";
	else $letter = "G";
	
	return $letter;
}

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
?>
