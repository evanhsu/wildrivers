<?php

	session_start();
	require_once("../includes/auth_functions.php");
	
	if($_SESSION['logged_in'] == 1) {
		require_once("../classes/mydb_class.php");
	}
	else {
		$_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://tools.siskiyourappellers.com/admin/index.php');
	}
	//------
	// View/Edit privileges determine whether listings show as links, or just as plain text
	if(check_access("edit_phonelist") && ($_SESSION['mobile'] != 1)) $allow_edit = 1;
	else $allow_edit = 0;

	 // Decide which year phonelist to display
	 if(isset($_GET['year'])) $year = $_GET['year'];
	 else $year = date("Y"); //Use current year if no year is specified

	if($allow_edit) {
		 // Get command
		 if(isset($_GET['cmd'])) $cmd = $_GET['cmd'];
		 else $cmd = "show_phonelist"; //Set default command if no cmd is specified
	
		// Get id
		 if(isset($_GET['id'])) $id = $_GET['id'];
		 else $id = -1; //Set default id if no id is specified
	
		 // Make updates if necessary
		 if(isset($_POST['id'])) update_info();
	}


	 //**************************************************************************
	 //**************************************************************************

	 function show_phonelist($year) {
		global $allow_edit;
		$query = "SELECT	crewmembers.id,
		 					crewmembers.lastname,
							crewmembers.firstname,
							crewmembers.phone,
							crewmembers.email,
							crewmembers.street1,
							crewmembers.street2,
							crewmembers.city,
							crewmembers.state,
							crewmembers.zip,
							roster.year
				FROM		crewmembers INNER JOIN roster
				ON			crewmembers.id = roster.id
				WHERE 		roster.year like \"".$year."\"
				ORDER BY	crewmembers.lastname, crewmembers.firstname";

		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB query failed: " . mydb::cxn()->error . "<br>\n".$query);
		}

		print "<table class=\"phone_table\" style=\"border:2px solid #ddd; margin:0 auto 0 auto;\">\n";
		
		switch($allow_edit) {
		case 1:
			while($row = $result->fetch_assoc()) {
				print	"<tr><td><a href=\"".$_SERVER['PHP_SELF']."?cmd=update_info&id=".$row['id']."\">".$row['lastname'].", ".$row['firstname']."</a></td>"
						."<td>".$row['phone']."</td>"
						."<td>".$row['email']."</td>"
						."<td style=\"font-size:10px\">".$row['street1']."<br>";
				if($row['street2'] != '') print $row['street2']."<br>";
				print $row['city'].", ".$row['state']." ".$row['zip']."</td>\n";
			}
			break;
		
		case 0:
		default:
			if($_SESSION['mobile'] == 1) {
				while($row = $result->fetch_assoc()) {
					print	"<tr><td style=\"text-align:left;\"><span style=\"font-weight:bold; color:#eeeeee; background-color:#333333; padding:2px 5px 2px 2px;line-height:1.5em;\">".$row['lastname'].", ".$row['firstname']."</span><br>\n";
					if($row['phone'] != '') print $row['phone']."<br>\n";
					if($row['email'] != '') print $row['email']."<br>\n";
					if($row['street1'] != '') print $row['street1']."<br>\n";
					if($row['street2'] != '') print $row['street2']."<br>\n";
					if($row['city'] != '') print $row['city'].", ";
					if($row['state'] != '') print $row['state']." ";
					if($row['zip'] != '') print $row['zip'];
					print "</td>\n";
				}
			}
			else {
				while($row = $result->fetch_assoc()) {
					print	"<tr><td>".$row['lastname'].", ".$row['firstname']."</td>"
							."<td>".$row['phone']."</td>"
							."<td>".$row['email']."</td>"
							."<td style=\"font-size:10px\">".$row['street1']."<br>";
					if($row['street2'] != '') print $row['street2']."<br>";
					print $row['city'].", ".$row['state']." ".$row['zip']."</td>\n";
				}
			}
			break;
		}//END switch

		print "</table>\n<br>\n";
	}// end 'show_phonelist'

