<?php
namespace base;

abstract class Widget
{
    public function __construct() {}

    public function render($view, $data = array())
    {
        ob_start();
        extract($data);
        require_once APP_PATH . 'widgets/views/'.$view.'.php';
        echo ob_get_clean();
    }

    public static function widget($params = null) {
        $class = new static();
        $class->run($params);
    }
    public function run($params = null) {}
}