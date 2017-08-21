<?php
namespace app\controllers;

use base\Controller;

class IndexController extends Controller
{
    public function IndexAction()
    {
        $slug = $this->getRequest()->getParam('slug');
//        var_dump($this->getRequest()->getParams());
        $this->render('index/index', array('name'=>$slug, 'class'=>'test'));
    }

    public function ContactAction()
    {
        return $this->renderPartial('index/contact');
    }
}