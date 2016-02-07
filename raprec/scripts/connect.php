<?php
function connect()	{
	// Connects to the Database and returns a handle to the db connection
	$mode = 1; // 1 = LIVE,   2 = DEV

	switch ($mode) {
	case 1:

		$host = "raprecsiskiyou.db.4665018.hostedresource.com";	// LIVE
		$user = "raprecsiskiyou";		// LIVE
		$passwd="Tr1ckyStats";		// LIVE
		$database = "raprecsiskiyou";	// LIVE
		break;

	case 2:
	default:
		$host = "localhost";	// DEV
		$user = "raprecsiskiyou";	// DEV
		$passwd="Tr1ckyStats";			// DEV
		$database = "raprecsiskiyou";// DEV
		break;
	} //end switch

	$dbh = mysql_connect($host,$user,$passwd, false, 65536) or die(mysql_error());
	mysql_select_db($database) or die(mysql_error());

	return $dbh;
} // end function connect()
?>