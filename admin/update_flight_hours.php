<?php

	session_start();
	require_once("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("flight_hours")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://www.siskiyourappellers.com/admin/index.php');
	}
	 
	 /********************************************************************************
	 The 'flighthours' table is indexed by a unique id that is made by appending the
	 4-digit year onto the numeric month.
	 
	 dB Schema example:
	 
	 id			month	year	hours
	 ________________________________
	 72006		7		2006	52.6
	 82006		8		2006	65.0
	 62007		6		2007	38.2
	 102007		10		2007	8.0
	 
	 *********************************************************************************/
	 $err_msg = "";
	 
	 if(isset($_POST['hours']) && isset($_POST['year']) && isset($_POST['month'])) {
	 	if(valid_data()) {
			$id = $_POST['month'] . $_POST['year'];
			
			$query = "SELECT hours FROM flighthours WHERE id like \"".$id."\"";
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') {
				die("Testing id existence failed: " . mydb::cxn()->error . "<br>\n".$query);
			}

			$row = mydb::cxn()->fetch_assoc($result);
			if(is_null($row['hours'])) { //This date has no flight hours entered yet - create a new entry
				$query = "	INSERT INTO flighthours (id,month,year,hours)
							VALUES ($id,".$_POST['month'].",".$_POST['year'].",".$_POST['hours'].")";
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->error != '') {
					die("Adding new dB row failed: " . mydb::cxn()->error . "<br>\n".$query);
				}

			}
			else {
				$query = "	UPDATE flighthours
							SET month = ".$_POST['month'].", year = ".$_POST['year'].", hours = ".$_POST['hours']."
							WHERE id like \"".$id."\"";
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->error != '') {
					die("Update existing hours for specified date failed: " . mydb::cxn()->error . "<br>\n".$query);
				}
			}
			$err_msg = "<h2>Flight Hours successfully updated!</h2><br>\n";
		}
	 }
	 elseif(isset($_POST['hours']) || isset($_POST['year']) || isset ($_POST['month'])) {
	 	$err_msg = "<h2>All data fields must be completed</h2><br>\n";
	 }


	function valid_data() {
		global $err_msg;
		$err_msg = "";
		if(!is_numeric($_POST['hours'])) $err_msg = $err_msg . "<h2>Flight Hours must be numeric</h2><br>\n";
		if(!is_numeric($_POST['year']) ) $err_msg = $err_msg . "<h2>Year must be numeric</h2><br>\n";
		if(strlen($_POST['year']) != 4 ) $err_msg = $err_msg . "<h2>Year must be a 4-digit number</h2><br>\n";
		
		if($err_msg == "") {
			$success = 1;
		}
		else $success = 0;
		
		return $success;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Update Flight Hours :: Siskiyou Rappel Crew</title>

	<?php include("../includes/basehref.html"); ?>

	<meta name="Author" content="Evan Hsu" />
	<meta name="Keywords" content="phonelist, phone, contact, crewmembers, people, email, address, mail, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
	<meta name="Description" content="View & Modify Crewmember Contact Info" />

	<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
</head>

<body>
<div id="wrapper">
 <div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Update Flight Hours</div>
    </div>

 <?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center">
	<br />
    
    <h2>Update Flight Hours</h2><br />
    <br />
    
    <form name="flighthourform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    	<table style="margin:0 auto 0 auto">
        	<tr>
            	<td>Year:</td>	<td><input name="year" type="text" size="4" value="<?php echo date('Y'); ?>"/></td>
                <td>Month:</td>	<td><select name="month">
                						<option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                    </select>
                                </td>
                <td>Hours:</td>	<td><input name="hours" type="text" size="5" /></td>
            </tr>
            <tr><td colspan=6 style="text-align:center"><input type="submit" value="Update" /></td></tr>
        </table>
    </form>
    <br />
    <?php if($err_msg != "") echo $err_msg; ?>
    
    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->


<?php include("../includes/footer.html"); ?>

</body>
</html>