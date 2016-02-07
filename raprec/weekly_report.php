<?php
	/*******************************************************************************************************/
	/* Copyright (C) 2012 Evan Hsu
       Permission is hereby granted, free of charge, to any person obtaining a copy of this software
	   and associated documentation files (the "Software"), to deal in the Software without restriction,
	   including without limitation the rights to use, copy, modify, merge, publish, distribute,
	   sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
	   furnished to do so, subject to the following conditions:

       The above copyright notice and this permission notice shall be included in all copies or
	   substantial portions of the Software.

       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
	   NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	   IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */
	/********************************************************************************************************/
	include('includes/php_doc_root.php');
	
	require_once("classes/mydb_class.php");
	require_once("classes/user_class.php");
	require_once("classes/email_class.php");
	require_once("classes/hrap_class.php");
	require_once("classes/crew_class.php");
	
	session_name('raprec');
	session_start();
	
	require("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	
	if(isset($_GET['logout']) && ($_GET['logout'] == 1)) {
		session_destroy();
		session_name('raprec');
		session_start();
	}
	
	//if($_SESSION['logged_in'] != 1) header('location: http://www.centraloregonhelitack.com/raprec/index.php');
	
	
	if(isset($_POST['mobile'])) $_SESSION['mobile'] = $_POST['mobile'];
	elseif(isset($_GET['mobile'])) $_SESSION['mobile'] = $_GET['mobile'];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Weekly Report :: RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="weekly report, actions, master actions, proficiency, proficient, fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, history" />
<meta name="Description" content="This page shows a weekly summary of rappel actions." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if(isset($_SESSION['mobile']) && ($_SESSION['mobile'] == 1)) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

</head>

<body>
    <div id="banner_left"><a href="index.php"><img src="images/raprec_banner_left.jpg" style="border:none" alt="RapRec Central Logo" /></a></div>
    <div id="banner_right"><a href="index.php"><img src="images/raprec_banner_right.jpg" style="border:none" alt="RapRec Central" /></a></div>
	
    <div id="left_sidebar">
    	<?php make_menu(); ?>
    </div>
	
    <div id="location_bar"><?php echo $_SESSION['location_bar']; ?></div>
    
    <div id="content" style="text-align:center">
        
    <?php
	show_weekly_report();
	?>

    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>


<?php

function show_weekly_report() {
	// INPUTS:
	//	None
	//
	// OUTPUT:
	//	This function prints the appropriate HTML page content to the screen.
	//	There is no return value.
	
	// $totals[0] = Operationals
	// $totals[1] = Proficiencies
	// $totals[2] = 2 Man Actions
	// $totals[3] = 3 - 4 Man Actions
	// $totals[4] = 5 - 6 Man Actions
	// $totals[5] = 7+ Man Actions
	$totals = array(0,0,0,0,0,0);
	$query = "
		SELECT weekly_report_bins.category, COUNT(*) AS total
		FROM weekly_report_bins
		LEFT OUTER JOIN weekly_report_view ON weekly_report_bins.value = weekly_report_view.hraps
		
		WHERE weekly_report_view.date >= STR_TO_DATE(  '".$_GET['start_date']."',  '%m-%d-%Y' ) 
		AND weekly_report_view.date <= STR_TO_DATE(  '".$_GET['end_date']."',  '%m-%d-%Y' ) 
		AND LOWER( weekly_report_view.aircraft_tailnumber ) = LOWER(  '".$_GET['tailnumber']."' ) 
		
		GROUP BY weekly_report_bins.category
		ORDER BY weekly_report_bins.category
		LIMIT 0 , 30";

	$result = mydb::cxn()->query($query);
	
	$text = "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"get\">\n"
			."<table>"
			."<tr><td style=\"text-align:left;\">Start Date:</td>"
			."<td style=\"text-align:left;\">End Date:</td><td>Tailnumber</td>"
			."</tr>\n"
			."<tr><td><input type=\"text\" name=\"start_date\" value=\"".$_GET['start_date']."\" style=\"width:6em\"></td>\n"
			."<td><input type=\"text\" name=\"end_date\" value=\"".$_GET['end_date']."\" style=\"width:6em;\"></td>"
			."<td><input type=\"text\" name=\"tailnumber\" value=\"".$_GET['tailnumber']."\" style=\"width:6em;text-transform:uppercase;\"></td>"
			."<td><input type=\"submit\" value=\"View\"></td></tr></table></form>";
			
	
	$text .= "<br>\n"
			."<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
			."<th style=\"width:7em;\">Start Date</th>"
			."<th style=\"width:7em;\">End Date</th>"
			."<th>Operational Rappels</th>"
			."<th>Proficiency Rappels</th>"
			."<th>2 Man Actions</th>"
			."<th>3-4 Man Actions</th>"
			."<th>5-6 Man Actions</th>"
			."<th>7+ Man Actions</th></tr>\n";
	
	
				
	$current_row = 0;
	while($row = $result->fetch_assoc()) {
		$current_row++;
		
		if($row['category'] == "2 Man Actions") {
			if(isset($row['total']) && !is_null($row['total']) && $row['total']!='') $totals[2] = $row['total'];
			else $totals[2]='0';
		}
		elseif($row['category'] == "3 - 4 Man Actions") {
			if($row['total']!='') $totals[3] = $row['total'];
			else $totals[3] = '0';
		}
		elseif($row['category'] == "5 - 6 Man Actions") {
			if($row['total']!='') $totals[4] = $row['total'];
			else $totals[4] = '0';
		}
		elseif($row['category'] == "7+ Man Actions") {
			if($row['total']!='') $totals[5] = $row['total'];
			else $totals[5] = '0';
		}
		
	}
		$query = "	SELECT sum(hraps) as operationals
					FROM weekly_report_view
					WHERE weekly_report_view.date >= STR_TO_DATE(  '".$_GET['start_date']."',  '%m-%d-%Y' ) 
					AND weekly_report_view.date <= STR_TO_DATE(  '".$_GET['end_date']."',  '%m-%d-%Y' ) 
					AND LOWER( weekly_report_view.aircraft_tailnumber ) = LOWER(  '".$_GET['tailnumber']."' )";
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		$totals[0] = $row['operationals'];
		
		$query = "	SELECT count(*) as proficiencies
					FROM rappels INNER JOIN operations
					ON rappels.operation_id = operations.id
					WHERE operations.type = 'proficiency_live'
					AND operations.date >= STR_TO_DATE(  '".$_GET['start_date']."',  '%m-%d-%Y' ) 
					AND operations.date <= STR_TO_DATE(  '".$_GET['end_date']."',  '%m-%d-%Y' ) 
					AND LOWER( operations.aircraft_tailnumber ) = LOWER(  '".$_GET['tailnumber']."' )";
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		$totals[1] = $row['proficiencies'];
					
		$text .= "<tr $class><td>".$_GET['start_date']."</td>"
				."<td>".$_GET['end_date']."</td>"
				."<td style=\"text-align:center;\">".$totals[0]."</td>"
				."<td style=\"text-align:center;\">".$totals[1]."</td>"
				."<td style=\"text-align:center;\">".$totals[2]."</td>"
				."<td style=\"text-align:center;\">".$totals[3]."</td>"
				."<td style=\"text-align:center;\">".$totals[4]."</td>"
				."<td style=\"text-align:center;\">".$totals[5]."</td>"
				."</tr></table><br>\n\n";
		

	echo $text;
} // End: function show_proficiency_report()
