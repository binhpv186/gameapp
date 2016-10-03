<?php

$startTime = microtime(true);

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

defined('ROOT') or define('ROOT', dirname(__FILE__) . '/');

defined('BASE_PATH') or define('BASE_PATH', dirname(__FILE__) . '/base/');

defined('APP_PATH') or define('APP_PATH', dirname(__FILE__) . '/app/');

require 'autoload.php';

$config = require_once APP_PATH . 'config/config.php';

\base\Config::load($config);

class App {
    public static $app;

    public function __construct($config)
    {
        self::$app = new base\Application($config);
    }

    public function run()
    {
        self::$app->run();
    }
}
$app = new App($config);
$app->run();
echo 'Loaded time: '. ((microtime(true)-$startTime)) . 's. Memory usage: ' . (memory_get_usage(true)/(1024*1024)) . 'MB';