<?php
	require_once("classes/mydb_class.php");

	function is_valid($year) {
		//Check to see if a given year is present in the database
		// Return 1 if given year is valid
		// Return 0 otherwise
		$result = mydb::cxn()->query("SELECT DISTINCT year FROM roster");
		if(mydb::cxn()->error != '') {
			die("Retrieving valid YEARs failed: " . mydb::cxn()->error . "<br>\n".$query);
		}
		while($row = $result->fetch_assoc()) {
			if($row['year'] == $year) return 1;
		}
		return 0; //Year is NOT valid or else function would have returned 1 by now
	}

	//************** MAIN **************************************
	if($_GET['year'] == "current") $_GET['year'] = date('Y');
	if(is_valid($_GET['year'])) {
		$year = $_GET['year'];
	}
	else {
		$year = 0;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Roster :: Siskiyou Rappel Crew</title>

<?php include("includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="View crew roster" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Roster
        	<?php  if($year > 0) echo " ($year)"  ?></div>
    </div>
	<?php include("includes/menu.php"); ?>

    <div id="content">

		<?php
				if($year <= 0) {
					echo "<br><h2>Rosters are available for the following years:</h2><br><br>\n";
					echo "<ul>\n";

					//Get all existing years from the database
					$result = mydb::cxn()->query("SELECT DISTINCT year FROM roster ORDER BY year desc");
					if(mydb::cxn()->error != '') {
						die("Retrieving YEARs for dropdown menu failed: " . mydb::cxn()->error . "<br>\n".$query);
					}

					while($row = $result->fetch_assoc()) {
						echo "<li><a href=\"roster.php?year=".$row['year']."\">".$row['year']."</li>\n";
					}//end while
					echo "</ul>\n\n";
				}

				else {
					$result = mydb::cxn()->query("	SELECT	crewmembers.id,
													crewmembers.firstname,
													crewmembers.lastname,
													crewmembers.headshot_filename,
													crewmembers.bio
											FROM	crewmembers INNER JOIN roster
											ON		crewmembers.id = roster.id
											WHERE	roster.year like \"".$year."\"
											ORDER BY	crewmembers.lastname");
					if(mydb::cxn()->error != '') {
						die("Retrieving roster failed: " . mydb::cxn()->error . "<br>\n".$query);
					}

					$count = 0;
					while($row = $result->fetch_assoc()) {
						$count = $count + 1;
						if($count % 2 == 0) $side = "right";
						else $side = "left";

						echo "<div class=\"bio\">\n"
							."<img src=\"./".$row['headshot_filename']."\" class=\"biopix_$side\" /><b class=\"highlight1\" style=\"float:$side\">";

						echo $row['firstname']." ".$row['lastname']."</b><br /><br />"
							.$row['bio']."\n"
							."</div>\n"
							."<br style=\"clear:both\"/><br />\n\n";
					}//end while
				}//end else
			?>
		</div><!-- end 'content'-->
</div><!-- end 'wrapper'-->
<?php include("includes/footer.html"); ?>

<?php include("includes/google_analytics.html"); ?>
</body>
</html>