<?php
namespace base;

class Config
{
    private static $_config;

    public static function load($config)
    {
        static::$_config = $config;
    }

    public static function get($name)
    {
        if(isset(static::$_config[$name])) {
            return static::$_config[$name];
        } else {
            return false;
        }
    }
}