<?php

	session_start();
	require_once(__DIR__ . "/../includes/auth_functions.php");

	if($_SESSION['logged_in'] == 1) {
	}//END if($_SESSION['logged_in'] == 1)

	else include_once(__DIR__ . "/../classes/Config.php");
		header('location: ' . ConfigService::getConfig()->app_url . '/admin/index.php');
?>

<html>
	<head>
		<title>Incident Calendar</title>
	</head>

	<body>
    	<iframe src="http://www.google.com/calendar/embed?src=siskiyourappellers%40gmail.com&ctz=America/Los_Angeles" 
        	style="border:0px;" 
            width="800" 
            height="600" 
            frameborder="0" 
            scrolling="no">
        </iframe>
	</body>
</html>
