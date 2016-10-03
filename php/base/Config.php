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
        $name = explode('.', $name);
        $config = static::$_config;
        foreach ($name as $section) {
            if(isset($config[$section])) {
                $config = $config[$section];
            } else {
                return false;
            }
        }
        return $config;
    }
}