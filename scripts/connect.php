<?php
function connect()	{
	// Connects to the Database and returns a handle to the db connection
	$mode = 2; // 1 = LIVE,   2 = DEV

	switch ($mode) {
	case 1:

		$host = "siskiyou_general.db.4665018.hostedresource.com";	// LIVE
		$user = "siskiyou_general";		// LIVE
		$passwd="Siskiyou09";		// LIVE
		$database = "siskiyou_general";	// LIVE
		break;

	case 2:
	default:
		$host = "localhost";	// DEV
		$user = "siskiyou_general";	// DEV
		$passwd="Siskiyou09";			// DEV
		$database = "siskiyou_general";// DEV
		break;
	} //end switch

	$dbh = mysql_connect($host,$user,$passwd, false, 65536) or die(mysql_error());
	mysql_select_db($database) or die(mysql_error());

	return $dbh;
} // end function connect()
?>