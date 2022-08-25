<?php

	//if(isset($_GET['session_id'])) session_id($_GET['session_id']);
	session_start();

	//The 'ini_set' directive requires a semicolon between entries in a Windows environment, but uses a colon in a Unix environment.
	//ini_set('include_path',ini_get('include_path').';../includes/'); //Windows
	ini_set('include_path',ini_get('include_path').':../includes/'); //Unix
	//require_once '../includes/Zend/Loader.php';
	//Zend_Loader::loadClass('Zend_Gdata');
	//Zend_Loader::loadClass('Zend_Gdata_AuthSub');
	//Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
	//Zend_Loader::loadClass('Zend_Gdata_Calendar');

	require_once("../includes/auth_functions.php");
	//include("../includes/g_calendar_functions.php");
	
	//if(substr(strtolower($_SERVER['PHP_SELF']),1,9) == "incidents") header('location: http://incidents.siskiyourappellers.com');
	//$php_self = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

	$php_self = $_SERVER['PHP_SELF'];
	if(isset($_GET['year'])) $_SESSION['incident_year'] = $_GET['year'];
	if(!isset($_SESSION['incident_year'])) $_SESSION['incident_year'] = date('Y');

	if(isset($_GET['logout']) && ($_GET['logout'] == 1)) {
		session_destroy();
		session_start();
	}

	if($_SESSION['logged_in'] == 1) {
		require_once("../includes/inc_functions.php"); //Contains functions: add_line, rm_line, get_incidents
		require_once("../classes/mydb_class.php");

		if(isset($_GET['sort_by'])) $_SESSION['sort_view_by'] = $_GET['sort_by'];
		elseif (!isset($_SESSION['sort_view_by'])) $_SESSION['sort_view_by'] = "date";
		
		if(isset($_GET['function'])) $function = $_GET['function'];
		else $function = "";

		$allow_edit = 0;
		if(check_access("edit_incidents")) $allow_edit = 1;

	}//END if($_SESSION['logged_in'] == 1)

	else {
	    include_once("../classes/Config.php");
		header('location: ' . ConfigService::getConfig()->app_url . '/admin/index.php');
    }
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Incident Catalog :: Wild Rivers Ranger District</title>

<?php include_once("../classes/Config.php"); ?>
<base href="<?php echo ConfigService::getConfig()->app_url ?>" />

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="incident, incidents, code, override, fires, list, management, helitack, hecm, crew, prineville" />
<meta name="Description" content="View & Update Incident History" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/inventory.css" />

<style type="text/css">

table {
	border:none;
}

th {
	background-color: #bbccdd;
	border: 1px solid #999999;
	margin: 1px 0px 2px 0px; padding: 2px 0px 2px 0px;
	font-size: 11px;
	font-weight: bold;
	text-align:center;
}

td.form {
	vertical-align: top;
	padding:0;
	font-size: 11px;
	font-weight: normal;
	text-transform: none;
}

.chkbox {
	margin:2px 3px 0 0;
}

.entry_cell {
	margin:0; padding:1;
	font-size:10px;
	display:block;
	border:1px solid #99bb99;
}
</style>

<SCRIPT language="JavaScript1.2">
function open_calendar()
{
new_window= window.open ("<?php echo ConfigService::getConfig()->app_url ?>", "Calendar","location=0,status=0,scrollbars=0,width=810,height=610");
//testwindow.moveTo(0,0);
}
</SCRIPT>

</head>


<body>
<div id="wrapper" style="height:75px; min-height:75px; width:640px; position: relative;">
	<div id="banner" style="height: 75px">
		<span style="position: absolute; width: 100%; text-align: center; bottom: 0px; font-size: 60px; color: white;">Incident Catalog</span>
        <img src="incidents/inc_banner.jpg" style="border:none" alt="Scroll down..." />
	</div>
</div>

<div id="wrapper" style="width:95%;">
	<div id="content">

<?php
	
	if($_SESSION['logged_in'] == 1) {
		echo "<div id=\"inv_menu\">
		<div style=\"float:left\">Logged in as: ".$_SESSION['username']."</div>\n";

		if($allow_edit) echo "
				<a href=\"" . $php_self . "?function=get_incidents\" class=\"menulink\">Browse Incidents</a> |
				<a href=\"" . $php_self . "?function=add_line\" class=\"menulink\">Add New Incident</a> |";
				
		//echo "	<a href=\"" . $php_self . "\" class=\"menulink\" onClick=\"open_calendar()\">View Calendar</a> |";
		echo " <a href=\"admin/index.php\" class=\"menulink\">Admin Home</a> |
				<a href=\"admin/index.php?logout=1\" class=\"menulink\">Logout</a>
			</div><br style=\"clear:left;\">\n";
		
		switch($function) {
		case "add_line":
			if($_POST['status'] == "insert") {
				add_line();
				add_line_form($php_self);
			}
			else add_line_form($php_self);
			break;

		case "rm_line":
			if($_POST['status'] == "remove") {
				rm_line();
				$get_incidents_result = get_incidents();
				display_incidents($get_incidents_result, $php_self);
			}
			else rm_line_form($_GET['idx'], $php_self);
			break;

		case "show_incident_details":
			show_incident_details($_GET['idx'], $php_self, $allow_edit);
			break;
			
		case "view_crewmembers_incidents":
			$get_incidents_result = get_incidents($_GET['crewmember_id']);
			view_crewmembers_incidents($get_incidents_result, $_GET['crewmember_id'], $php_self);
			break;

		case "edit_line":
			if(isset($_POST['status']) && ($_POST['status'] == "edit")) {
				edit_line($_POST['idx'], $php_self);
				edit_line_form($_POST['idx'], $php_self);
			}
			else edit_line_form($_GET['idx'], $php_self);
			break;
		
		case "delete_attached_file":
			if(isset($_POST['file_id']) && ($_POST['file_id'] != "")) {
				delete_attached_file($_POST['file_id']);
			}
			edit_line_form($_GET['idx'], $php_self);
			break;
			
		case "get_incidents":
		default:
			$get_incidents_result = get_incidents();
			display_incidents($get_incidents_result, $php_self);
			break;
		}//END Switch()
	}//End if($_SESSION['logged_in'] == 1)

?>

	</div> <!-- END "content" -->
</div> <!-- END "wrapper" -->

</body>
</html>
