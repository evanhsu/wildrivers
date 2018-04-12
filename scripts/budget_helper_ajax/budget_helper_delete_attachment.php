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
		header('location: http://tools.siskiyourappellers.com/admin/index.php');
	}
*/	
	
try {
	$req_id = mydb::cxn()->real_escape_string($_GET['req_id']);
	$attachment_num = mydb::cxn()->real_escape_string($_GET['attachment_num']);
	
	$query = "SELECT attachment".$attachment_num." FROM requisitions WHERE id = ".$req_id;
	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->affected_rows > 0) {
		$row = $result->fetch_assoc();
		$attachment_path = $row['attachment'.$attachment_num];
	}
	else {
		throw new Exception('Requisition #'.$req_id.' doesn\'t appear to have an Attachment #'.$attachment_num.'!');
		exit;
	}
	
	if(!unlink($_SERVER['DOCUMENT_ROOT']."/admin/".$attachment_path)) {
		throw new Exception('Attachment #'.$attachment_num.' could not be deleted.');
	}
	else {
		$query = "UPDATE requisitions SET attachment".$attachment_num." = NULL WHERE id = ".$req_id;
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception('Attachment #'.$attachment_num.' was deleted, but the database entry still exists: '.mydb::cxn()->error);
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

	return true;
?>