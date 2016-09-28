<?php
defined('BASE_PATH') or define('BASE_PATH', dirname(__FILE__) . '/base/');

defined('APP_PATH') or define('APP_PATH', dirname(__FILE__) . '/app/');

require 'autoload.php';

$app = new base\Application();
$app->run();

