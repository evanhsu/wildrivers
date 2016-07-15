<?php

	session_start();
	require_once("../includes/auth_functions.php");
	require_once("../classes/mydb_class.php");
	
	if(isset($_GET['logout']) && ($_GET['logout'] == 1)) {
		session_destroy();
		session_start();
	}
	
	if(isset($_POST['mobile'])) $_SESSION['mobile'] = $_POST['mobile'];
	elseif(isset($_GET['mobile'])) $_SESSION['mobile'] = $_GET['mobile'];
	elseif(!isset($_SESSION['mobile'])) $_SESSION['mobile'] = 0;
	
	if(isset($_POST['username']) && isset($_POST['passwd'])) $login_result = login($_POST['username'], $_POST['passwd']);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin :: Siskiyou Rappel Crew</title>
<?php include("../includes/basehref.html"); ?>
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Administrative Portal" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php
	if($_SESSION['mobile'] == 1) {
		echo "<style type=\"text/css\">\n"
			."body {text-align:center; width:100%;}\n"
 			."#wrapper {width:100%; min-height:100px; height:auto; margin: 5px auto 0 auto; text-align:center;}\n"
 			."#content {width:100%; font-family:Verdana; font-size:0.8em; margin:0 auto 0 auto; text-align:left;}\n"
 			."form .textentry {font-family:Verdana; font-size:8px; width:100%; margin:0 auto 0 auto;}\n"
 			."td {font-family:Verdana; font-size:0.8em;}\n"
 			."#bottom_links {font-size:0.5em; width:100%;}\n"
			."</style>\n";
	}
	else {
		echo "<style type=\"text/css\">\n"
			."form .textentry {width:150px;}\n"
			."</style>\n";
	}
?>

</head>

<body>
<div id="wrapper">
	<?php
		if($_SESSION['mobile'] != 1) {
			echo "<div id=\"banner\">\n"
        		."<a href=\"index.php\"><img src=\"images/banner_index2.jpg\" style=\"border:none\" alt=\"Scroll down...\" /></a>\n"
        		."<div id=\"banner_text_bg\" style=\"background: url(images/banner_text_bg2.jpg) no-repeat;\">Siskiyou Rappel Crew - Admin Login</div>\n"
    			."</div>\n";
			include("../includes/menu.php");
		}
	?>

    <div id="content">
	    <br />
        <?php
            if($_SESSION['logged_in'] == 1) {
                echo "	You are logged in as <i>".$_SESSION['username']."</i><br><br>\n";
				//Print a notice if logged in as 'coh'
				if(($_SESSION['username'] == 'coh') && ($_SESSION['mobile'] != 1)) echo "<span class=\"highlight1\" style=\"display:block\">Anonymous access (username: coh) has been restricted to mobile-device usage <i>only</i>.<br>For full access, login with your unique username.<br></span><br>\n";
				echo "<b>Crew Info</b><br>\n";
				if(check_access("crew_status")) echo "<a href=\"admin/current_update.php\">Update current crew status</a><br>\n";
				if(check_access("update_jobs")) echo "<a href=\"admin/update_job_vacancies.php\">Update Job Vacancies</a><br />\n";
				echo "<a href=\"admin/phonelist.php\">Crew Phonelist</a><br>\n";
				
				//Remove the rest of the menu if user is connected from a mobile device
				if($_SESSION['mobile'] != 1) {
					if(check_access("roster")) echo "<a href=\"admin/modify_roster.php\">Modify rosters</a> -- Add crewmembers, modify biographies, or build a whole new crew<br>\n";
					if(check_access("flight_hours")) echo "<a href=\"admin/update_flight_hours.php\">Update Flight Hours</a> -- Enter flight hours for each month<br>\n";
					if(check_access("order_apparel")) echo "<br>
							<b>Crew Apparel</b><br>\n
							<a href=\"admin/apparel.php\">Order Crew Apparel</a> - Crew shirts, hoodies and hats<br>\n";
					if(check_access("manage_apparel")) echo "
							<a href=\"admin/apparel_summary.php\">View Apparel Order Summary</a> - See each crewmember's apparel order and a summary for the entire crew<br>\n";
					if(check_access("photos")) echo "<br>
							<b>Photos</b><br>
							<a href=\"admin/manage_photos.php\">Manage photos</a> -- Edit captions and delete photos<br>
							<a href=\"admin/photo_upload.php\">Upload new photos</a> -- Add new photos to the website<br>\n";
					echo "<br><b>Document Repository</b><br>\n<a href=\"admin/docs/index.php\">Documents</a> -- Download letterhead, logo artwork, pocket guides, etc.<br>\n";
					if(check_access("inventory")) echo "<br><b>Inventory</b><br>
							<a href=\"/inventory/index.php?session_id=".session_id()."\">Inventory Management</a> -- Gear check in / check out<br>";
					if(check_access("edit_incidents")) echo "<br><b>Incident Management</b><br>
							<a href=\"incidents/index.php\">Helitack Incident Catalog</a> -- View & update p-codes, crewmembers on-scene, fuel models, etc<br>\n";
							
					if(check_access("budget_helper")) echo "<br><b>Budget Helper</b><br>
							<a href=\"admin/budget_helper.php\">Budget Helper</a> -- Track your purchases<br />\n";
					
					if($_SESSION['username'] != 'coh') echo "<br><b>Account Management</b><br>
							<a href=\"admin/change_password.php\">Change Password</a> -- Change your login password<br>\n";
					if(check_access("account_management")) echo "<a href=\"admin/account_management.php\">User Accounts</a> -- Modify user access privileges<br>\n";
					if(check_access("backup_restore")) echo "<br><b>Database Recovery</b><br>\n<a href=\"admin/backup_restore.php\">Data Integrity Console</a> -- Backup or Restore the database<br>\n";
				}//END if($_SESSION['mobile'] != 1)
				echo "<br><br><a href=\"".$_SERVER['PHP_SELF']."?logout=1&mobile=".$_SESSION['mobile']."\">Logout</a><br>";
            }
			else {
			    echo "	<span class=\"highlight1\" style=\"display:block\">Login to use administrative tools:</span><br />\n";
				
        		echo "	<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\" style=\"margin:0; padding:0;\">\n"
            		."		<table style=\"margin:0; padding:0;\">\n"
                	."			<tr><td>Username:</td><td><input name=\"username\" type=\"text\" class=\"textentry\" /></td></tr>\n"
                	."			<tr><td>Password:</td><td><input name=\"passwd\" type=\"password\" class=\"textentry\" /></td></tr>\n"
					."			<tr><td></td><td><input type=\"submit\" value=\"Login\" style=\"background-color:#aca; border:2px solid #336633; font-weight:bold; font-size:10px; display:block;\" /></td></tr>\n"
            		."		</table>\n"
        			."	</form>";
			}
        ?>

    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->
<div style="clear:both; display:block;">&nbsp;</div>
<?php 
	if($_SESSION['mobile'] != 1) include("../includes/footer.html");
	else include("../includes/footer_mobile.html");
?>

</body>
</html>
