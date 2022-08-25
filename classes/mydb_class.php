<?php
require_once(__DIR__ . "/Config.php");

class mydb {

    private static $_connection;

    public static function cxn()
    {
		if( !self::$_connection ) {
		    $config = ConfigService::getConfig();

            $host = $config->db_host;
            $user = $config->db_username;
            $passwd = $config->db_password;
            $database = $config->db_database;
            $port = $config->db_port;


			// Using mysqli (PHP 5)
			self::$_connection = new mysqli($host,$user,$passwd,$database,$port);

			/* Check Connection */
			if (self::$_connection->connect_error) {
				printf("Database connect failed: %s\n", mysqli_connect_error());
				exit();
			}
		}
		return self::$_connection;
    }
}

