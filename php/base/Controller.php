<?php
namespace base;

use base\View;

class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function render($fileView, $option)
    {
        return $this->view->render($fileView, $option);
    }
}