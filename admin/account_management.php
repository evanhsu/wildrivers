<?php

	session_start();
	require_once("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("account_management")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: https://wildrivers.firecrew.us/admin/index.php');
	}
//-------------------------------------------------------------------------------------
function get_access_level () {
	$access_level = "";
	if($_POST['account_management'] == 'on')	$access_level .= "account_management,";
	if($_POST['backup_restore'] == 'on')		$access_level .= "backup_restore,";
	if($_POST['roster'] == 'on')				$access_level .= "roster,";
	if($_POST['edit_phonelist'] == 'on')		$access_level .= "edit_phonelist,";
	if($_POST['inventory'] == 'on')				$access_level .= "inventory,";
	if($_POST['edit_incidents'] == 'on')		$access_level .= "edit_incidents,";
	if($_POST['budget_helper'] == 'on')			$access_level .= "budget_helper,";
	if($_POST['budget_helper_admin'] == 'on')		$access_level .= "budget_helper_admin,";
	if($_POST['flight_hours'] == 'on')			$access_level .= "flight_hours,";
	if($_POST['crew_status'] == 'on')			$access_level .= "crew_status,";
	if($_POST['photos'] == 'on')				$access_level .= "photos,";
	if($_POST['order_apparel'] == 'on')			$access_level .= "order_apparel,";
	if($_POST['manage_apparel'] == 'on')			$access_level .= "manage_apparel,";
	if($_POST['update_jobs'] == 'on')			$access_level .= "update_jobs,";
	
	if(strlen($access_level)>0)	$access_level = substr($access_level,0,strlen($access_level)-1); //Strip last comma

	return $access_level;
}
//-------------------------------------------------------------------------------------
function echo_checkboxes() {
	echo "<input type=\"checkbox\" name=\"account_management\">Account Management<br>\n"
		."<input type=\"checkbox\" name=\"backup_restore\">dB Backup & Restore<br>\n"
		."<input type=\"checkbox\" name=\"roster\">Roster<br>\n"
//		."<input type=\"checkbox\" name=\"edit_phonelist\">Edit Phonelist<br>\n"
		."<input type=\"checkbox\" name=\"inventory\">Inventory<br>\n"
		."<input type=\"checkbox\" name=\"edit_incidents\">Edit Incidents<br>\n"
		."<input type=\"checkbox\" name=\"budget_helper\">Budget Helper<br>\n"
		."<input type=\"checkbox\" name=\"budget_helper_admin\">Budget Helper Approver<br>\n"
//		."<input type=\"checkbox\" name=\"flight_hours\">Flight Hours<br>\n"
//		."<input type=\"checkbox\" name=\"crew_status\">Crew Status<br>\n"
//		."<input type=\"checkbox\" name=\"photos\">Photos<br>\n"
//		."<input type=\"checkbox\" name=\"update_jobs\">Update Job Vacancies<br>\n"
//		."<input type=\"checkbox\" name=\"order_apparel\">Order Apparel<br>\n"
//		."<input type=\"checkbox\" name=\"manage_apparel\">Manage Apparel\n"
        ;
}
//-------------------------------------------------------------------------------------
function build_auth_info_array() {
	global $auth_info;
	$query = "SELECT id, username, real_name, access_level FROM authentication WHERE 1 ORDER BY username";
	$result = mydb::cxn()->query($query) or die("Error retrieving usernames for edit_user list: " . mydb::cxn()->error);
	
	//Build a local array of access privileges for each user
	$access_levels = array(
        'account_management',
        'backup_restore',
        'roster',
//        'edit_phonelist',
        'inventory',
        'edit_incidents',
        'budget_helper',
        'budget_helper_admin',
//        'flight_hours',
//        'crew_status',
//        'photos',
//        'update_jobs',
//        'order_apparel',
//        'manage_apparel'
    );

	while($row = $result->fetch_assoc()) {
		$auth_info[$row['id']] = array('username'=>$row['username'], 'real_name'=>$row['real_name'], 'id'=>$row['id']);
		foreach($access_levels as $area) {
			if(strpos($row['access_level'],$area) !== false) $auth_info[$row['id']][$area] = 1;
			else $auth_info[$row['id']][$area] = 0;
		}
	}
}
//-------------------------------------------------------------------------------------
        /**
         * Converts a PHP array to a JavaScript array
         *
         * Takes a PHP array, and returns a string formated as a JavaScript array
         * that exactly matches the PHP array.
         *
         * @param       array  $phpArray  The PHP array
         * @param       string $jsArrayName          The name for the JavaScript array
         * @return      string
         */
        function get_javascript_array($phpArray, $jsArrayName) {
                $html = "var ".$jsArrayName . " = new Array();\n";
                foreach ($phpArray as $id => $row) {
					$html .= $jsArrayName . "['".$id."'] = new Array();\n";
					foreach($row as $key=>$value) {
						$html .= $jsArrayName . "['".$id."']['".$key."'] = \"". $value ."\";\n";
					}
				}
                return $html;
        }
