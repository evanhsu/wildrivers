<?php
class mydb {

    private static $_connection;

    public static function cxn()
    {
		if( !self::$_connection ) {
			/*********************************************************************/
			/*************<< Specify the LIVE dB or the DEV dB >>*****************/
			/*********************************************************************/
			$mode = 2; // 1 = LIVE,   2 = DEV
		
			switch ($mode) {
			case 1:
		
				$host = "siskiyou_general.db.4665018.hostedresource.com";	// LIVE
				$user = "siskiyou_general";		// LIVE
				$passwd="Siskiyou09";	// LIVE
				$database = "siskiyou_general";	// LIVE
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
