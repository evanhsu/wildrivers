<?php
	session_start();
//	require_once("../../includes/auth_functions.php");
	require_once("../../classes/mydb_class.php");
/*
	if(($_SESSION['logged_in'] == 1) && check_access("budget_helper")) {
		// Access Granted!
	}
	else {
		// Access Denied.
		header('location: http://www.siskiyourappellers.com/admin/index.php');
	}
*/
	
	if(!isset($_GET['splitID']) || !isset($_GET['elementID']) || !isset($_GET['nocache'])) throw new Exception('Invalid query');
	if(in_array($_GET['updateType'],array('reconciled','received'))) {
		if(!in_array($_GET['newStatus'],array('NULL','checked'))) throw new Exception('Invalid query');

		$table = "requisitions_split";

		$query = "UPDATE ".$table." SET ".$_GET['updateType']." = '".$_GET['newStatus']."' WHERE id = ".$_GET['splitID'];
		$result = mydb::cxn()->query($query);
		
		$query = "SELECT ".$_GET['updateType']." FROM ".$table." WHERE id = ".$_GET['splitID'];
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		if($row[$_GET['updateType']]=='checked') {
			$status = 'yes';
			$description = $_GET['updateType'];
		}
		else {
			$status='no';
			$description = "Not ".$_GET['updateType'];
		}
	}
	elseif($_GET['updateType'] == 'approved') {
		$table = "requisitions";
		$query = "UPDATE ".$table." SET approved_by = '".$_GET['newStatus']."' WHERE id = ".$_GET['splitID'];
		$result = mydb::cxn()->query($query);
		
		$query = "SELECT approved_by FROM ".$table." WHERE id = ".$_GET['splitID'];
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		
		if(!in_array($row['approved_by'], array('','NULL',NULL))) { //If 'approved_by' is a non-blank value...
			$status = 'yes';
			$description = "Approved by ".$row['approved_by'];
		}
		else {
			$status='no';
			$description = "Not approved";
		}
	}
	else throw new Exception('Invalid query');

	echo "images/".$_GET['updateType']."_".$status.".png;".$description;
?>
