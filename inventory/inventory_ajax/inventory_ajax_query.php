<?php
	session_start();
//	require_once("../includes/auth_functions.php");
	require_once("../../classes/mydb_class.php");
	require_once("../../includes/inv_functions.php");
/*
	if(($_SESSION['logged_in'] == 1) && check_access("budget_helper")) {
		// Access Granted!
	}
	else {
		// Access Denied.
		header('location: https://wildrivers.firecrew.us/admin/index.php');
	}
*/
	switch($_GET['function']) {

	case("change_qty"):
		if(!isset($_GET['itemID']) || $_GET['itemID'] == '') throw new Exception('Invalid query');
		if(!isset($_GET['newQty']) || $_GET['newQty'] == '') throw new Exception('Invalid query');
		$id = mydb::cxn()->real_escape_string($_GET['itemID']);
		$new_qty = mydb::cxn()->real_escape_string($_GET['newQty']);
		
		//Get old quantity
		$query = "SELECT quantity FROM inventory WHERE id = ".$id;
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		$old_qty = $row['quantity'];
		
		//Change quantity
		$query = "UPDATE inventory SET quantity = ".$new_qty." WHERE id = ".$id;
		$result = mydb::cxn()->query($query);
		
		//Get new quantity
		$query = "SELECT quantity FROM inventory WHERE id = ".$id;
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		$new_qty = $row['quantity'];
		
		if(mydb::cxn()->error == '') {
			echo $row['quantity'];
			update_item_history($id, "quantity", $old_qty, $new_qty);
		}
		break;

	case("check_in"):
		if(!isset($_POST['itemID']) || $_POST['itemID'] == '') throw new Exception('Invalid query (no itemID)');
		check_in($_POST['itemID']); //Defined in 'inv_functions.php'
		
		// Send the itemID as a response to the calling function
		echo $_POST['itemID'];
		break;
	} // End switch()
?>