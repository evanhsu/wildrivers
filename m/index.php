<?php
	// This page is for mobile devices.  It simplifies the URL of the admin page
	// as well as setting the $_SESSION['mobile'] flag
	session_start();
	$_SESSION['mobile'] = 1;
	header('location: http://www.siskiyourappellers.com/admin/index.php');
?>