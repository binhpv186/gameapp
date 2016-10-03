<?php
namespace base;

class Widget
{
    public function render($view, $option = array())
    {
        ob_start();
        extract($option);
        require_once APP_PATH . 'widgets/views/'.$view.'.php';
        echo ob_get_clean();
    }
}