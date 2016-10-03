<?php
function registerAutoload($class) {
    $class = preg_replace('/(\w+)(Controller)$/', '$1', $class);
    if(file_exists(str_replace(array('\\', '/'), DS, strtolower($class) . '.php'))) {
        require_once $class . '.php';
    } else {
        throw new Exception("Class '$class' not Found");
    }

}
spl_autoload_register('registerAutoload', true, true);