//-------------------------------------------------------------------------------------
$auth_info = array();
build_auth_info_array();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manage Accounts :: Wild Rivers Ranger District</title>

<?php include_once("../classes/Config.php"); ?>
<base href="<?php echo ConfigService::getConfig()->app_url ?>">

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression" />
<meta name="Description" content="Manage Accounts - Adjust access privileges, Create & Delete user accounts" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<style type="text/css">
	form {margin:0 auto 0 auto;}
	#checkbox_table {margin:0 auto 0 auto;}
	#checkbox_table td {
		vertical-align:top;
		text-align:left;
	}
	#checkbox_table th {
		text-align:left;
	}
</style>


</head>

<body onload="update_checkboxes()">
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Wild Rivers Ranger District - Account Management</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center">
	    <br />
        <?php
			echo "<a href=\"".$_SERVER['PHP_SELF']."?function=add_user\">Add Account</a> | "
				."<a href=\"".$_SERVER['PHP_SELF']."?function=edit_user\">Edit Account</a> | "
				."<a href=\"".$_SERVER['PHP_SELF']."?function=rm_user\">Remove Account</a> | "
				."<a href=\"admin/index.php\">Back to Admin Home</a><br>\n";
			//----------------------------------------
        	switch($_GET['function']) {
			case 'rm_user':
				if($_POST['id'] != '') {
					$result = rm_user($_POST['id']);
					if($result != 1) echo "<br>".$result;
				}
				$query = "SELECT id, username FROM authentication WHERE 1";
				$result = mydb::cxn()->query($query) or die("Error retrieving usernames for rm_user list: " . mydb::cxn()->error);
				
				echo "<br><br><form action=\"".$_SERVER['PHP_SELF']."?function=rm_user\" method=\"POST\">\n"
					."<table id=\"checkbox_table\">\n"
					."<tr><th colspan=\"3\">Remove User Account</th></tr>\n"
					."<tr><td>Select user to delete:</td><td><select name=\"id\">\n";
				while($row = $result->fetch_assoc()) {
					echo "<option value=\"".$row['id']."\">".$row['username']."</option>\n";
				}
				echo "</select></td>\n"
					."<td><input type=\"submit\" value=\"Remove User\"></td></tr>\n</table>\n"
					."</form>";
				break;
			//----------------------------------------
			case 'edit_user':
				if($_POST['id'] != '') {
					$access_level = get_access_level();
					
					$result = edit_user($_POST['id'],$_POST['real_name'],$access_level);
					if($result != 1) echo $result;
				}
				build_auth_info_array();
				
				echo "<br><br><form action=\"".$_SERVER['PHP_SELF']."?function=edit_user\" method=\"POST\" name=\"access_form\">\n"
					."<table id=\"checkbox_table\">\n"
					."<tr><th colspan=\"3\">Edit Access Privileges</th></tr>\n"
					."<tr><th>Username</th><th style=\"width:150px\">Access</th><th>&nbsp;</th></tr>\n"
					."<tr><td><select name=\"id\" onchange=\"update_checkboxes()\">\n";
				foreach($auth_info as $user_id=>$info) {
					if($user_id != $_SESSION['user_id']) echo "<option value=\"".$info['id']."\">".$info['username']."</option>\n"; //Don't let admins modify their own privileges
				}
				echo "</select></td>\n"
					."<td rowspan=\"2\" style=\"width:175px\">";
				echo_checkboxes();
				echo "</td>\n"
					."<td><input type=\"submit\" value=\"Modify User\"></td></tr>\n"
					."<tr><td style=\"vertical-align:top\"><b>Full Name:</b><br><input type=\"text\" name=\"real_name\" style=\"width:150px\"></td><td></td></tr>"
					."</table>\n</form>";
			
				break;
			//----------------------------------------
			case 'add_user':
			default:
				if(isset($_POST['username'])) {
					$access_level = get_access_level();
					
					$result = add_user($_POST['username'], $_POST['password'], $_POST['real_name'], $access_level);
					if($result != 1) echo "<br>".$result."<br>\n";
					else echo "<br>User has been successfully added!<br>\n";
				}
				echo "<br><br><form action=\"".$_SERVER['PHP_SELF']."?function=add_user\" method=\"POST\">\n"
					."<table id=\"checkbox_table\">\n"
					."<tr><th colspan=\"4\">Add User Account</th></tr>\n"
					."<tr><th>Username</th><th>Password</th><th style=\"width:150px\">Access</th><th>&nbsp;</th></tr>\n"
					."<tr><td style=\"vertical-align:top\"><input type=\"text\" name=\"username\" style=\"width:100px\"></td>\n"
					."<td><input type=\"password\" name=\"password\" style=\"width:100px\"></td>\n"
					."<td rowspan=\"2\">";
				echo_checkboxes();
				echo "</td>\n"
					."<td><input type=\"submit\" value=\"Add User\"></td></tr>\n"
					."<tr><td colspan=\"2\" style=\"vertical-align:top\"><b>Full Name:</b><br><input type=\"text\" name=\"real_name\" style=\"width:150px\"></td><td></td></tr>"
					."</table>\n</form>";
			
				break;
			}//END switch
        
        ?>
    </div><!-- END 'content' -->
