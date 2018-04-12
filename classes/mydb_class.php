<?php
class mydb {

    private static $_connection;

    public static function cxn()
    {
		if( !self::$_connection ) {
			/*********************************************************************/
			/*************<< Specify the LIVE dB or the DEV dB >>*****************/
			/*********************************************************************/
			$mode = 1; // 1 = LIVE,   2 = DEV
		
			switch ($mode) {
			case 1:
		
				$host = "localhost";	// LIVE
				$user = "siskiyourappellers";		// LIVE
				$passwd="";	// LIVE
				$database = "siskiyourappellers-tools";	// LIVE
				$port = 65536;			// LIVE
				break;
		
			case 2:
			default:
				$host = "localhost";	// DEV
				$user = "siskiyou_general";		// DEV
				$passwd="Siskiyou09";	// DEV
				$database = "siskiyou_general";	// DEV
				break;

			} //end switch

			// Using mysqli (PHP 5)
			self::$_connection = new mysqli($host,$user,$passwd,$database);

			/* Check Connection */
			if (self::$_connection->connect_error) {
				printf("Database connect failed: %s\n", mysqli_connect_error());
				exit();
			}
		}
		return self::$_connection;
    }
}
?>
