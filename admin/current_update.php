<?php
	session_start();
	require_once("../includes/auth_functions.php");

	if(($_SESSION['logged_in'] == 1) && check_access("crew_status")) {
		require_once("../classes/mydb_class.php");
		include_once("../includes/update_rss_feed.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: https://wildrivers.firecrew.us/admin/index.php');
	}


	//-------------------------------------------------------------------------------------------	
	if(isset($_POST['update_text']) && $_POST['update_text'] != '') {
		$current_text = nl2br(htmlentities($_POST['update_text'], ENT_QUOTES));
		$current_sticky=nl2br(htmlentities($_POST['sticky_text'], ENT_QUOTES));

		$current_text = filter_var($current_text, FILTER_SANITIZE_STRING);
		$current_sticky = filter_var($current_sticky, FILTER_SANITIZE_STRING);
		
		$query = "INSERT INTO current (name, date, status) VALUES('".$_POST['name']."', NOW(), '".$current_text."')";
		mydb::cxn()->query($query);
		$query = "UPDATE current_sticky SET name='".$_POST['name']."', date=NOW(), status='".$current_sticky."' WHERE 1";
		mydb::cxn()->query($query);
		
		//update_rss_feed($current_sticky, $current_text, time());
		update_rss_feed();

		header('location: https://wildrivers.firecrew.us/current.php');
		//header('location: https://wildrivers.firecrew.us/admin/update_facebook_wall.php');
		exit();
	}
	
	$query_read_sticky = "SELECT name, unix_timestamp(date) as date, status FROM current_sticky WHERE 1";
	if(!$result = mydb::cxn()->query($query_read_sticky)) {
		$sticky_text = "Error retrieving sticky post: ".mydb::cxn()->error;
	}
	else {
		$row = $result->fetch_assoc();
		$sticky_text = str_replace("<br />","",$row['status']);
		$sticky_name = $row['name'];
		$sticky_date = date('d-M-Y H:i',$row['date']);
	}

	//---------------------------------------------------------------------------------------------------
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Update :: Wild Rivers Ranger District</title>

<?php include_once("../classes/Config.php"); ?>
<base href="<?php echo ConfigService::getConfig()->app_url ?>" />

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Current Crew Status Update: Modify information on the whereabouts of crewmembers and the projects that we are currently working on." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<?php
	if($_SESSION['mobile'] == 1) {
		echo "<style type=\"text/css\">\n"
			."	body {text-align:center; width:100%;}\n"
 			."	#wrapper {width:100%; min-height:100px; height:auto; margin: 5px auto 0 auto; text-align:center;}\n"
 			."	#content {width:100%; font-family:Verdana; font-size:0.8em; margin:0 auto 0 auto; text-align:left;}\n"
 			."	form .textentry {font-family:Verdana; font-size:8px; width:100%; margin:0 auto 0 auto;}\n"
 			."	td {font-family:Verdana; font-size:0.8em;}\n"
 			."	#bottom_links {font-size:0.5em; width:100%;}\n"
			."	textarea {font-family:Verdana, Arial; font-size:0.8em;}\n"
			."</style>";
	}
	else {
		echo "<style type=\"text/css\">\n"
			."	textarea {font-family:Verdana, Arial; font-size:11px;}\n"
			."</style>\n";
	}
?>

</head>

<body>
<div id="wrapper">
	<?php if($_SESSION['mobile'] != 1) {
		echo "<div id=\"banner\">
        	<a href=\"index.php\"><img src=\"images/banner_index2.jpg\" style=\"border:none\" /></a>
        	<div id=\"banner_text_bg\" style=\"background: url(images/banner_text_bg2.jpg) no-repeat;\">Wild Rivers Ranger District - Current Events Update Form</div>
    	</div>";

		include("../includes/menu.php");
		}
	?>

    <div id="content" style="text-align:center;">
    
        <?php 	if($_SESSION['mobile'] == 1) echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">"
												."<span style=\"font-size:0.5em\">Last update by <b>" . $sticky_name . "</b> on " . $sticky_date . "</span><br>\n";
				else echo "<br>| <a href=\"admin/index.php\" style=\"font-weight:bold\">Admin Home</a> |"
					."<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\" style=\"margin-top:20px\">"
					."Last update by <b>" . $sticky_name . "</b> on " . $sticky_date . "<br>\n";
					//" . $_SERVER['PHP_SELF'] . "
		?>
			<br />

            <?php
            	if($_SESSION['mobile'] == 1) {
					echo "<span class=\"highlight1\">Sticky Text</span><br />\n";
					echo "<textarea name=\"sticky_text\" cols=\"25\" rows=\"3\">" . $sticky_text . "</textarea><br />\n";
					echo "<span>Current Update:</span><br />";
            		echo "<textarea name=\"update_text\" cols=\"25\" rows=\"5\">" . $current_text . "</textarea><br />\n";
				}
				else {
					echo "<span class=\"highlight1\">Sticky Text</span> (Always at the top)<br />";
					echo "<textarea name=\"sticky_text\" cols=\"100\" rows=\"5\">" . $sticky_text . "</textarea><br /><br /><br />\n";
					echo "<span>Current Update:</span><br />";
					echo "<textarea name=\"update_text\" cols=\"100\" rows=\"10\">" . $current_text . "</textarea><br /><br />\n";
				}
				
				echo "<input type=\"hidden\" name=\"name\" value=\"".$_SESSION['username']."\">\n";
			?>

            <input type="submit" value="Update!" /> <input type="reset" value="Clear" />
        </form>
        <br /><br />
    

    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->

<?php 
	if($_SESSION['mobile'] != 1) include("../includes/footer.html");
	else include("../includes/footer_mobile.html");
?>

</body>
</html>