</div><!-- END 'wrapper' -->

<?php include("../includes/footer.html"); ?>

</body>
<script type="text/javascript">
function update_checkboxes() {
	<?php echo get_javascript_array($auth_info,'auth_info'); ?>
	var user_id = document.access_form.id.value;
	
	document.access_form.real_name.value = auth_info[user_id]['real_name'];
	
	if(auth_info[user_id]['account_management'] == 1) document.access_form.account_management.checked = true;
	else document.access_form.account_management.checked = false;
	
	if(auth_info[user_id]['backup_restore'] == 1) document.access_form.backup_restore.checked = true;
	else document.access_form.backup_restore.checked = false;
	
	if(auth_info[user_id]['roster'] == 1) document.access_form.roster.checked = true;
	else document.access_form.roster.checked = false;
	
	if(auth_info[user_id]['edit_phonelist'] == 1) document.access_form.edit_phonelist.checked = true;
	else document.access_form.edit_phonelist.checked = false;
	
	if(auth_info[user_id]['inventory'] == 1) document.access_form.inventory.checked = true;
	else document.access_form.inventory.checked = false;
	
	if(auth_info[user_id]['edit_incidents'] == 1) document.access_form.edit_incidents.checked = true;
	else document.access_form.edit_incidents.checked = false;
	
	if(auth_info[user_id]['budget_helper'] == 1) document.access_form.budget_helper.checked = true;
	else document.access_form.budget_helper.checked = false;

	if(auth_info[user_id]['budget_helper_admin'] == 1) document.access_form.budget_helper_admin.checked = true;
	else document.access_form.budget_helper_admin.checked = false;
	
	if(auth_info[user_id]['flight_hours'] == 1) document.access_form.flight_hours.checked = true;
	else document.access_form.flight_hours.checked = false;
	
	if(auth_info[user_id]['crew_status'] == 1) document.access_form.crew_status.checked = true;
	else document.access_form.crew_status.checked = false;
	
	if(auth_info[user_id]['photos'] == 1) document.access_form.photos.checked = true;
	else document.access_form.photos.checked = false;
	
	if(auth_info[user_id]['update_jobs'] == 1) document.access_form.update_jobs.checked = true;
	else document.access_form.update_jobs.checked = false;
	
	if(auth_info[user_id]['order_apparel'] == 1) document.access_form.order_apparel.checked = true;
	else document.access_form.order_apparel.checked = false;
	
	if(auth_info[user_id]['manage_apparel'] == 1) document.access_form.manage_apparel.checked = true;
	else document.access_form.manage_apparel.checked = false;
}
</script>
</html>
