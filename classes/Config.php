<?php


class EnvConfig
{
    public $db_host;
    public $db_port;
    public $db_username;
    public $db_password;
    public $db_database;

    public $app_url;

    public function __construct($configArray)
    {
        $this->db_username = $configArray['DB_USERNAME'];
        $this->db_password = $configArray['DB_PASSWORD'];
        $this->db_database = $configArray['DB_DATABASE'];
        $this->db_host = $configArray['DB_HOST'];
        $this->db_port = $configArray['DB_PORT'];

        $this->app_url = $configArray['APP_URL'];
    }
}

class ConfigService
{
    private static $_config;

    public static function getConfig()
    {
        if (!self::$_config) {
            self::$_config = new EnvConfig([
                'DB_USERNAME' => getenv('DB_USERNAME'),
                'DB_PASSWORD' => getenv('DB_PASSWORD'),
                'DB_DATABASE' => getenv('DB_DATABASE'),
                'DB_HOST' => getenv('DB_HOST'),
                'DB_PORT' => getenv('DB_PORT'),

                'APP_URL' => getenv('APP_URL')
            ]);
        }

        return self::$_config;
    }
}