<?php
class mydb {

    private static $_connection;

    public static function cxn()
    {
		if( !self::$_connection ) {
            $host = "localhost";
            $user = "wildrivers";
            $passwd="";
            $database = "wildrivers";
            $port = 65536;

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
