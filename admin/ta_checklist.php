<?php
	
	// This script provides a JSON interface for mobile apps to use to keep track of the status of each crewmember's paycheck.
	// A list of paychecks can be retrieved via GET for each payperiod, or a specific paycheck can have its status changed
	// via POST.  The status of a paycheck is boolean variable in the database that designates whether a specific paycheck
	// has been SUBMITTED in Paycheck8.
	$password = "siskiyou";
	require_once(__DIR__ . "/../classes/mydb_class.php");
	
	if(isset($_POST['password']) && $_POST['password'] == $password && isset($_POST['person_id']) && $_POST['person_id'] != "") {
		update_or_insert_paycheck($_POST['year'], $_POST['payperiod'], $_POST['person_id'], $_POST['status']);
	}
	elseif($_GET['password'] == $password) {
		// A 'password' must be submitted along with the GET request.  This is BARE MINIMUM 'security' since this data is not sensitive.
		if(isset($_GET['year']) && ($_GET['year'] != "")) $year = mydb::cxn()->real_escape_string($_GET['year']);
		else $year = false;
		if(isset($_GET['payperiod']) && ($_GET['payperiod'] != "")) $payperiod = mydb::cxn()->real_escape_string($_GET['payperiod']);
		else $payperiod = false;
		
		show_payperiod($year,$payperiod);
	}
	else return true;


	function show_payperiod($year, $payperiod) {
	  //If no payperiod is specified, the script returns a list of paychecks for the last payperiod that has data in the database.
	  //This function returns a JSON list of all crewmembers and the boolean status of their paycheck
	  //for the requested pay period.
	  //0 : This person's paycheck has not been submitted.
	  //1 : This person' paycheck has already been submitted.
	  if(!$year) $year_query = "(SELECT MAX(year) AS year FROM paychecks)";
	  else $year_query = "\"".trim($year)."\"";
	  
	  if(!$payperiod) {
		  $query = "	SELECT MAX( payperiod ) AS payperiod, year
					  FROM paychecks
					  WHERE YEAR = ".$year_query;
		  $result = mydb::cxn()->query($query);
		  $row = $result->fetch_assoc();
		  $year = $row['year'];
		  $payperiod = $row['payperiod'];
	  }
	  
	  $query = "SELECT	crewmembers.id AS person_id,
	  					crewmembers.lastname,
						crewmembers.firstname,
						roster.year,
						paychecks.id AS paycheck_id,
						paychecks.payperiod,
						paychecks.status
					FROM	crewmembers INNER JOIN roster
					ON	(crewmembers.id = roster.id) AND (roster.year = ".$year.")
					LEFT OUTER JOIN	paychecks
					ON	(paychecks.crewmember_id = crewmembers.id) AND (paychecks.year = roster.year) AND (paychecks.payperiod = ".$payperiod.")
					WHERE 1
					ORDER BY crewmembers.lastname, crewmembers.firstname";
	  $result = mydb::cxn()->query($query);
	  
	  $response['year'] = $row['year'];
	  $response['payperiod'] = $row['payperiod'];
	  $response['paycheck_status'] = array();
	  $paychecks = array();
	  while($row = $result->fetch_assoc()) {
		  $paychecks['person_id'] = $row['person_id'];
		  $paychecks['name'] = $row['lastname'].", ".$row['firstname'];
		  $paychecks['status'] = $row['status'];
		  
		  array_push($response['paycheck_status'], $paychecks);
	  }
	  
	  echo json_encode($response);
	} // END function show_payperiod()
	
	//******************************************************************
	function update_or_insert_paycheck($year, $payperiod, $person_id, $status) {
		//This function will change the STATUS of a specific paycheck.
		
		//Sanitize inputs
		$year = mydb::cxn()->real_escape_string($year);
		$payperiod = mydb::cxn()->real_escape_string($payperiod);
		$person_id = mydb::cxn()->real_escape_string($person_id);
		$status = mydb::cxn()->real_escape_string($status);
		
		//Check to see if this paycheck is already in the database
		$query = "	SELECT id FROM paychecks
					WHERE 	paychecks.year = ".$year."
					AND		paychecks.payperiod = ".$payperiod."
					AND		paychecks.crewmember_id = ".$person_id;
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		echo $query."<br /><br />\n\n";
		
		if($result->num_rows > 0) {
			// This paycheck is already in the database.  UPDATE the status.
			$query = "UPDATE paychecks SET status = ".$status." WHERE id = ".$row['id'];
			$result = mydb::cxn()->query($query);
		}
		else {
			// This paycheck is NOT in the database.  INSERT it with the requested status.
			$query = "	INSERT INTO paychecks (year,payperiod,crewmember_id,status)
						values(".$year.",".$payperiod.",".$person_id.",".$status.")";
			$result = mydb::cxn()->query($query);
		}
		
		echo $query."\n\n".mydb::cxn()->error;
		
	} // END function update_or_insert_paycheck()
?>