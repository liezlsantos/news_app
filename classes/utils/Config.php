<?php

class Config {

    private $config;
    private static $instance = null;

    private function __construct() {
        $configFile = getenv('NEWSAPP_SETTINGS_PATH') ?: __DIR__.'/../../app.ini';
        $this->config = parse_ini_file($configFile, true);
    }

    public static function getInstance() {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public static function get($section) {
        $config = Config::getInstance()->config;
        return $config[$section];
    }
}
