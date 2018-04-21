<?php

	//if(isset($_GET['session_id'])) session_id($_GET['session_id']);
	session_start();
/*
	ini_set('include_path',ini_get('include_path').':../includes:../includes/Zend:');
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_AuthSub');
	Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
	Zend_Loader::loadClass('Zend_Gdata_Calendar');
*/
	require_once('../includes/auth_functions.php');
//	require_once('../classes/roster_class.php');
	require_once('../classes/person_class.php');
//	require_once('../classes/course_enrollment_class.php');
	include("../includes/g_calendar_functions.php");
	
	//if(substr(strtolower($_SERVER['PHP_SELF']),1,9) == "incidents") header('location: http://incidents.siskiyourappellers.com');
	//$php_self = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

	$php_self = $_SERVER['PHP_SELF'];

	if($_GET['logout'] == 1) {
		session_destroy();
		session_start();
	}

	if($_SESSION['logged_in'] == 1) {
		$allow_edit = 0;
		if(check_access("edit_training")) $allow_edit = 1;

	}//END if($_SESSION['logged_in'] == 1)

	else header('location: https://wildrivers.firecrew.us/admin/index.php');
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Training Tracker :: Wild Rivers Ranger District</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="training, iqcs, ics, class, classes, courses, qualifications, quals, red card, trainee, task book, positions, fires, list, management, helitack, hecm, crew, prineville" />
<meta name="Description" content="Schedule training courses and view qualifications" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/inventory.css" />
</head>


<body>
<div id="wrapper" style="height:170px; min-height:170px; width:900px;">
	<div id="banner">
        <a href="https://wildrivers.firecrew.us/training" style="display:block; width:900px; height:75px; padding:0;"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
    </div>
</div>

<div id="wrapper" style="width:95%;">
	<div id="content" style="text-align:center">
    
    <?php
	echo "<br /><h2 style=\"font-size:1.7em;\">Upcoming Courses</h2>\n"
		."<table style=\"font-size:1.5em; margin:0 auto 0 auto;\">\n"
		."<tr><th>Start Date</th><th>Course</th><th>Student</th><th>Status</th></tr>\n";
	
	$query = "SELECT
			  scheduled_courses.id,
			  scheduled_courses.name as course_name,
			  scheduled_courses.date_start,
			  scheduled_courses.date_end,
			  CONCAT(people.firstname,' ',people.lastname) as student,
			  enrollment.status
			  
			  FROM
			  scheduled_courses INNER JOIN enrollment
			  ON enrollment.scheduled_course_id = scheduled_courses.id
			  INNER JOIN people on people.id = enrollment.student_id
			  
			  WHERE
			  scheduled_courses.date_end >= CURDATE()";
	
	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->error != '') throw new Exception('There was a problem retrieving enrolled courses for this crew');
	
	$last_course_id = -1;
	$row_class = 'evn';
	while($row = $result->fetch_assoc()) {
		if($row['id'] == $last_course_id) {
			echo "<tr class=\"".$row_class."\"><td colspan=\"2\">&nbsp;</td>";
		}
		else {
			if($row_class == 'evn') $row_class = 'odd';
			else $row_class = 'evn';
			echo "<tr class=\"".$row_class."\"><td>".$row['date_start']."</td>"
				."<td>".$row['course_name']."</td>";
		}
		echo "<td>".$row['student']."</td><td>".$row['status']."</td></tr>\n";
		$last_course_id = $row['id'];
	} // END while()
	
	echo "</table><br />\n";
    
    ?>
    </div><!-- END div 'content' -->
</div><!-- END div 'wrapper' -->
</body>
</html>