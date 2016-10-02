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

    public function render($fileView, $option = null)
    {
        return $this->view->render($fileView, $option);
    }

    public function renderPartial($fileView, $option = null)
    {
        return $this->view->renderPartial($fileView, $option);
    }

    public function getRequest()
    {
        return \App::$app->request;
    }
}