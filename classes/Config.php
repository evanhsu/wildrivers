<?php


class EnvConfig
{
    public $db_host;
    public $db_port;
    public $db_username;
    public $db_password;
    public $db_database;

    public function __construct($db_username, $db_password, $db_database, $db_host = 'localhost', $db_port = 65536 )
    {
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_database = $db_database;
        $this->db_host = $db_host;
        $this->db_port = $db_port;
    }
}

class ConfigService
{
    private static $_config;

    public static function getConfig()
    {
        if (!self::$_config) {
            self::$_config = new EnvConfig(
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                getenv('DB_DATABASE'),
                getenv('DB_HOST'),
                getenv('DB_PORT')
            );
        }

        return self::$_config;
    }
}