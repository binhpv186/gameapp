<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

defined('ROOT') or define('ROOT', dirname(__FILE__) . '/');

defined('BASE_PATH') or define('BASE_PATH', dirname(__FILE__) . '/base/');

defined('APP_PATH') or define('APP_PATH', dirname(__FILE__) . '/app/');

require 'autoload.php';

$config = require_once APP_PATH . 'config/config.php';

\base\Config::load($config);

class App extends base\Application {
    public static $app;
}
$app = new App($config);
$app->run();