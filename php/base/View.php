<?php
namespace base;

class View
{
    protected $_theme = 'basic';

    protected $layout = 'index';

    public function __construct()
    {

    }

    public function render($fileView, $data = null)
    {
        ob_start();
        if(is_array($data)) {
            extract($data);
        }
        require(APP_PATH . 'views/' . $fileView . '.php');
		$content = ob_get_clean()."\n";
        require_once APP_PATH . 'templates/' . $this->_theme . '/' . $this->layout . '.php';
    }

    public function renderPartial($fileView, $data = null)
    {
        if(is_array($data)) {
            extract($data);
        }
        require_once(APP_PATH . 'views/' . $fileView . '.php');
    }

    public function setTheme($theme = '')
    {
        $this->_theme = $theme;
    }

    public function setLayout($layout = '')
    {
        $this->_layout = $layout;
    }
}