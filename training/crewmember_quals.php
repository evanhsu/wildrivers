<?php

	//if(isset($_GET['session_id'])) session_id($_GET['session_id']);
	session_start();

	ini_set('include_path',ini_get('include_path').':../includes:../includes/Zend:');
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_AuthSub');
	Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
	Zend_Loader::loadClass('Zend_Gdata_Calendar');

	require_once("../includes/auth_functions.php");
	include("../includes/g_calendar_functions.php");
	
	//if(substr(strtolower($_SERVER['PHP_SELF']),1,9) == "incidents") header('location: http://incidents.siskiyourappellers.com');
	//$php_self = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

	$php_self = $_SERVER['PHP_SELF'];

	if($_GET['logout'] == 1) {
		session_destroy();
		session_start();
	}

	if($_SESSION['logged_in'] == 1) {
		$allow_edit = 0;
		if(check_access("edit_training")) $allow_edit = 1;

	}//END if($_SESSION['logged_in'] == 1)

	else header('location: https://wildrivers.firecrew.us/admin/index.php');
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Training Tracker :: Wild Rivers Ranger District</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="training, iqcs, ics, class, classes, courses, qualifications, quals, red card, trainee, task book, positions, fires, list, management, helitack, hecm, crew, prineville" />
<meta name="Description" content="View & Update Incident History" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />

</head>


<body>
<div id="wrapper" style="height:75px; min-height:75px; width:900px;">
	<div id="banner">
        <a href="https://wildrivers.firecrew.us/incidents" style="display:block; width:900px; height:75px; padding:0;"><img src="incidents/inc_banner.jpg" style="border:none" alt="Scroll down..." /></a>
    </div>
</div>

<div id="wrapper" style="width:95%;">
	<div id="content">