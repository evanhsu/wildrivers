<?php

	session_start();
	require_once("../includes/auth_functions.php");
	require_once("../classes/mydb_class.php");
	
	if(!$_SESSION['logged_in']) {
		$_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: https://wildrivers.firecrew.us/admin/index.php');
	}
//-------------------------------------------------------------------------------------
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Change Password :: Wild Rivers Ranger District</title>

<?php include_once("../classes/Config.php"); ?>
<base href="<?php echo ConfigService::getConfig()->app_url ?>" />

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Change Password" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<style type="text/css">
	td {
		width:75px;
		vertical-align:bottom;
		text-align:left;
	}
	th {
		text-align:left;
	}
</style>

</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Wild Rivers Ranger District - Change Password</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center;">
	    <br />
        <a href="../admin/index.php">Back to Admin Home</a><br /><br />
        <?php
		$form = "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
			<table style=\"margin:0 auto 0 auto\">
			<tr><td>Old Password:<br /><input type=\"password\" name=\"old_password\" style=\"width:100px\" /></td>
				<td>New Password:<br /><input type=\"password\" name=\"new_password1\" style=\"width:100px\" /></td>
				<td>Re-enter New Password:<br /><input type=\"password\" name=\"new_password2\" style=\"width:100px\" /></td></tr>
			<tr><td colspan=\"3\" style=\"text-align:right\"><input type=\"submit\" value=\"Change Password\" /></td></tr>
			</table>
			</form>";
			
		if(isset($_POST['old_password']) && isset($_POST['new_password1']) && isset($_POST['new_password2'])) {
			$result = change_password($_POST['old_password'], $_POST['new_password1'], $_POST['new_password2']);
			if($result != 1) echo $result."<br><br>\n".$form;
			else echo "Password has been changed!<br>\n".$form;
		}
		else {
			echo $form;
		}
		?>
    </div><!-- END 'content' -->
</div><!-- END 'wrapper' -->

<?php include("../includes/footer.html"); ?>

</body>
</html>