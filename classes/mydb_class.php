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
            $port = (int) $config->db_port;

            $sslCa = getenv('MYSQL_ATTR_SSL_CA');
            if ($sslCa !== false && $sslCa !== '' && is_readable($sslCa)) {
                $link = mysqli_init();
                if ($link === false) {
                    printf("Database connect failed: mysqli_init\n");
                    exit();
                }
                mysqli_ssl_set($link, null, null, $sslCa, null, null);
                mysqli_options($link, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
                $connected = mysqli_real_connect(
                    $link,
                    $host,
                    $user,
                    $passwd,
                    $database,
                    $port,
                    null,
                    MYSQLI_CLIENT_SSL
                );
                if (!$connected) {
                    printf("Database connect failed: %s\n", mysqli_connect_error());
                    exit();
                }
                self::$_connection = $link;
            } else {
                self::$_connection = new mysqli($host, $user, $passwd, $database, $port);
                if (self::$_connection->connect_error) {
                    printf("Database connect failed: %s\n", mysqli_connect_error());
                    exit();
                }
            }
		}
		return self::$_connection;
    }
}

