<?php
function registerAutoload($class) {
    require $class . '.php';
}
spl_autoload_register('registerAutoload', true, true);