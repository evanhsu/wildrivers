<?php

	session_start();
	require("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("backup_restore")) {
		require("../scripts/connect.php");
		$dbh = connect();
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://www.siskiyourappellers.com/admin/index.php');
	}
	
	if(isset($_POST['function'])) {
		switch($_POST['function']) {
		case 'restore_from_user_file':
			$fh = fopen($_FILES['userfile']['tmp_name'],'r');
			while($line = fgets($fh)) {
				$query .= $line;
			}
			mysql_query($query,$dbh);
		break;
		
		case 'restore_from_auto_backup':
			$query = "";
			$fh = fopen("../".$_POST['filename'],'r');
			while($line = fgets($fh)) {
				$query .= $line;
			}
			mysql_query($query,$dbh);
		break;
		}
	}

	//****************************************************************************************
	function display_download_menu($backup_file_list) {
		echo "<div style=\"margin-left:10px;\">\n";
		foreach($backup_file_list as $key=>$row) {
			echo "\t\t\t<a href=\"".$row['filename']."\">".$row['date']."</a><br>\n";
		}
		echo "\t\t\t</div>\n";
		return;
	}// END display_download_menu()

	$backup_root_folder = "../db_backups";
	$relative_root_folder = "db_backups";
	$days = array('mon','tue','wed','thu','fri','sat','sun');
	$backup_file_list = array();
		
	foreach($days as $day) {
		if(!$dh = opendir($backup_root_folder."/".$day)) echo "Couldn't open folder: ".$backup_root_folder."/".$day;
		while($file = readdir($dh)) {
			if(($file != ".") && ($file != "..")) {
				preg_match('/(19|20)[0-9]{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])/',$file,$matches);
				//preg_match('/\d{4}-\d{2}-\d{2}/',$file,$matches);
				$file_date = $matches[0];
				$date_diff = strtotime(time()) - strtotime($file_date);
				$backup_file_list[] = array('filename'=>$relative_root_folder."/".$day."/".$file, 'date'=>$file_date, 'date_diff'=>$date_diff);
			}
		}
	}
	
	if(count($backup_file_list) == 0) {
		$backup_file_list[] = array('filename'=>"",'date'=>"none",'date_diff'=>0);
	}
	else {
		//Sort backup files so that the most recent is at the top of the list
		foreach($backup_file_list as $key=>$value) {
			$fn[$key] = $value['filename'];
			$d[$key] = $value['date'];
			$dd[$key] = $value['date_diff'];
		}
		array_multisort($dd, SORT_ASC, $d, $fn, $backup_file_list);
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Backup / Restore Database :: Siskiyou Rappel Crew</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Backup or Restore the database (Admin Only)" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<style type="text/css">
.backup_table td {
	text-align:left;
	vertical-align:top;
	background-color:#ddeedd;
	padding: 2px;
	border:2px solid #ccddcc;
}
</style>
</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Data Integrity Console</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center;">
    	<br />
        | <a href="admin/index.php">Back to Admin Home</a> |<br /><br />
        <div style="font-size:1em; color:#aaa;">
        	Restore operations will REPLACE ALL EXISTING DATA in the database.<br />
            Any changes made to the website since the backup file was created will be lost.
        </div>
        
        <br />
        
    	<table class="backup_table" style="margin:0 auto 0 auto;">
        <tr>
        	<td style="width:300px;"><div style="font-weight:bold; color:#990000; font-size:1.2em;">Download Backup File</div></td>
            <td><div style="font-weight:bold; color:#990000; font-size:1.2em;">Restore Database From Backup File</div></td>
        </tr>
        <tr>
        	<td><div style="font-size:0.9em; color:#777777;">(Right-click and choose "Save As...")</div><br />
        		<?php display_download_menu($backup_file_list); ?>
            </td>
            <td><div style="font-size:0.9em; color:#777777;display:inline;">Upload a File</div><br />
				<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin:0;">
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" /><!-- Ten Megabytes...ish -->
            	<input type="file" name="userfile" size="40" /><br />
                <input type="submit" value="Upload & Restore" />
                <input type="hidden" name="function" value="restore_from_user_file" />
                </form>
            	<br />
                
                <div style="font-size:1.2em; font-weight:bold; color:#666666;">or</div>
                
                <br />
                <div style="font-size:0.9em; color:#777777;">Load an auto-backup File</div>
                <div style="font-size:0.9em; color:#bbbbbb;">(Backups are performed nightly at 10:30pm)</div>
            	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin:0;">
            		<select name="filename" style="width:100px;">
<?php
						for($i=0;$i<count($backup_file_list);$i++) {
							echo "\t\t\t\t<option value=\"".$backup_file_list[$i]['filename']."\">".$backup_file_list[$i]['date']."</option>\n";
						}
?>
                	</select>
                	<input type="hidden" name="function" value="restore_from_auto_backup" />
                	<input type="submit" value="Restore" />
                </form>
            </td>
        </tr>
        </table>
        
        <br /><br /><br />
        <div style="font-size:1.2em; font-weight:bold; color:#bb0000;">WARNING</div>
        <div style="font-size:1.0em; font-weight:bold; color:#999999;">
        	This tool provides the functionality to irreparably damage the database.<br />
            Restore operations should be performed ONLY with UNMODIFIED database backup files.<br /><br />
            You are not MacGyver. Don't upload modified files.
        </div>
    
	</div><!-- end 'content'-->
</div><!-- end 'wrapper'-->

<?php include("../includes/footer.html") ?>

</body>
</html>