//-----------------------------------------------------------------------------------------------------------------

	function show_update_form($id) {
		$query = "SELECT	crewmembers.id,
							crewmembers.lastname,
							crewmembers.firstname,
							crewmembers.phone,
							crewmembers.email,
							crewmembers.street1,
							crewmembers.street2,
							crewmembers.city,
							crewmembers.state,
							crewmembers.zip
				FROM		crewmembers
				WHERE		crewmembers.id like '".$id."'";
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB query failed: " . mydb::cxn()->error . "<br>\n".$query);
		}
		$row = $result->fetch_assoc();

		print	"<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n"
				."<table class=\"phone_table\">\n<tr><td>Name</td><td>"
				.$row['firstname']." ".$row['lastname']."</td></tr>\n"
				."<tr><td>Street</td><td><input type=\"text\" name=\"street1\" value=\"".htmlentities($row['street1'])."\"></td></tr>\n"
				."<tr><td>&nbsp;</td><td><input type=\"text\" name=\"street2\" value=\"".htmlentities($row['street2'])."\"></td></tr>\n"
				."<tr><td>City</td><td><input type=\"text\" name=\"city\" value=\"".htmlentities($row['city'])."\"></td></tr>\n"
				."<tr><td>State</td><td><input type=\"text\" name=\"state\" value=\"".htmlentities($row['state'])."\"></td></tr>\n"
				."<tr><td>Zip</td><td><input type=\"text\" name=\"zip\" value=\"".htmlentities($row['zip'])."\"></td></tr>\n"
				."<tr><td>&nbsp</td><td>&nbsp;</td></tr>"
				."<tr><td>Email</td><td><input type=\"text\" name=\"email\" value=\"".htmlentities($row['email'])."\"></td></tr>\n"
				."<tr><td>Phone</td><td><input type=\"text\" name=\"phone\" value=\"".htmlentities($row['phone'])."\"></td></tr>\n"
				."<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"Update\"></td></tr>\n"
				."<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">\n"
				."</table>\n</form>\n";
	}// end 'show_update_form'

//-----------------------------------------------------------------------------------------------------------------

	function update_info() {
		$query = "update crewmembers set street1 = \"".mydb::cxn()->real_escape_string($_POST['street1'])."\", "
				."street2 = \"".mydb::cxn()->real_escape_string($_POST['street2'])."\", "
				."city = \"".mydb::cxn()->real_escape_string($_POST['city'])."\", "
				."state = \"".mydb::cxn()->real_escape_string($_POST['state'])."\", "
				."zip = \"".mydb::cxn()->real_escape_string($_POST['zip'])."\", "
				."phone = \"".mydb::cxn()->real_escape_string($_POST['phone'])."\", "
				."email = \"".mydb::cxn()->real_escape_string($_POST['email'])."\""
				." where id like \"".$_POST['id']."\"";
				
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB query failed (update contact info): " . mydb::cxn()->error . "<br>\n".$query);
		}

	}//end 'update_info'



//-----------------------------------------------------------------------------------------------------------------

	function make_year_dropdown() {
		$query = "SELECT DISTINCT year FROM roster WHERE 1 ORDER BY year DESC";
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB query failed (year dropdown menu): " . mydb::cxn()->error . "<br>\n".$query);
		}

		print	"<form action=\"".$_SERVER['PHP_SELF']."\" method=\"GET\">\n<select name=\"year\">\n";
		while($row = $result->fetch_assoc()) {
			print "<option value=\"".$row['year']."\"";
			if($_GET['year'] == $row['year']) print " selected=\"selected\"";
			print ">".$row['year']."</option>\n";
		}

		print "</select>\n<input type=\"submit\" value=\"Go\">\n</form><br>\n\n";

	}//end 'make_year_dropdown'

//----------------------------------------------------------------------------------------------------------------
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Phonelist :: Siskiyou Rappel Crew</title>

	<?php include("../includes/basehref.html"); ?>

	<meta name="Author" content="Evan Hsu" />
	<meta name="Keywords" content="phonelist, phone, contact, crewmembers, people, email, address, mail, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
	<meta name="Description" content="View & Modify Crewmember Contact Info" />

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
		echo "<style>\n"
			.".phone_table td {\n"
			."background-color:#eee;\n"
			."padding:2px 10px 2px 10px;\n"
			."margin:0;\n"
			."text-align:left;\n"
			."}\n"
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
        		."<div id=\"banner_text_bg\" style=\"background: url(images/banner_text_bg2.jpg) no-repeat;\">Siskiyou Rappel Crew - Phonelist</div>\n"
    			."</div>\n";
			include("../includes/menu.php");
		}
	?>

    <div id="content" style="text-align:center">
	<br />

	<?php

		make_year_dropdown();
		echo "| <a href=\"admin/index.php\">Admin Home</a> |<br><br>\n\n";
		
		switch ($cmd) {
		case "update_info":
			show_update_form($id);
			break;
		case "show_phonelist":
		default:
			show_phonelist($year);
			break;
		}// end 'switch'
	?>

    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->


<?php 
	if($_SESSION['mobile'] != 1) include("../includes/footer.html");
	else include("../includes/footer_mobile.html");
?>

</body>
</html>