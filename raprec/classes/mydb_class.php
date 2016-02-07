<?php
class mydb {

    private static $_connection;

    public static function cxn()
    {
		if( !self::$_connection ) {
			/*********************************************************************/
			/*************<< Specify the LIVE dB or the DEV dB >>*****************/
			/*********************************************************************/
			//$mode = $_SESSION['production_mode']; // Either "live" or "dev", defined in "includes/constants.php"
			$mode = "live";

			switch ($mode) {
			case "live":

				$host = "raprecsiskiyou.db.4665018.hostedresource.com";	// LIVE
				$user = "raprecsiskiyou";		// LIVE
				$passwd="Tr1ckyStats";			// LIVE
				$database = "raprecsiskiyou";	// LIVE
				$port = 65536;					// LIVE
				break;

			case "amazon":

				$host = "aatn7x1ex5ss96.cxttuzijpd1s.us-west-2.rds.amazonaws.com:3306";	// LIVE
				$user = "raprecsiskiyou";		// LIVE
				$passwd="Tr1ckyStats";			// LIVE
				$database = "raprecsiskiyou";	// LIVE
				$port = 3306;					// LIVE
				break;


			case "dev":
			default:
				$host = "localhost";			// DEV
				//$host = "127.0.0.1";			// DEV
				$user = "raprecsiskiyou";		// DEV
				$passwd="Tr1ckyStats";			// DEV
				$database = "raprecsiskiyou";	// DEV
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
