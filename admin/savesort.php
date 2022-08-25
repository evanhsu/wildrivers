<?php

	session_start();
	require_once(__DIR__ . "/../classes/mydb_class.php");
	require_once(__DIR__ . "/../includes/auth_functions.php");

	if(($_SESSION['logged_in'] == 1) && ($_GET['list'] == "wishlist") && check_access("budget_helper")) {
		// Access Granted!
		// Save the order of items in the wishlist (budget_helper)
		//$query = "UPDATE requisitions SET priority = 999 WHERE id = 127";
		//mydb::cxn()->query($query);

		$ids = explode(",",$_POST['ids']);
		$priority = 1;
		foreach($ids as $id) {
			$query = "UPDATE requisitions SET priority = ".$priority." WHERE id = ".$id;
			mydb::cxn()->query($query);
			$priority++;
		}
	}
	else {
		// Access Denied.
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('HTTP/1.1 401 Unauthorized');
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'User is not authorized to access this page', 'code' => '401')));
	}



