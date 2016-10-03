<?php
class Widget
{
    public static function get($name, Array $option = array())
    {
        $className = '\\app\\widgets\\'.ucfirst($name);
        $widget = new $className;
        foreach ($option as $param => $value) {
            if (property_exists ($widget, $param)) {
                $widget->$param = $value;
            }
        }
        $widget->run();
    }


}