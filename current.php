<?php
	require("classes/mydb_class.php");

	$query = "SELECT status, unix_timestamp(date) as date FROM current_sticky WHERE 1";
	$sticky_result = mydb::cxn()->query($query);
	$sticky_row = $sticky_result->fetch_assoc();
	$sticky_text = $sticky_row['status'];
	$sticky_timestamp = date('M d, Y @ H:i',$sticky_row['date']);
	$sticky_name = $sticky_row['name'];
	
	$query = "SELECT status, unix_timestamp(date) as date FROM current WHERE 1 ORDER BY date DESC LIMIT 25";
	$status_result = mydb::cxn()->query($query);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Current :: Siskiyou Rappel Crew</title>
<?php include("includes/basehref.html"); ?>
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Intelligence Portal: access to the weather forecast, SIT report, and shared resource report" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>


<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Current Events</div>
    </div>
    <?php include("includes/menu.php"); ?>

    <div id="content">
        <div style="margin:2px -8px 0px auto; padding:0; text-align:right; vertical-align:middle; width:50%; float:right;"><a href="rss.php">Subscribe to this feed! </a><a href="rss.php" style="text-decoration:none; border:none;"><img src="images/rss.jpg" style="border:none;" /></a></div>
        <br style="clear:both"; />
        <?php
        	if($sticky_text != "") echo "<div class=\"highlight1\" style=\"font-size:1.2em\">Notice:</div><br />\n".$sticky_text."<br /><br />\n\n";
			while($row = $status_result->fetch_assoc()) {
				$timestamp = date('M d, Y @ H:i',$row['date']);
				echo "<br />---<br />"
					."<div class=\"highlight1\">".$timestamp."</div><br />"
					.$row['status']."<br />\n\n";
			}
        ?>
        <br />
	</div>
  <!-- end 'content'-->
</div><!-- end 'wrapper'-->
<?php include("includes/footer.html"); ?>

<?php include("includes/google_analytics.html"); ?>
</body>
</html>